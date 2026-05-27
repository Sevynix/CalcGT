<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function update(Request $request, Feedback $feedback)
    {
        $request->validate([
            'admin_reply' => 'nullable|string|max:1000',
        ]);

        $feedback->update([
            'is_read' => $request->has('is_read'),
            'is_published' => $request->has('is_published'),
            'admin_reply' => $request->admin_reply,
        ]);

        return back()->with('success', 'Feedback updated successfully.');
    }

    public function destroy(Feedback $feedback)
    {
        // Delete associated image if exists
        if ($feedback->image_path && str_starts_with($feedback->image_path, 'storage/')) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('storage/', '', $feedback->image_path));
        }
        
        $feedback->delete();
        return back()->with('success', 'Feedback deleted successfully.');
    }
}
