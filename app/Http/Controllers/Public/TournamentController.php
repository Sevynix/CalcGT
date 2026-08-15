<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::visible()
            ->withCount('competitions')
            ->where('status', '!=', 'draft')
            ->latest()
            ->get();

        return view('public.index', compact('tournaments'));
    }

    public function show(Tournament $tournament)
    {
        if (!$tournament->is_visible) {
            abort(403, 'Tournament ini sedang tidak tersedia.');
        }

        $competitions = $tournament->competitions()->with('registrations')->get();

        return view('public.tournament', compact('tournament', 'competitions'));
    }
}
