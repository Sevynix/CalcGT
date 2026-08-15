<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\HostKey;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showForm()
    {
        if (session('host.tournament_id')) {
            return redirect()->route('host.timeline');
        }

        return view('host.auth');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $hostKey = HostKey::findByPlainKey($request->key);

        if (!$hostKey) {
            return back()->withErrors(['key' => 'Host key tidak valid atau sudah dicabut.']);
        }

        // Mark used_at (first use timestamp)
        if (!$hostKey->used_at) {
            $hostKey->update(['used_at' => now()]);
        }

        // Store in session
        session([
            'host' => [
                'tournament_id' => $hostKey->tournament_id,
                'key_id'        => $hostKey->id,
            ],
        ]);

        return redirect()->route('host.timeline')
            ->with('success', 'Selamat datang, Host!');
    }

    public function logout()
    {
        session()->forget('host');
        return redirect()->route('host.login');
    }
}
