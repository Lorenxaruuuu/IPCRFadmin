<?php

namespace App\Http\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IpcrfRecord;
use App\Models\Employee;
use App\Models\Province;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IpcrfController extends Controller
{
    public function dashboard()
    {
        $stats = [
            // total records (could be > employees if multiple uploads per person)
            'total_uploaded' => IpcrfRecord::count(),
            // unique employees who have uploaded at least once
            'uploaded_employees' => IpcrfRecord::distinct('employee_id')->count('employee_id'),
            'active_forms' => IpcrfRecord::where('status', 'Saved')->count(),
            'notices' => \App\Models\Notice::where('is_active', true)->count(),
            'total_employees' => \App\Models\Employee::count(),
        ];
        
        $recentSubmissions = IpcrfRecord::with('employee.school.municipality')
            ->latest('uploaded_at')
            ->take(10)
            ->get();

        // …existing extra employees logic unchanged…

        // if there aren't ten records yet, add some employees who have no record
        if ($recentSubmissions->count() < 10) {
            $needed = 10 - $recentSubmissions->count();
            $idsWith = $recentSubmissions->pluck('employee_id')->filter()->unique();
            $extras = \App\Models\Employee::whereNotIn('id', $idsWith)
                ->take($needed)
                ->get();
            foreach ($extras as $e) {
                $fake = new IpcrfRecord();
                $fake->setRelation('employee', $e);
                $fake->uploaded_at = null;
                $fake->status = 'No Record';
                $recentSubmissions->push($fake);
            }
        }
        
        $provinces = Province::where('region', 'Region 11')->get();

        $announcements = \App\Models\Notice::with('poster')
            ->where('is_active', true)
            ->latest('posted_at')
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact('stats', 'recentSubmissions', 'provinces', 'announcements'));
    }

    // Direct upload form - NO role selection step
    public function uploadForm()
    {
        $provinces = Province::where('region', 'Region 11')->get();
        return view('admin.upload', compact('provinces'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'employee_id' => 'required|string|max:255',
                'employee_name' => 'required|string|max:255',
                'province' => 'required|exists:provinces,id',
                'municipality' => 'required|exists:municipalities,id',
                'school' => 'required|string|max:255',
                'file' => 'required|file|mimes:pdf,xlsx,xls,doc,docx|max:10240',
                'semester' => 'required|in:1st,2nd',
                'school_year' => 'required|string',
                'role' => 'required|in:Teacher,Master Teacher,Principal,Supervisor', // Role in form now
            ]);

            // ensure school exists for selected municipality before touching employee
            $school = \App\Models\School::firstOrCreate([
                'name' => $validated['school'],
                'municipality_id' => $validated['municipality'],
            ], [
                // new schools need a unique school_id code
                'school_id' => Str::upper(Str::random(8)),
            ]);

            // look up or create employee by employee_id (assumed unique identifier)
            $employee = \App\Models\Employee::where('employee_id', $validated['employee_id'])->first();
            if (!$employee) {
                // parse simple first/last from name
                $parts = explode(' ', $validated['employee_name'], 2);
                $first = $parts[0];
                $last = $parts[1] ?? '';
                $employee = \App\Models\Employee::create([
                    'employee_id' => $validated['employee_id'],
                    'first_name' => $first,
                    'last_name' => $last,
                    'school_id' => $school->id,
                    'role' => $validated['role'],
                    'email' => $validated['employee_id'] . '@example.com',
                ]);
            } else {
                // if user supplied a name but the id already exists, we'll return a warning
                if (trim($validated['employee_name']) !== '' && ($employee->first_name . ' ' . $employee->last_name) !== $validated['employee_name']) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['success' => true, 'message' => 'Employee ID already exists, using existing record.']);
                    }
                    // continue after warning; employee record will still be used
                    session()->flash('warning', 'Employee ID already exists, using existing record.');
                }

                // update school if changed
                if ($employee->school_id !== $school->id) {
                    $employee->school_id = $school->id;
                    $employee->save();
                }
            }

            $file = $request->file('file');
            $path = $file->store('ipcrf_records', 'private');
            
            IpcrfRecord::create([
                'employee_id' => $employee->id,
                // allow null when not authenticated
                'uploaded_by' => auth()->id(),
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'semester' => $validated['semester'],
                'school_year' => $validated['school_year'],
                'role' => $validated['role'], 
                'status' => 'Saved',
                'uploaded_at' => now(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('admin.records')->with('success', 'IPCRF uploaded successfully');
        } catch (\Throwable $e) {
            // log for diagnostics
            \Log::error('IPCRF upload error: '.$e->getMessage(), ['exception' => $e]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : []], 422);
            }

            // return to form with warning so user sees message in UI
            return redirect()->back()->with('warning', $e->getMessage())->withInput();
        }
    }

    public function records(Request $request)
    {
        // base query for existing records
        $recordQuery = IpcrfRecord::with(['employee.school.municipality.province']);
        
        if ($request->filled('province')) {
            $recordQuery->whereHas('employee.school.municipality.province', function($q) use ($request) {
                $q->where('id', $request->province);
            });
        }
        
        if ($request->filled('municipality')) {
            $recordQuery->whereHas('employee.school.municipality', function($q) use ($request) {
                $q->where('id', $request->municipality);
            });
        }

        if ($request->filled('semester')) {
            $recordQuery->where('semester', $request->semester);
        }

        if ($request->filled('year')) {
            $recordQuery->where('school_year', $request->year);
        }

        if ($request->filled('employee_id')) {
            $recordQuery->whereHas('employee', function($q) use ($request) {
                $q->where('employee_id', 'like', '%' . $request->employee_id . '%');
            });
        }

        // get records first
        $recordsCollection = $recordQuery->latest('uploaded_at')->get();

        // now append employees without any record (subject to same province/municipality filters)
        $needed = 20 - $recordsCollection->count();
        if ($needed > 0) {
            $empQuery = \App\Models\Employee::with('school.municipality.province')
                ->whereDoesntHave('ipcrfRecords');

            if ($request->filled('province')) {
                $empQuery->whereHas('school.municipality.province', function($q) use ($request) {
                    $q->where('id', $request->province);
                });
            }
            if ($request->filled('municipality')) {
                $empQuery->whereHas('school.municipality', function($q) use ($request) {
                    $q->where('id', $request->municipality);
                });
            }

            if ($request->filled('employee_id')) {
                $empQuery->where('employee_id', 'like', '%' . $request->employee_id . '%');
            }

            $extras = $empQuery->take($needed)->get();
            foreach ($extras as $e) {
                $fake = new IpcrfRecord();
                $fake->setRelation('employee', $e);
                $fake->uploaded_at = null;
                $fake->status = 'No Record';
                $recordsCollection->push($fake);
            }
        }

        // create paginator manually (ensures filters preserved)
        $page = $request->input('page', 1);
        $perPage = 20;
        $slice = $recordsCollection->slice(($page - 1) * $perPage, $perPage);
        $records = new \Illuminate\Pagination\LengthAwarePaginator(
            $slice->values(),
            $recordsCollection->count(),
            $perPage,
            $page,
            ['path' => url()->current(), 'query' => $request->query()]
        );

        $provinces = Province::where('region', 'Region 11')->get();
        
        // return JSON for AJAX requests (dashboard filtering)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'records' => $records->items(),
                'pagination' => [
                    'total' => $records->total(),
                    'per_page' => $records->perPage(),
                    'current_page' => $records->currentPage(),
                    'last_page' => $records->lastPage(),
                ],
            ]);
        }
        
        return view('admin.records', compact('records', 'provinces'));
    }

    public function download($id)
    {
        $record = IpcrfRecord::findOrFail($id);
        return Storage::disk('private')->download($record->file_path, $record->file_name);
    }

    public function getMunicipalities($provinceId)
    {
        return response()->json(
            \App\Models\Municipality::where('province_id', $provinceId)->get()
        );
    }

    public function getSchools($municipalityId)
    {
        return response()->json(
            \App\Models\School::where('municipality_id', $municipalityId)->get()
        );
    }
}