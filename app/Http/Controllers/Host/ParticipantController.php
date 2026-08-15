<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\ManualParticipant;
use App\Services\BracketService;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function __construct(private BracketService $bracketService) {}

    public function manage(Request $request, Competition $competition)
    {
        if ($competition->has_registration) {
            if ($competition->status !== 'registration_closed') {
                return back()->with('error', 'Peserta manual hanya dapat dikelola saat pendaftaran ditutup.');
            }
        } else {
            if ($competition->status !== 'revealed') {
                return back()->with('error', 'Peserta manual hanya dapat dikelola saat status revealed.');
            }
        }

        $participants = $competition->manualParticipants()->orderBy('id')->get();
        return view('host.manual-participants', compact('competition', 'participants'));
    }

    public function add(Request $request, Competition $competition)
    {
        $request->validate([
            'names' => 'required|array',
            'names.*' => 'string|max:255'
        ]);

        $maxSlots = $competition->effectiveSlotCount();
        $currentCount = $competition->manualParticipants()->count();

        // Filter and count unique valid names to be added
        $namesToAdd = [];
        foreach ($request->names as $name) {
            $name = trim($name);
            if (!empty($name)) {
                $namesToAdd[] = $name;
            }
        }

        if ($currentCount + count($namesToAdd) > $maxSlots) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal menambahkan peserta. Maksimal peserta untuk kompetisi ini adalah ' . $maxSlots . ' (saat ini sudah ada ' . $currentCount . ' peserta).'
            ]);
        }

        foreach ($namesToAdd as $name) {
            ManualParticipant::firstOrCreate([
                'competition_id' => $competition->id,
                'name_normalized' => ManualParticipant::normalizeName($name),
            ], [
                'name' => $name,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function remove(Request $request, Competition $competition, ManualParticipant $participant)
    {
        if ($participant->competition_id === $competition->id) {
            $participant->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['error' => 'Invalid participant'], 422);
    }

    public function generateBracket(Request $request, Competition $competition)
    {
        $participants = $competition->manualParticipants()->pluck('name')->toArray();

        if (count($participants) < 2) {
            return back()->with('error', 'Minimal 2 peserta untuk membuat bagan.');
        }

        // Start Phase 1 (Generators will auto-seed and set status to ongoing)
        $phase = $this->bracketService->startFirstPhase($competition, $participants, 'auto');
        
        // Mark competition as ongoing
        $competition->update(['status' => 'ongoing']);

        return redirect()->route('host.bracket', $competition)
            ->with('success', 'Bagan berhasil dibuat dan dikunci! Silakan kelola pertandingan.');
    }
}
