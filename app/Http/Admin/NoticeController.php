<?php
namespace App\Http\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller {
    
    public function index() {
        $notices = Notice::with('poster')->active()->get();
        return view('admin.notices', compact('notices'));
    }
    
    public function store(Request $request) {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:Low,Medium,High',
        ]);
        
        Notice::create([
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'priority' => $validated['priority'],
            'posted_by' => auth()->id() ?? 1,
            'posted_at' => now(),
        ]);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notice posted successfully');
    }
    
    public function destroy($id) {
        $notice = Notice::findOrFail($id);
        $notice->update(['is_active' => false]);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notice removed');
    }
}