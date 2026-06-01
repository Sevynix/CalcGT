<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatchNoteController extends Controller
{
    public function index()
    {
        $patchNotes = \App\Models\PatchNote::orderBy('created_at', 'desc')->get();
        return view('admin.patch-notes.index', compact('patchNotes'));
    }

    public function create()
    {
        return view('admin.patch-notes.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'version' => 'nullable|string|max:50',
            'content' => 'required|string',
        ]);

        \App\Models\PatchNote::create([
            'title' => $request->title,
            'version' => $request->version,
            'content' => $request->content,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.patch-notes.index')->with('success', 'Patch Note created successfully!');
    }

    public function edit(\App\Models\PatchNote $patchNote)
    {
        return view('admin.patch-notes.form', compact('patchNote'));
    }

    public function update(Request $request, \App\Models\PatchNote $patchNote)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'version' => 'nullable|string|max:50',
            'content' => 'required|string',
        ]);

        $patchNote->update([
            'title' => $request->title,
            'version' => $request->version,
            'content' => $request->content,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.patch-notes.index')->with('success', 'Patch Note updated successfully!');
    }

    public function destroy(\App\Models\PatchNote $patchNote)
    {
        $patchNote->delete();
        return redirect()->route('admin.patch-notes.index')->with('success', 'Patch Note deleted successfully!');
    }
}
