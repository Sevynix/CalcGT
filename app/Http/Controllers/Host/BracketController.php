<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\BracketNode;
use App\Models\Competition;
use App\Models\CompetitionPhase;
use App\Models\FfaResult;
use App\Models\ManualParticipant;
use App\Services\BracketService;
use App\Services\Bracket\FixedBracketOptionsResolver;
use App\Services\SpinWheelService;
use Illuminate\Http\Request;

class BracketController extends Controller
{
    public function __construct(
        private BracketService   $bracketService,
        private SpinWheelService $spinService
    ) {}



    // ── Start next phase ──────────────────────────────────────────────────────

    public function startNextPhase(Request $request, Competition $competition)
    {
        $this->authorizeCompetition($request, $competition);

        if (!$this->bracketService->canStartNextPhase($competition)) {
            return back()->with('error', 'Belum bisa memulai fase berikutnya. Pastikan fase sekarang sudah selesai.');
        }

        // Get survivors from the latest finished phase
        $latestPhase = $competition->latestPhase();
        $survivors   = $this->getSurvivors($latestPhase);

        if (count($survivors) < 2) {
            return back()->with('error', 'Tidak cukup survivor untuk fase berikutnya.');
        }

        $phase = $this->bracketService->startNextPhase($competition, $survivors);

        return redirect()->route('host.bracket', $competition)
            ->with('success', 'Fase berikutnya berhasil dimulai dengan ' . count($survivors) . ' peserta!');
    }

    // ── Bracket view ──────────────────────────────────────────────────────────

    public function show(Request $request, Competition $competition)
    {
        $this->authorizeCompetition($request, $competition);

        $phases       = $competition->phases()->get();
        $bracketDatas = [];

        foreach ($phases as $phase) {
            $bracketDatas[] = $this->bracketService->getBracketData($phase);
        }

        $canStartNext  = $this->bracketService->canStartNextPhase($competition);
        $latestPhase   = $competition->latestPhase();
        $selected      = $this->spinService->getSelected($competition);

        $unassigned = [];
        $hasWinners = $latestPhase ? \App\Models\BracketNode::where('phase_id', $latestPhase->id)->whereNotNull('winner')->exists() : false;
        $needsSeedingUi = $latestPhase && $latestPhase->status === 'seeding';

        $qualifiedParticipants = [];
        if ($latestPhase && $latestPhase->phase_order > 1) {
            $prevPhase = $competition->phases()->where('phase_order', $latestPhase->phase_order - 1)->first();
            if ($prevPhase) {
                if ($prevPhase->isSingleElim()) {
                    $targetRound = $prevPhase->targetRound();
                    if ($targetRound) {
                        $qualifiedParticipants = \App\Models\BracketNode::where('phase_id', $prevPhase->id)
                            ->where('bracket_side', 'upper')
                            ->where('round', $targetRound)
                            ->whereNotNull('winner')
                            ->pluck('winner')
                            ->unique()
                            ->values()
                            ->toArray();
                    } else {
                        $qualifiedParticipants = $this->getSurvivors($prevPhase);
                    }
                } else {
                    $qualifiedParticipants = $this->getSurvivors($prevPhase);
                }
            }
        }

        if ($needsSeedingUi) {
            $expectedParticipants = !empty($qualifiedParticipants) ? $qualifiedParticipants : [];
            if (empty($expectedParticipants)) {
                if ($latestPhase->phase_order === 1) {
                    if ($latestPhase->participant_source === 'spin') {
                        $expectedParticipants = $selected->pluck('name')->toArray();
                    } else {
                        $expectedParticipants = $competition->participants()->pluck('name')->toArray();
                    }
                }
            }
            
            if ($latestPhase->bracket_type === 'upper_lower_6') {
                $unassigned = array_values($expectedParticipants);
            } else {
                $assigned = \App\Models\BracketNode::where('phase_id', $latestPhase->id)
                    ->get()
                    ->flatMap(function($node) {
                        return [$node->participant_a, $node->participant_b];
                    })
                    ->filter()
                    ->unique()
                    ->toArray();
                    
                $unassigned = array_values(array_diff($expectedParticipants, $assigned));
            }
        }

        return view('host.bracket', compact(
            'competition',
            'phases',
            'bracketDatas',
            'canStartNext',
            'latestPhase',
            'selected',
            'unassigned',
            'qualifiedParticipants'
        ));
    }

    // ── FFA management ────────────────────────────────────────────────────────

    public function showFfa(Request $request, Competition $competition)
    {
        $this->authorizeCompetition($request, $competition);

        $phase = $competition->latestPhase();
        if (!$phase || !$phase->isFfa()) {
            return redirect()->route('host.bracket', $competition);
        }

        $results = $phase->ffaResults()->get();
        return view('host.ffa', compact('competition', 'phase', 'results'));
    }

    public function toggleFfaPassed(Request $request, Competition $competition, FfaResult $result)
    {
        $this->authorizeCompetition($request, $competition);
        $this->authorizePhase($result->competition_id, $competition->id);

        $result->update(['passed' => !$result->passed]);

        return response()->json([
            'success' => true,
            'passed'  => $result->passed,
        ]);
    }

    public function finalizeFfa(Request $request, Competition $competition)
    {
        $this->authorizeCompetition($request, $competition);

        $phase = $competition->latestPhase();
        if (!$phase || !$phase->isFfa()) {
            return back()->with('error', 'Tidak ada fase FFA yang aktif.');
        }

        $survivors = $this->bracketService->getFfaSurvivors($phase);
        $this->bracketService->finalizeFfa($phase);

        // Auto-start next phase if eligible
        if ($this->bracketService->canStartNextPhase($competition)) {
            if (count($survivors) >= 2) {
                $this->bracketService->startNextPhase($competition, $survivors);
            }
        }

        return redirect()->route('host.bracket', $competition)
            ->with('success', count($survivors) . ' peserta ditandai lolos dari FFA.');
    }



    // ── Set winner ────────────────────────────────────────────────────────────

    public function setWinner(Request $request, BracketNode $node)
    {
        $request->validate(['winner' => 'required|string']);

        $competition = $node->phase->competition;
        $this->authorizeCompetition($request, $competition);

        try {
            $this->bracketService->setWinner($node, $request->winner);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        // Auto-start next phase if eligible
        if ($this->bracketService->canStartNextPhase($competition)) {
            $latestFinished = $competition->phases()->where('status', 'finished')->orderByDesc('phase_order')->first();
            $survivors   = $this->getSurvivors($latestFinished);
            if (count($survivors) >= 2) {
                $this->bracketService->startNextPhase($competition, $survivors);
            }
        }

        return response()->json([
            'success'         => true,
            'message'         => "Pemenang: {$request->winner}",
            'can_start_next'  => false, // Always false now since we auto-started it
            'is_completed'    => $node->phase->fresh()->status === 'finished',
        ]);
    }

    public function correctWinner(Request $request, BracketNode $node)
    {
        $request->validate(['winner' => 'required|string']);

        $competition = $node->phase->competition;
        $this->authorizeCompetition($request, $competition);

        try {
            $this->bracketService->correctWinner($node, $request->winner);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json(['success' => true]);
    }

    public function resetMatch(Request $request, BracketNode $node)
    {
        $competition = $node->phase->competition;
        $this->authorizeCompetition($request, $competition);

        try {
            $this->bracketService->resetWinner($node);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json(['success' => true]);
    }

    // ── Seeding ───────────────────────────────────────────────────────────────

    public function assignParticipant(Request $request, BracketNode $node)
    {
        $request->validate([
            'slot'        => 'required|in:participant_a,participant_b',
            'participant' => 'nullable|string',
        ]);

        $competition = $node->phase->competition;
        $this->authorizeCompetition($request, $competition);

        if (!$node->phase->isSeeding()) {
            return response()->json(['error' => 'Bagan tidak dalam mode penyusunan manual.'], 422);
        }

        $node->update([$request->slot => $request->participant]);

        return response()->json(['success' => true]);
    }

    public function lockSeeding(Request $request, Competition $competition)
    {
        $this->authorizeCompetition($request, $competition);

        $phase = $competition->latestPhase();
        if (!$phase || !$phase->isSeeding()) {
            return back()->with('error', 'Fase penyusunan tidak valid.');
        }

        try {
            $this->bracketService->lockAndStartPhase($phase);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('host.bracket', $competition)
            ->with('success', 'Bagan berhasil dikunci dan pertandingan dimulai!');
    }

    public function quickSeedUpperLower6(Request $request, Competition $competition)
    {
        $this->authorizeCompetition($request, $competition);

        $phase = $competition->latestPhase();
        if (!$phase || !$phase->isSeeding() || $phase->bracket_type !== 'upper_lower_6') {
            return back()->with('error', 'Fase penyusunan tidak valid untuk aksi ini.');
        }

        $semifinalists = $request->input('semifinalists', []);
        if (!is_array($semifinalists) || count($semifinalists) !== 2) {
            return back()->with('error', 'Anda harus memilih tepat 2 peserta untuk Semifinal.');
        }

        // Get unassigned participants to validate and find the remaining 4
        $expectedParticipants = [];
        if ($phase->phase_order === 1) {
            $selected = $this->spinService->getSelected($competition);
            if ($phase->participant_source === 'spin') {
                $expectedParticipants = $selected->pluck('name')->toArray();
            } else {
                $expectedParticipants = $competition->participants()->pluck('name')->toArray();
            }
        } else {
            $prevPhase = $competition->phases()->where('phase_order', $phase->phase_order - 1)->first();
            if ($prevPhase) {
                $expectedParticipants = $this->getSurvivors($prevPhase);
            }
        }

        // Validate that selected semifinalists are in the expected participants
        foreach ($semifinalists as $s) {
            if (!in_array($s, $expectedParticipants)) {
                return back()->with('error', "Peserta {$s} tidak valid.");
            }
        }

        // Find remaining participants
        $remaining = array_values(array_diff($expectedParticipants, $semifinalists));
        if (count($remaining) !== 4) {
            return back()->with('error', 'Jumlah peserta yang tersisa tidak tepat 4 orang. (' . count($remaining) . ' tersisa)');
        }

        // Shuffle remaining
        shuffle($remaining);

        // Assign Semifinalists (Round 2 Upper)
        $r2Nodes = \App\Models\BracketNode::where('phase_id', $phase->id)
            ->where('bracket_side', 'upper')
            ->where('round', 2)
            ->orderBy('position')
            ->get();
            
        if ($r2Nodes->count() >= 2) {
            $r2Nodes[0]->update(['participant_a' => $semifinalists[0]]);
            $r2Nodes[1]->update(['participant_a' => $semifinalists[1]]);
        }

        // Assign Round 1 Upper
        $r1Nodes = \App\Models\BracketNode::where('phase_id', $phase->id)
            ->where('bracket_side', 'upper')
            ->where('round', 1)
            ->orderBy('position')
            ->get();
            
        if ($r1Nodes->count() >= 2) {
            $r1Nodes[0]->update([
                'participant_a' => $remaining[0],
                'participant_b' => $remaining[1]
            ]);
            $r1Nodes[1]->update([
                'participant_a' => $remaining[2],
                'participant_b' => $remaining[3]
            ]);
        }

        // Automatically lock and start phase
        try {
            $this->bracketService->lockAndStartPhase($phase);
        } catch (\Exception $e) {
            return back()->with('error', 'Peserta berhasil ditugaskan, tetapi gagal memulai bagan: ' . $e->getMessage());
        }

        return redirect()->route('host.bracket', $competition)
            ->with('success', 'Berhasil memilih semifinalis, mengacak bagan, dan memulai pertandingan!');
    }

    // ── Reset ─────────────────────────────────────────────────────────────────

    public function resetBracket(Request $request, Competition $competition)
    {
        $this->authorizeCompetition($request, $competition);
        $this->bracketService->resetBracket($competition);

        return redirect()->route('host.bracket.setup', $competition)
            ->with('success', 'Bracket direset. Silakan buat ulang.');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function getSurvivors(CompetitionPhase $phase): array
    {
        if ($phase->isGroupStage()) {
            return $this->bracketService->getGroupAdvancers($phase, $phase->advancing_per_group);
        }
        if ($phase->isSingleElim() && $phase->survivor_count) {
            return $this->bracketService->getSeSurvivors($phase);
        }
        if ($phase->isFfa()) {
            return $this->bracketService->getFfaSurvivors($phase);
        }
        return [];
    }

    private function parseManualNames(string $raw): array
    {
        $lines = preg_split('/[\r\n]+/', $raw);
        $names = [];
        foreach ($lines as $line) {
            $name = trim($line);
            if (!empty($name)) {
                $names[] = $name;
            }
        }
        return array_unique($names);
    }

    private function authorizeCompetition(Request $request, Competition $competition): void
    {
        $tournamentId = $request->attributes->get('host_tournament_id');
        if ($competition->tournament_id !== (int) $tournamentId) {
            abort(403, 'Akses ditolak.');
        }
    }

    private function authorizePhase(int $competitionId, int $expectedId): void
    {
        if ($competitionId !== $expectedId) {
            abort(403, 'Akses ditolak.');
        }
    }
}
