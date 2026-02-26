<?php

namespace App\Http\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IpcrfRecord;
use App\Models\Employee;
use App\Models\Province;
use Illuminate\Support\Facades\Storage;

class IpcrfController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_uploaded' => IpcrfRecord::count(),
            'active_forms' => IpcrfRecord::where('status', 'Saved')->count(),
            'notices' => \App\Models\Notice::where('is_active', true)->count(),
        ];
        
        $recentSubmissions = IpcrfRecord::with('employee.school.municipality')
            ->latest('uploaded_at')
            ->take(10)
            ->get();
            
        return view('admin.dashboard', compact('stats', 'recentSubmissions'));
    }

    // Direct upload form - NO role selection step
    public function uploadForm()
    {
        $provinces = Province::all();
        return view('admin.upload', compact('provinces'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'file' => 'required|file|mimes:pdf,xlsx,xls|max:10240',
            'semester' => 'required|in:1st,2nd',
            'school_year' => 'required|string',
            'role' => 'required|in:Teacher,Master Teacher,Principal,Supervisor', // Role in form now
        ]);
        
        $file = $request->file('file');
        $path = $file->store('ipcrf_records', 'private');
        
        IpcrfRecord::create([
            'employee_id' => $validated['employee_id'],
            'uploaded_by' => auth()->id() ?? 1,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'semester' => $validated['semester'],
            'school_year' => $validated['school_year'],
            'role' => $validated['role'], 
            'status' => 'Saved',
            'uploaded_at' => now(),
        ]);
        
        return redirect()->route('admin.records')->with('success', 'IPCRF uploaded successfully');
    }

    public function records(Request $request)
    {
        $query = IpcrfRecord::with(['employee.school.municipality.province']);
        
        if ($request->filled('province')) {
            $query->whereHas('employee.school.municipality.province', function($q) use ($request) {
                $q->where('id', $request->province);
            });
        }
        
        if ($request->filled('municipality')) {
            $query->whereHas('employee.school.municipality', function($q) use ($request) {
                $q->where('id', $request->municipality);
            });
        }
        
        $records = $query->latest('uploaded_at')->paginate(20);
        $provinces = Province::all();
        
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
}