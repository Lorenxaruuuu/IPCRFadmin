<?php
namespace App\Http\Controllers\Admin;

use app\Http\Controllers\Controller;
use app\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::with('uploader')
            ->where('is_active', true)
            ->orderBy('published_at', 'desc')
            ->get();
            
        return view('admin.forms', compact('forms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Template,Guidelines,Reference',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('forms', 'private');

        Form::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'file_path' => $path,
            'uploaded_by' => auth()->id(),
            'published_at' => now(),
        ]);

        return back()->with('success', 'Form published successfully');
    }

    public function download($id)
    {
        $form = Form::findOrFail($id);
        return Storage::disk('private')->download($form->file_path);
    }

    public function destroy($id)
    {
        $form = Form::findOrFail($id);
        $form->update(['is_active' => false]);
        return back()->with('success', 'Form removed');
    }
}