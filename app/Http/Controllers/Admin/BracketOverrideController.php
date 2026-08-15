<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BracketNode;

class BracketOverrideController extends Controller
{
    /**
     * Admin-only: unlock a locked bracket node to allow correction.
     */
    public function unlock(BracketNode $node)
    {
        $node->update(['locked' => false]);

        return back()->with('success', "Match ronde {$node->round} posisi {$node->position} berhasil di-unlock.");
    }

    /**
     * Admin-only: force set winner on any node.
     */
    public function forceWinner(BracketNode $node)
    {
        $winner = request()->validate([
            'winner' => 'required|string',
        ])['winner'];

        $node->update(['winner' => $winner, 'locked' => false]);

        // Re-promote winner
        app(\App\Services\BracketService::class)->setWinner($node->refresh(), $winner);

        return back()->with('success', "Pemenang match berhasil di-override menjadi: $winner");
    }
}
