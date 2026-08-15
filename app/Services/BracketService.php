<?php

namespace App\Services;

use App\Models\BracketNode;
use App\Models\Competition;
use App\Models\CompetitionPhase;
use App\Models\FfaResult;
use App\Services\Bracket\FixedBracketOptionsResolver;
use App\Services\Bracket\GroupStageGenerator;
use App\Services\Bracket\UpperLowerGenerator;
use App\Services\Bracket\SingleEliminationGenerator;
use App\Services\Bracket\BracketManager;
use App\Services\Bracket\BracketHelper;

class BracketService
{
    public function __construct(
        protected GroupStageGenerator      $groupStageGenerator,
        protected SingleEliminationGenerator $singleElimGenerator,
        protected UpperLowerGenerator      $upperLowerGenerator,
        protected BracketManager           $manager,
    ) {}

    public function resetWinner(BracketNode $node): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($node) {
            $phase = $node->phase;
            if ($phase->status === 'seeding') {
                throw new \RuntimeException('Phase masih dalam mode seeding.');
            }

            // If it's a group stage, and phase was finished, we must clear Phase 2's starting slots
            if ($phase->isGroupStage() && $phase->status === 'finished') {
                $hasPlayedMatches = $phase->competition->phases()
                    ->where('phase_order', '>', $phase->phase_order)
                    ->whereHas('nodes', function ($query) {
                        $query->whereNotNull('winner');
                    })
                    ->exists();

                if ($hasPlayedMatches) {
                    throw new \RuntimeException('Tidak bisa membatalkan pertandingan karena fase berikutnya sudah mulai dimainkan.');
                }
                
                // Clear Phase 2 starting nodes
                $nextPhase = $phase->competition->phases()->where('phase_order', $phase->phase_order + 1)->first();
                if ($nextPhase) {
                    BracketNode::where('phase_id', $nextPhase->id)
                        ->where('bracket_side', 'upper')
                        ->where('round', 1)
                        ->update(['participant_a' => null, 'participant_b' => null, 'locked' => false]);
                }
            }

            if ($phase->status === 'finished') {
                $phase->update(['status' => 'ongoing']);
            }

            if ($phase->isSingleElim()) {
                $totalRounds = BracketHelper::getTotalRounds($phase->participant_count ?: 2);
                $this->manager->resetWinnerSingleElim($node, $totalRounds);
            } else {
                // Generic reset for group stage / upper_lower
                if (!$node->canCorrect()) {
                    throw new \RuntimeException('Match tidak bisa dibatalkan karena match berikutnya sudah dimainkan.');
                }
                
                $node->update(['winner' => null]);
                
                if (in_array($phase->bracket_type, ['upper_lower_4', 'upper_lower_6'])) {
                    $this->manager->resetExplicitRouting($node);
                    
                    if ($node->bracket_side === 'grand_final' && !$node->is_if_necessary) {
                        $ifNec = \App\Models\BracketNode::where('phase_id', $node->phase_id)
                            ->where('bracket_side', 'grand_final')
                            ->where('is_if_necessary', true)
                            ->first();
                        if ($ifNec) {
                            if ($ifNec->hasWinner()) {
                                throw new \RuntimeException("Tidak bisa membatalkan karena match if-necessary sudah ada pemenangnya.");
                            }
                            $ifNec->update([
                                'participant_a' => null,
                                'participant_b' => null,
                                'locked' => false,
                            ]);
                        }
                    }
                }

                // If group stage, update standings
                if ($node->group_id) {
                    $this->manager->updateGroupStandings($node->group);
                }
            }
        });
    }

    // ── Format options ────────────────────────────────────────────────────────

    public function getFormatOptions(int $slotCount): array
    {
        return FixedBracketOptionsResolver::getOptions($slotCount);
    }

    // ── Bracket generation ────────────────────────────────────────────────────

    public function startFirstPhase(Competition $competition, array $participants, string $participantSource): CompetitionPhase
    {
        $format  = $competition->bracket_format;

        // If using the old detailed format codes:
        $options = FixedBracketOptionsResolver::getOption($competition->slot_count, $format);

        if ($options) {
            $firstPhaseType = $options['phases'][0];
            $firstPhase = match ($firstPhaseType) {
                'single_elim'      => $this->startSingleElim($competition, $participants, $participantSource, null),
                'single_elim_to_4' => $this->startSingleElim($competition, $participants, $participantSource, 4),
                'single_elim_to_6' => $this->startSingleElim($competition, $participants, $participantSource, 6),
                'group_stage'      => $this->startGroupStage($competition, $participants, $participantSource, $options),
                'round_robin'      => $this->startGroupStage($competition, $participants, $participantSource, ['group_count' => 1, 'top_n' => 1]),
                'ffa'              => $this->startFfa($competition, $participants, $participantSource),
                default            => throw new \InvalidArgumentException("Unknown phase type: {$firstPhaseType}"),
            };

            // Pre-generate subsequent phases as dummies
            $previousPhase = $firstPhase;
            for ($i = 1; $i < count($options['phases']); $i++) {
                $dummyType = $options['phases'][$i];
                $expectedCount = $this->getExpectedSurvivorCount($options, $i);
                $dummyPhase = $this->createDummyPhase($competition, $dummyType, $i + 1, $expectedCount);
                
                $this->wirePhases($previousPhase, $dummyPhase);
                $previousPhase = $dummyPhase;
            }

            return $firstPhase;
        }

        throw new \InvalidArgumentException("Format tidak valid: {$format}");
    }

    /**
     * Start the next phase based on the competition's bracket_format and current phase.
     */
    public function startNextPhase(Competition $competition, array $survivors): CompetitionPhase
    {
        $format      = $competition->bracket_format;
        $options     = FixedBracketOptionsResolver::getOption($competition->slot_count, $format);
        
        // Find the order of the latest finished phase
        $latestFinished = $competition->phases()->where('status', 'finished')->orderByDesc('phase_order')->first();
        $currentOrder = $latestFinished ? $latestFinished->phase_order : 0;
        
        $nextType    = $options['phases'][$currentOrder] ?? null;

        if (!$nextType) {
            throw new \RuntimeException('Tidak ada fase berikutnya.');
        }

        $nextPhase = $competition->phases()->where('phase_order', $currentOrder + 1)->first();
        if (!$nextPhase) {
            throw new \RuntimeException('Fase berikutnya tidak ditemukan (dummy hilang).');
        }

        // Auto-populate the next phase based on bracket type
        if (in_array($nextPhase->bracket_type, ['upper_lower_4', 'upper_lower_6'])) {
            $p = array_pad($survivors, $nextPhase->participant_count, null);
            $upperNodes = BracketNode::where('phase_id', $nextPhase->id)
                ->where('bracket_side', 'upper')
                ->orderBy('round')
                ->orderBy('position')
                ->get();

            if ($nextPhase->bracket_type === 'upper_lower_6') {
                [$p1, $p2, $p3, $p4, $p5, $p6] = $p;
                $ur1p0 = $upperNodes->where('round', 1)->where('position', 0)->first();
                $ur1p1 = $upperNodes->where('round', 1)->where('position', 1)->first();
                $uSemi0 = $upperNodes->where('round', 2)->where('position', 0)->first();
                $uSemi1 = $upperNodes->where('round', 2)->where('position', 1)->first();
                
                if ($ur1p0) $ur1p0->update(['participant_a' => $p3, 'participant_b' => $p6, 'locked' => false]);
                if ($ur1p1) $ur1p1->update(['participant_a' => $p4, 'participant_b' => $p5, 'locked' => false]);
                if ($uSemi0) $uSemi0->update(['participant_a' => $p1, 'locked' => false]);
                if ($uSemi1) $uSemi1->update(['participant_a' => $p2, 'locked' => false]);
            } else {
                // upper_lower_4
                [$p1, $p2, $p3, $p4] = $p;
                $ur1p0 = $upperNodes->where('round', 1)->where('position', 0)->first();
                $ur1p1 = $upperNodes->where('round', 1)->where('position', 1)->first();
                
                if ($ur1p0) $ur1p0->update(['participant_a' => $p1, 'participant_b' => $p4, 'locked' => false]);
                if ($ur1p1) $ur1p1->update(['participant_a' => $p2, 'participant_b' => $p3, 'locked' => false]);
            }
        } else {
            // For single_elim, group stages, etc.
            $startingNodes = BracketNode::where('phase_id', $nextPhase->id)
                ->where('bracket_side', 'upper')
                ->where('round', 1)
                ->orderBy('position')
                ->get();

            foreach ($startingNodes as $node) {
                if (empty($survivors)) break;
                
                if ($node->is_bye) {
                    $a = array_shift($survivors);
                    $node->update([
                        'participant_a' => $a,
                        'winner' => $a,
                        'locked' => false,
                    ]);
                    app(\App\Services\Bracket\BracketManager::class)->promoteWinnerSingleElim($node, \App\Services\Bracket\BracketHelper::getTotalRounds($nextPhase->participant_count));
                } else {
                    $a = array_shift($survivors);
                    $b = array_shift($survivors);
                    $node->update([
                        'participant_a' => $a,
                        'participant_b' => $b,
                        'locked' => false,
                    ]);
                }
            }
        }

        $nextPhase->update(['status' => 'ongoing']);
        return $nextPhase;
    }

    // ── Individual phase starters ─────────────────────────────────────────────

    private function startSingleElim(
        Competition $competition,
        array       $participants,
        string      $source,
        ?int        $survivorCount,
        int         $order = 1
    ): CompetitionPhase {
        $type = 'single_elim';
        if ($survivorCount === 4) $type = 'single_elim_to_4';
        if ($survivorCount === 6) $type = 'single_elim_to_6';

        $phase = CompetitionPhase::create([
            'competition_id'    => $competition->id,
            'phase_order'       => $order,
            'bracket_type'      => $type,
            'participant_source' => $source,
            'participant_count'  => count($participants),
            'survivor_count'    => $survivorCount,
            'status'            => 'seeding',
        ]);

        $this->singleElimGenerator->generate($phase, $participants);
        return $phase;
    }

    private function startGroupStage(
        Competition $competition,
        array       $participants,
        string      $source,
        array       $options,
        int         $order = 1
    ): CompetitionPhase {
        $phase = CompetitionPhase::create([
            'competition_id'      => $competition->id,
            'phase_order'         => $order,
            'bracket_type'        => 'round_robin',
            'participant_source'  => $source,
            'participant_count'   => count($participants),
            'advancing_per_group' => $options['top_n'],
            'status'              => 'seeding',
        ]);

        $this->groupStageGenerator->generate($phase, $participants, $options['group_count']);
        return $phase;
    }

    private function startUpperLower(
        Competition $competition,
        array       $participants,
        string      $type,           // upper_lower_4 | upper_lower_6
        int         $order
    ): CompetitionPhase {
        $phase = CompetitionPhase::create([
            'competition_id'    => $competition->id,
            'phase_order'       => $order,
            'bracket_type'      => $type,
            'participant_source' => 'survivors',
            'participant_count'  => count($participants),
            'status'            => $type === 'upper_lower_6' ? 'seeding' : 'pending',
        ]);

        $this->upperLowerGenerator->generate($phase, $participants);
        return $phase;
    }

    private function startFfa(
        Competition $competition,
        array       $participants,
        string      $source,
        int         $order = 1
    ): CompetitionPhase {
        $phase = CompetitionPhase::create([
            'competition_id'    => $competition->id,
            'phase_order'       => $order,
            'bracket_type'      => 'ffa',
            'participant_source' => $source,
            'participant_count'  => count($participants),
            'status'            => 'ongoing',
        ]);

        // Create FFA result rows for each participant
        foreach ($participants as $name) {
            FfaResult::create([
                'competition_id'   => $competition->id,
                'phase_id'         => $phase->id,
                'participant_name' => $name,
                'passed'           => false,
            ]);
        }

        return $phase;
    }

    // ── Winner / correction ───────────────────────────────────────────────────

    private function wirePhases(CompetitionPhase $phase1, CompetitionPhase $phase2): void
    {
        if ($phase1->isSingleElim()) {
            $targetRound = $phase1->targetRound();
            if (!$targetRound) return;

            $terminalNodes = BracketNode::where('phase_id', $phase1->id)
                ->where('bracket_side', 'upper')
                ->where('round', $targetRound)
                ->orderBy('position')
                ->get();

            $startingNodes = BracketNode::where('phase_id', $phase2->id)
                ->where('bracket_side', 'upper')
                ->where('round', 1)
                ->orderBy('position')
                ->get();

            foreach ($terminalNodes as $index => $node) {
                $nextPosition = (int) floor($index / 2);
                $slot = ($index % 2 === 0) ? 'participant_a' : 'participant_b';
                
                $targetNode = $startingNodes->where('position', $nextPosition)->first();
                if ($targetNode) {
                    $node->update([
                        'winner_routes_to_node_id' => $targetNode->id,
                        'winner_routes_to_slot' => $slot
                    ]);
                }
            }
        }
    }

    private function getExpectedSurvivorCount(array $options, int $currentPhaseIndex): int
    {
        $prevType = $options['phases'][$currentPhaseIndex - 1];
        if (str_starts_with($prevType, 'group_stage') || str_starts_with($prevType, 'round_robin')) {
            return ($options['group_count'] ?? 0) * ($options['top_n'] ?? 0);
        }
        if ($prevType === 'single_elim_to_4') return 4;
        if ($prevType === 'single_elim_to_6') return 6;
        if ($prevType === 'single_elim') return 1;
        
        return 0;
    }

    private function createDummyPhase(Competition $competition, string $type, int $order, int $expectedCount): CompetitionPhase
    {
        $dummyParticipants = array_fill(0, $expectedCount, null);
        
        $phase = CompetitionPhase::create([
            'competition_id'    => $competition->id,
            'phase_order'       => $order,
            'bracket_type'      => $type,
            'participant_source' => 'survivors',
            'participant_count'  => $expectedCount,
            'status'            => 'pending',
        ]);

        if ($type === 'single_elim' || $type === 'single_elim_to_4' || $type === 'single_elim_to_6') {
            $this->singleElimGenerator->generate($phase, $dummyParticipants);
        } elseif ($type === 'upper_lower_4' || $type === 'upper_lower_6') {
            $this->upperLowerGenerator->generate($phase, $dummyParticipants);
        }
        
        return $phase;
    }

    public function setWinner(BracketNode $node, string $winner): void
    {
        $this->manager->setWinner($node, $winner);
    }

    public function correctWinner(BracketNode $node, string $newWinner): void
    {
        $this->manager->correctWinner($node, $newWinner);
    }

    public function getBracketData(CompetitionPhase $phase): array
    {
        return $this->manager->getBracketData($phase);
    }

    public function lockAndStartPhase(CompetitionPhase $phase): void
    {
        $this->manager->lockAndStartPhase($phase);
    }

    public function getGroupAdvancers(CompetitionPhase $phase, int $apg): array
    {
        return $this->manager->getGroupAdvancers($phase, $apg);
    }

    public function getSeSurvivors(CompetitionPhase $phase): array
    {
        return $this->manager->getSesurvivors($phase);
    }

    public function resetBracket(Competition $competition): void
    {
        $this->manager->resetBracket($competition);
    }

    // ── FFA helpers ───────────────────────────────────────────────────────────

    public function setFfaPassed(FfaResult $result, bool $passed): void
    {
        $result->update(['passed' => $passed]);
    }

    public function finalizeFfa(CompetitionPhase $phase): void
    {
        $phase->update(['status' => 'finished']);
    }

    public function getFfaSurvivors(CompetitionPhase $phase): array
    {
        return $phase->ffaResults()
            ->where('passed', true)
            ->pluck('participant_name')
            ->toArray();
    }

    // ── Next phase eligibility ────────────────────────────────────────────────

    public function canStartNextPhase(Competition $competition): bool
    {
        $format  = $competition->bracket_format;
        $options = FixedBracketOptionsResolver::getOption($competition->slot_count, $format);
        if (!$options) return false;

        $latestFinished = $competition->phases()->where('status', 'finished')->orderByDesc('phase_order')->first();
        if (!$latestFinished) return false;
        
        $currentOrder = $latestFinished->phase_order;
        $nextType     = $options['phases'][$currentOrder] ?? null;
        
        if ($nextType === null) return false;

        $nextPhase = $competition->phases()->where('phase_order', $currentOrder + 1)->first();
        return $nextPhase && $nextPhase->status === 'pending';
    }

    public function generateGroupStage(CompetitionPhase $phase, array $participants, int $groupCount): void
    {
        $this->groupStageGenerator->generate($phase, $participants, $groupCount);
    }

    public function generateBracket(CompetitionPhase $phase, array $participants, bool $isManual = false): void
    {
        $this->singleElimGenerator->generate($phase, $participants, $isManual);
    }
}
