<?php

namespace App\Http\Middleware;

use App\Models\Tournament;
use Closure;
use Illuminate\Http\Request;

class TournamentVisibleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $tournamentId = $request->route('tournament') ?? $request->route('id');

        if ($tournamentId) {
            $tournament = Tournament::find($tournamentId);

            if ($tournament && !$tournament->is_visible) {
                abort(403, 'Tournament ini sedang tidak tersedia.');
            }
        }

        return $next($request);
    }
}
