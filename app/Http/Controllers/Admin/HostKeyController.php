<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostKey;
use App\Models\Tournament;

class HostKeyController extends Controller
{
    public function generate(Tournament $tournament)
    {
        // Revoke existing active key first
        $tournament->hostKeys()
            ->whereNull('revoked_at')
            ->update(['revoked_at' => now()]);

        // Generate new plain key
        $plainKey = HostKey::generatePlainKey();

        HostKey::create([
            'tournament_id' => $tournament->id,
            'key_hash'      => HostKey::hashKey($plainKey),
            'created_at'    => now(),
        ]);

        // Flash success
        return redirect()->route('admin.tournaments.show', $tournament)
            ->with('success', 'Host key baru berhasil digenerate.');
    }

    public function revoke(Tournament $tournament, HostKey $hostKey)
    {
        $hostKey->update(['revoked_at' => now()]);

        return redirect()->route('admin.tournaments.show', $tournament)
            ->with('success', 'Host key berhasil dicabut.');
    }
}
