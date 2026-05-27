<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $publishedFeedbacks = Feedback::where('is_published', true)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
            
        return view('feedback.index', compact('publishedFeedbacks'));
    }

    public function create()
    {
        return view('feedback.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:bug,suggestion',
            'message' => 'required|string|max:1000',
            'image' => 'nullable|image|max:5120', // Max 5MB
        ]);

        $data = $request->only(['name', 'type', 'message']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('feedback', 'public');
            $data['image_path'] = 'storage/' . $path;
        }

        Feedback::create($data);

        return redirect()->route('feedback.create')->with('success', 'Thank you! Your feedback has been submitted successfully.');
    }
}
