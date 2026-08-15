<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Tournament;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    private function hostTournament(Request $request): Tournament
    {
        return Tournament::findOrFail($request->attributes->get('host_tournament_id'));
    }

    public function index(Request $request)
    {
        $tournament   = $this->hostTournament($request);
        $competitions = $tournament->competitions;

        return view('host.timeline', compact('tournament', 'competitions'));
    }

    public function reveal(Request $request, Competition $competition)
    {
        $this->authorizeCompetition($request, $competition);

        if (!$competition->canBeRevealed()) {
            return back()->with('error', 'Lomba sebelumnya harus di-reveal terlebih dahulu.');
        }

        if ($competition->status !== 'hidden') {
            return back()->with('error', 'Lomba ini sudah di-reveal.');
        }

        $competition->update(['status' => 'revealed']);

        return back()->with('success', "Lomba \"{$competition->name}\" berhasil di-reveal!");
    }

    /**
     * Open registration - only available when has_registration = true.
     */
    public function openRegistration(Request $request, Competition $competition)
    {
        $this->authorizeCompetition($request, $competition);

        if (!$competition->has_registration) {
            return back()->with('error', 'Lomba ini tidak memiliki fitur pendaftaran.');
        }

        if (!in_array($competition->status, ['revealed', 'registration_closed'])) {
            return back()->with('error', 'Hanya lomba yang sudah di-reveal atau ditutup pendaftarannya yang bisa dibuka.');
        }

        $competition->update(['status' => 'registration_open']);

        return back()->with('success', "Pendaftaran \"{$competition->name}\" dibuka.");
    }

    public function closeRegistration(Request $request, Competition $competition)
    {
        $this->authorizeCompetition($request, $competition);

        if ($competition->status !== 'registration_open') {
            return back()->with('error', 'Pendaftaran belum dibuka.');
        }

        $competition->update(['status' => 'registration_closed']);

        return back()->with('success', "Pendaftaran \"{$competition->name}\" ditutup.");
    }

    public function seedUsers(Request $request, Competition $competition)
    {
        $this->authorizeCompetition($request, $competition);

        if (!$competition->has_registration) {
            return back()->with('error', 'Lomba ini tidak memiliki fitur pendaftaran.');
        }

        if (!in_array($competition->status, ['revealed', 'registration_open'])) {
            return back()->with('error', 'Lomba harus di-reveal atau pendaftaran terbuka untuk menambah user dummy.');
        }

        $toAdd = 40;
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < $toAdd; $i++) {
            $growId = $faker->userName . rand(100, 999);
            $competition->registrations()->create([
                'grow_id'            => $growId,
                'grow_id_normalized' => \App\Models\Registration::normalizeGrowId($growId),
                'client_token'       => 'dummy_' . \Illuminate\Support\Str::random(10),
                'ip_hash'            => \App\Models\Registration::hashIp('127.0.0.1'),
                'flagged_duplicate'  => false,
            ]);
        }

        return back()->with('success', "Berhasil menambahkan {$toAdd} user dummy.");
    }

    public function finish(Request $request, Competition $competition)
    {
        $this->authorizeCompetition($request, $competition);
        $competition->update(['status' => 'finished']);
        return back()->with('success', "Lomba \"{$competition->name}\" ditandai selesai.");
    }

    public function startNoBracket(Request $request, Competition $competition)
    {
        $this->authorizeCompetition($request, $competition);
        
        if ($competition->has_bracket) {
            return back()->with('error', 'Lomba ini menggunakan bracket, tidak bisa dimulai langsung.');
        }

        $competition->update(['status' => 'ongoing']);
        
        return back()->with('success', "Lomba \"{$competition->name}\" telah dimulai!");
    }

    private function authorizeCompetition(Request $request, Competition $competition): void
    {
        $tournamentId = $request->attributes->get('host_tournament_id');
        if ($competition->tournament_id !== (int) $tournamentId) {
            abort(403, 'Akses ditolak.');
        }
    }
}

