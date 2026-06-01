<?php

namespace App\Http\Controllers;

use App\Models\CommunityPost;
use App\Models\CommunityOpinion;
use Illuminate\Http\Request;

class CommunityOpinionController extends Controller
{
    public function index()
    {
        $posts = CommunityPost::where('is_published', true)->orderBy('created_at', 'desc')->get();
        return view('opinions.index', compact('posts'));
    }

    public function show(CommunityPost $communityPost)
    {
        if (!$communityPost->is_published) {
            abort(404);
        }
        return view('opinions.show', compact('communityPost'));
    }

    public function store(Request $request, CommunityPost $communityPost)
    {
        if (!$communityPost->is_published) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'opinion' => 'required|string|max:2000',
        ]);

        $communityPost->opinions()->create([
            'name' => $validated['name'] ?: 'Anonymous',
            'opinion' => $validated['opinion'],
        ]);

        return redirect()->route('opinions.index')->with('success', 'Your opinion has been submitted successfully! Thank you for your feedback.');
    }
}
