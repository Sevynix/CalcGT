<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostKey;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::withCount('competitions')
            ->latest()
            ->get();

        return view('admin.tournaments.index', compact('tournaments'));
    }

    public function create()
    {
        return view('admin.tournaments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'status'       => 'required|in:draft,test_drive,active,finished',
            'banner_image' => 'nullable|image|max:10240',
            'banner_aspect_ratio' => 'required|in:3:2,16:9',
        ]);

        $data = [
            'name'        => $request->name,
            'description' => $request->description,
            'status'      => $request->status,
            'banner_aspect_ratio' => $request->banner_aspect_ratio,
            'is_visible'  => false,
            'created_by'  => Auth::id(),
        ];

        if ($request->hasFile('banner_image')) {
            $data['banner_path'] = $request->file('banner_image')->store('tournaments', 'public');
        }

        $tournament = Tournament::create($data);

        return redirect()->route('admin.tournaments.show', $tournament)
            ->with('success', 'Tournament berhasil dibuat.');
    }

    public function show(Tournament $tournament)
    {
        $tournament->load(['competitions', 'hostKeys']);
        $activeKey = $tournament->activeHostKey();

        return view('admin.tournaments.show', compact('tournament', 'activeKey'));
    }

    public function edit(Tournament $tournament)
    {
        return view('admin.tournaments.edit', compact('tournament'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'status'       => 'required|in:draft,test_drive,active,finished',
            'banner_image' => 'nullable|image|max:10240',
            'banner_aspect_ratio' => 'required|in:3:2,16:9',
            'remove_banner'=> 'nullable|boolean',
        ]);

        $data = $request->only('name', 'description', 'status', 'banner_aspect_ratio');

        if ($request->has('remove_banner') && $request->remove_banner) {
            if ($tournament->banner_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($tournament->banner_path);
                $data['banner_path'] = null;
            }
        } elseif ($request->hasFile('banner_image')) {
            if ($tournament->banner_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($tournament->banner_path);
            }
            $data['banner_path'] = $request->file('banner_image')->store('tournaments', 'public');
        }

        $tournament->update($data);

        return redirect()->route('admin.tournaments.show', $tournament)
            ->with('success', 'Tournament diperbarui.');
    }

    public function toggleVisibility(Tournament $tournament)
    {
        $tournament->update(['is_visible' => !$tournament->is_visible]);

        $msg = $tournament->is_visible ? 'ditampilkan' : 'disembunyikan';
        return back()->with('success', "Tournament '{$tournament->name}' sekarang {$msg}.");
    }

    public function destroy(Tournament $tournament)
    {
        if ($tournament->banner_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($tournament->banner_path);
        }
        $tournament->delete();
        return redirect()->route('admin.tournaments.index')->with('success', 'Tournament dihapus.');
    }
}
