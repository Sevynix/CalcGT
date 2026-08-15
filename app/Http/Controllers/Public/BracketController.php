<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Services\BracketService;
use Illuminate\Http\Request;

class BracketController extends Controller
{
    public function __construct(private BracketService $bracketService) {}

    /**
     * Return bracket JSON for polling - used by frontend.
     */
    public function data(Competition $competition)
    {
        if (!$competition->tournament->is_visible) {
            return response()->json(['error' => 'Tournament tidak tersedia.'], 403);
        }

        $phase1 = $competition->phase1();
        $phase2 = $competition->phase2();

        return response()->json([
            'competition_status' => $competition->status,
            'phase1' => $phase1 ? $this->bracketService->getBracketData($phase1) : null,
            'phase2' => $phase2 ? $this->bracketService->getBracketData($phase2) : null,
        ]);
    }
    public function show(Competition $competition)
    {
        if (!$competition->tournament->is_visible) {
            abort(404, 'Tournament tidak ditemukan.');
        }

        $phase1 = $competition->phase1();
        $phase2 = $competition->phase2();

        $bracket1 = $phase1 ? $this->bracketService->getBracketData($phase1) : null;
        $bracket2 = $phase2 ? $this->bracketService->getBracketData($phase2) : null;

        return view('public.bracket', compact('competition', 'bracket1', 'bracket2'));
    }
}

