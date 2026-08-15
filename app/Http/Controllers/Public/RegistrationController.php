<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function store(Request $request, Competition $competition)
    {
        if (!$competition->has_registration) {
            return response()->json(['error' => 'Lomba ini tidak memiliki pendaftaran publik.'], 403);
        }

        if (!$competition->isRegistrationOpen()) {
            return response()->json(['error' => 'Pendaftaran tidak sedang dibuka.'], 422);
        }

        if (!$competition->tournament->is_visible) {
            return response()->json(['error' => 'Tournament tidak tersedia.'], 403);
        }

        $request->validate([
            'grow_id'      => 'required|string|min:3|max:18',
            'client_token' => 'nullable|string|max:64',
        ]);

        $growId           = $request->grow_id;
        $growIdNormalized = Registration::normalizeGrowId($growId);
        $clientToken      = $request->client_token ?? Str::uuid()->toString();
        $ipHash           = Registration::hashIp($request->ip());

        // Check if this client_token already registered for this competition
        $alreadyRegistered = Registration::where('competition_id', $competition->id)
            ->where('client_token', $clientToken)
            ->exists();

        if ($alreadyRegistered) {
            return response()->json([
                'error' => 'Kamu sudah terdaftar di lomba ini.',
                'client_token' => $clientToken,
            ], 422);
        }

        // Check if GrowID already registered (unique constraint)
        $growIdExists = Registration::where('competition_id', $competition->id)
            ->where('grow_id_normalized', $growIdNormalized)
            ->exists();

        if ($growIdExists) {
            return response()->json([
                'error' => 'GrowID ini sudah terdaftar di lomba ini.',
            ], 422);
        }

        // Detect IP-based potential duplicate (soft flag)
        $sameIpCount = Registration::where('competition_id', $competition->id)
            ->where('ip_hash', $ipHash)
            ->where('created_at', '>=', now()->subMinutes(30))
            ->count();

        $flagged = $sameIpCount >= 1;

        $registration = Registration::create([
            'competition_id'   => $competition->id,
            'grow_id'          => $growId,
            'grow_id_normalized' => $growIdNormalized,
            'client_token'     => $clientToken,
            'ip_hash'          => $ipHash,
            'flagged_duplicate' => $flagged,
            'created_at'       => now(),
        ]);

        return response()->json([
            'success'      => true,
            'message'      => "Berhasil daftar sebagai {$growId}!",
            'client_token' => $clientToken,
            'grow_id'      => $growId,
        ]);
    }
}
