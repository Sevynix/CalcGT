<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HostMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $hostSession = session('host');

        if (!$hostSession || empty($hostSession['tournament_id']) || empty($hostSession['key_id'])) {
            return redirect()->route('host.login');
        }

        // Verify the key is still valid (not revoked)
        $key = \App\Models\HostKey::find($hostSession['key_id']);
        if (!$key || !$key->isValid()) {
            session()->forget('host');
            return redirect()->route('host.login')->with('error', 'Host key telah dicabut. Hubungi admin.');
        }

        // Inject tournament into request for convenience
        $request->attributes->set('host_tournament_id', $hostSession['tournament_id']);
        $request->attributes->set('host_key_id', $hostSession['key_id']);

        return $next($request);
    }
}
