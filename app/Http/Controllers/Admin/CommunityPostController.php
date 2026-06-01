<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommunityPost;
use Illuminate\Http\Request;

class CommunityPostController extends Controller
{
    public function index()
    {
        $posts = CommunityPost::withCount('opinions')->orderBy('created_at', 'desc')->get();
        return view('admin.community-posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.community-posts.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'nullable|boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');
        CommunityPost::create($validated);

        return redirect()->route('admin.community-posts.index')->with('success', 'Community Post created successfully!');
    }

    public function edit(CommunityPost $communityPost)
    {
        return view('admin.community-posts.form', ['post' => $communityPost]);
    }

    public function update(Request $request, CommunityPost $communityPost)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'nullable|boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');
        $communityPost->update($validated);

        return redirect()->route('admin.community-posts.index')->with('success', 'Community Post updated successfully!');
    }

    public function destroy(CommunityPost $communityPost)
    {
        $communityPost->delete();
        return redirect()->route('admin.community-posts.index')->with('success', 'Community Post deleted successfully!');
    }
}
