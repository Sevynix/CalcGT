<?php

namespace App\Services\Bracket;

use App\Models\BracketNode;
use App\Models\CompetitionPhase;
use App\Models\CompetitionGroup;
use Illuminate\Support\Facades\DB;

class BracketManager
{
    /**
     * Set winner of a match and propagate results through the bracket.
     */
    public function setWinner(BracketNode $node, string $winner): void
    {
        if ($node->isLocked()) {
            throw new \RuntimeException('Match ini sudah terkunci. Hubungi admin untuk koreksi.');
        }

        if ($winner !== $node->participant_a && $winner !== $node->participant_b) {
            throw new \RuntimeException('Pemenang tidak valid.');
        }

        DB::transaction(function () use ($node, $winner) {
            $node->update(['winner' => $winner]);

            // Group stage match
            if ($node->group_id) {
                $this->updateGroupStandings($node->group);
                $this->checkGroupPhaseCompletion($node->phase);
                return;
            }

            $loser = $node->loser();

            $isUL = in_array($node->phase->bracket_type, ['upper_lower_4', 'upper_lower_6']);

            // ── Explicit routing (upper_lower_4 / upper_lower_6 / grand_final) ─
            if ($node->winner_routes_to_node_id || $node->loser_routes_to_node_id || $isUL) {
                if ($node->winner_routes_to_node_id || $node->loser_routes_to_node_id) {
                    $this->applyExplicitRouting($node, $winner, $loser);
                }

                // Grand Final "if necessary" logic
                if ($node->bracket_side === 'grand_final' && !$node->is_if_necessary) {
                    $this->handleGrandFinalResult($node, $winner);
                }

                if ($isUL) {
                    $this->checkUpperLowerCompletion($node->phase);
                } else {
                    $this->checkPartialSeCompletion($node->phase);
                    if (!$node->phase->survivor_count) {
                        $this->checkPhaseCompletion($node->phase);
                    }
                }
                return;
            }

            // ── Standard single-elimination routing ───────────────────────────
            $phase       = $node->phase;
            $totalRounds = BracketHelper::getTotalRounds($phase->participant_count ?: 2);

            $this->promoteWinnerSingleElim($node, $totalRounds);
            $this->lockIfNeeded($node, $totalRounds);

            // Check if this phase has a survivor_count (partial SE)
            $this->checkPartialSeCompletion($phase);

            // Check normal SE completion
            if (!$phase->survivor_count) {
                $this->checkPhaseCompletion($phase);
            }
        });
    }

    /**
     * Correct winner - allowed only if next node has no winner yet.
     */
    public function correctWinner(BracketNode $node, string $newWinner): void
    {
        if (!$node->canCorrect()) {
            throw new \RuntimeException('Pemenang tidak bisa dikoreksi. Match berikutnya sudah diputuskan.');
        }

        DB::transaction(function () use ($node, $newWinner) {
            $node->update(['winner' => $newWinner]);
            $newLoser = $node->loser();

            $isUL = in_array($node->phase->bracket_type, ['upper_lower_4', 'upper_lower_6']);

            if ($node->winner_routes_to_node_id || $isUL) {
                if ($node->winner_routes_to_node_id) {
                    $next = BracketNode::find($node->winner_routes_to_node_id);
                    if ($next && !$next->hasWinner()) {
                        $next->update([$node->winner_routes_to_slot => $newWinner]);
                    }
                }
                if ($node->loser_routes_to_node_id) {
                    $lower = BracketNode::find($node->loser_routes_to_node_id);
                    if ($lower && !$lower->hasWinner()) {
                        $lower->update([$node->loser_routes_to_slot => $newLoser]);
                    }
                }
                return;
            }

            // Standard SE correction
            $phase       = $node->phase;
            $nextRound   = $node->round + 1;
            $nextPosition = (int) floor($node->position / 2);
            $slot        = ($node->position % 2 === 0) ? 'participant_a' : 'participant_b';

            $nextNode = BracketNode::where('phase_id', $phase->id)
                ->where('bracket_side', 'upper')
                ->where('round', $nextRound)
                ->where('position', $nextPosition)
                ->first();

            if ($nextNode && !$nextNode->hasWinner()) {
                $nextNode->update([$slot => $newWinner]);
            }
        });

        // If this is the target round of a phase that feeds into a manually seeded phase,
        // and we corrected a winner, we must reset the next phase to seeding so they can pick again.
        if ($node->phase && $node->phase->isSingleElim() && $node->round == $node->phase->targetRound()) {
            $nextPhase = $node->phase->competition->phases()->where('phase_order', '>', $node->phase->phase_order)->first();
            if ($nextPhase && in_array($nextPhase->bracket_type, ['single_elim', 'single_elim_to_4', 'single_elim_to_6', 'upper_lower_6'])) {
                BracketNode::where('phase_id', $nextPhase->id)->update([
                    'participant_a' => null,
                    'participant_b' => null,
                    'winner'        => null,
                    'locked'        => false
                ]);
                $nextPhase->update(['status' => 'ongoing']);
            }
        }
    }

    // ── Routing helpers ───────────────────────────────────────────────────────

    /**
     * Apply explicit winner_routes_to and loser_routes_to routing.
     */
    private function applyExplicitRouting(BracketNode $node, string $winner, ?string $loser): void
    {
        // Route winner
        if ($node->winner_routes_to_node_id) {
            $nextNode = BracketNode::find($node->winner_routes_to_node_id);
            if ($nextNode) {
                $nextNode->update([$node->winner_routes_to_slot => $winner]);
                $this->handleAutoByeNode($nextNode);
            }
        }

        // Route loser
        if ($loser && $node->loser_routes_to_node_id) {
            $lowerNode = BracketNode::find($node->loser_routes_to_node_id);
            if ($lowerNode) {
                $lowerNode->update([$node->loser_routes_to_slot => $loser]);
                $this->handleAutoByeNode($lowerNode);
            }
        }
    }

    /**
     * After a Grand Final (non-if-necessary) is decided, handle "if necessary".
     * If the lower bracket player (slot B) wins, activate the if-necessary match.
     */
    private function handleGrandFinalResult(BracketNode $gf, string $winner): void
    {
        // No more "If Necessary" match per host request.
        // Whoever wins the first Grand Final wins the entire phase.
        $ifNec = BracketNode::where('phase_id', $gf->phase_id)
            ->where('bracket_side', 'grand_final')
            ->where('is_if_necessary', true)
            ->first();

        if ($ifNec) {
            $ifNec->update(['locked' => true]);
        }

        // Mark phase finished immediately
        $gf->phase->update(['status' => 'finished']);
    }

    /**
     * If a newly filled node (in upper_lower) has only one participant and is a bye,
     * auto-set the winner and propagate.
     */
    private function handleAutoByeNode(BracketNode $node): void
    {
        $node->refresh();
        if ($node->hasWinner()) return;
        if ($node->bracket_side === 'grand_final') return;
    }

    // ── Standard SE helpers ───────────────────────────────────────────────────

    public function promoteWinnerSingleElim(BracketNode $node, int $totalRounds): void
    {
        if ($node->round >= $totalRounds || is_null($node->winner)) {
            return;
        }

        $nextRound    = $node->round + 1;
        $nextPosition = (int) floor($node->position / 2);
        $slot         = ($node->position % 2 === 0) ? 'participant_a' : 'participant_b';

        $nextNode = BracketNode::where('phase_id', $node->phase_id)
            ->where('bracket_side', 'upper')
            ->where('round', $nextRound)
            ->where('position', $nextPosition)
            ->first();

        if ($nextNode) {
            $nextNode->update([$slot => $node->winner]);

            $nextNode->refresh();
            if ($nextNode->is_bye && ($nextNode->participant_a || $nextNode->participant_b)) {
                $winner = $nextNode->participant_a ?? $nextNode->participant_b;
                $nextNode->update(['winner' => $winner]);
                $this->promoteWinnerSingleElim($nextNode, $totalRounds);
            }
        }
    }

    public function resetExplicitRouting(BracketNode $node): bool
    {
        $handled = false;

        if ($node->winner_routes_to_node_id) {
            $nextNode = BracketNode::find($node->winner_routes_to_node_id);
            if ($nextNode) {
                if (!$nextNode->hasWinner()) {
                    $nextNode->update([
                        $node->winner_routes_to_slot => null,
                        'locked' => false,
                    ]);
                }
            }
            $handled = true;
        }

        if ($node->loser_routes_to_node_id) {
            $lowerNode = BracketNode::find($node->loser_routes_to_node_id);
            if ($lowerNode) {
                if (!$lowerNode->hasWinner()) {
                    $lowerNode->update([
                        $node->loser_routes_to_slot => null,
                        'locked' => false,
                    ]);
                }
            }
            $handled = true;
        }

        return $handled;
    }

    public function resetWinnerSingleElim(BracketNode $node, int $totalRounds): void
    {
        if (is_null($node->winner)) return;

        if (!$node->canCorrect()) {
            throw new \RuntimeException('Match tidak bisa dibatalkan karena match berikutnya sudah dimainkan.');
        }

        $node->update(['winner' => null, 'locked' => false]);

        // 1. Wipe next phase if it requires manual seeding (do this BEFORE resetExplicitRouting)
        if ($node->phase && $node->phase->isSingleElim() && $node->round == $node->phase->targetRound()) {
            $nextPhase = $node->phase->competition->phases()->where('phase_order', '>', $node->phase->phase_order)->first();
            if ($nextPhase && in_array($nextPhase->bracket_type, ['single_elim', 'single_elim_to_4', 'single_elim_to_6', 'upper_lower_6'])) {
                BracketNode::where('phase_id', $nextPhase->id)->update([
                    'participant_a' => null,
                    'participant_b' => null,
                    'winner'        => null,
                    'locked'        => false
                ]);
                $nextPhase->update(['status' => 'ongoing']);
            }
        }

        // 2. Check if it has an explicit route
        if ($this->resetExplicitRouting($node)) {
            if ($node->phase->status === 'finished') {
                $node->phase->update(['status' => 'ongoing']);
            }
            return;
        }
        
        // 2. Standard SE Routing
        $nextRound    = $node->round + 1;
        $nextPosition = (int) floor($node->position / 2);
        $slot         = ($node->position % 2 === 0) ? 'participant_a' : 'participant_b';

        $nextNode = BracketNode::where('phase_id', $node->phase_id)
            ->where('bracket_side', 'upper')
            ->where('round', $nextRound)
            ->where('position', $nextPosition)
            ->first();

        if ($nextNode) {
            if (!$nextNode->hasWinner()) {
                $nextNode->update([
                    $slot => null,
                    'locked' => false,
                ]);
            }
        }

        if ($node->phase->status === 'finished') {
            $node->phase->update(['status' => 'ongoing']);
        }

    }

    private function lockIfNeeded(BracketNode $node, int $totalRounds): void
    {
        if ($node->round >= $totalRounds) return;

        $nextRound    = $node->round + 1;
        $nextPosition = (int) floor($node->position / 2);

        $nextNode = BracketNode::where('phase_id', $node->phase_id)
            ->where('bracket_side', 'upper')
            ->where('round', $nextRound)
            ->where('position', $nextPosition)
            ->first();

        if ($nextNode && $nextNode->hasWinner()) {
            $node->update(['locked' => true]);
        }
    }

    // ── Completion checks ─────────────────────────────────────────────────────

    private function checkPhaseCompletion(CompetitionPhase $phase): void
    {
        $totalRounds = BracketHelper::getTotalRounds($phase->participant_count ?: 2);
        $finalNode   = BracketNode::where('phase_id', $phase->id)
            ->where('bracket_side', 'upper')
            ->where('round', $totalRounds)
            ->where('position', 0)
            ->first();

        if ($finalNode && $finalNode->hasWinner()) {
            $phase->update(['status' => 'finished']);
        }
    }

    /**
     * For SE phases with survivor_count: finish phase when the target round completes.
     */
    private function checkPartialSeCompletion(CompetitionPhase $phase): void
    {
        if (!$phase->survivor_count) return;

        $targetRound = $phase->targetRound();
        if (!$targetRound) return;

        // All nodes in the target round must have winners
        $nodes = BracketNode::where('phase_id', $phase->id)
            ->where('bracket_side', 'upper')
            ->where('round', $targetRound)
            ->get();

        if ($nodes->isNotEmpty() && $nodes->every(fn($n) => $n->hasWinner())) {
            $phase->update(['status' => 'finished']);
        }
    }

    private function checkUpperLowerCompletion(CompetitionPhase $phase): void
    {
        // Check if the if-necessary grand final is done
        $ifNec = BracketNode::where('phase_id', $phase->id)
            ->where('bracket_side', 'grand_final')
            ->where('is_if_necessary', true)
            ->first();

        if ($ifNec && $ifNec->hasWinner()) {
            $phase->update(['status' => 'finished']);
        }

        // Or if the main GF is done and if-necessary is locked (upper champ won straight)
        $gf = BracketNode::where('phase_id', $phase->id)
            ->where('bracket_side', 'grand_final')
            ->where('is_if_necessary', false)
            ->first();

        if ($gf && $gf->hasWinner() && $ifNec && $ifNec->locked) {
            $phase->update(['status' => 'finished']);
        }
    }

    private function checkGroupPhaseCompletion(CompetitionPhase $phase): void
    {
        $unfinished = BracketNode::where('phase_id', $phase->id)
            ->whereNull('winner')
            ->exists();

        if (!$unfinished) {
            $phase->update(['status' => 'finished']);
        }
    }

    // ── Group stage standings ─────────────────────────────────────────────────

    public function updateGroupStandings(CompetitionGroup $group): void
    {
        $matches = BracketNode::where('group_id', $group->id)->get();

        $standings = $group->standings;
        foreach ($standings as &$row) {
            $row['wins'] = 0;
            $row['losses'] = 0;
            $row['points'] = 0;
            $row['matches_played'] = 0;
        }
        unset($row);

        foreach ($matches as $match) {
            if (!$match->winner) continue;
            $loser = ($match->winner === $match->participant_a)
                ? $match->participant_b
                : $match->participant_a;

            foreach ($standings as &$row) {
                if ($row['participant'] === $match->winner) {
                    $row['wins']++;
                    $row['points']++;
                    $row['matches_played']++;
                }
                if ($row['participant'] === $loser) {
                    $row['losses']++;
                    $row['matches_played']++;
                }
            }
            unset($row);
        }

        $group->update(['standings' => $standings]);
    }

    // ── Bracket data for frontend ─────────────────────────────────────────────

    public function getBracketData(CompetitionPhase $phase): array
    {
        if ($phase->bracket_type === 'round_robin') {
            return $this->getGroupData($phase);
        }

        if ($phase->isUpperLower()) {
            return $this->getUpperLowerData($phase);
        }

        if ($phase->isFfa()) {
            return $this->getFfaData($phase);
        }

        return $this->getSingleElimData($phase);
    }

    private function getSingleElimData(CompetitionPhase $phase): array
    {
        $nodes       = $phase->upperNodes()->get();
        $totalRounds = BracketHelper::getTotalRounds($phase->participant_count ?: 2);

        $rounds = [];
        for ($r = 1; $r <= $totalRounds; $r++) {
            $rounds[$r] = $nodes->where('round', $r)->values()->map(fn($n) => $this->nodeToArray($n))->toArray();
        }

        return [
            'phase_id'        => $phase->id,
            'phase_order'     => $phase->phase_order,
            'status'          => $phase->status,
            'bracket_type'    => $phase->bracket_type,
            'total_rounds'    => $totalRounds,
            'survivor_count'  => $phase->survivor_count,
            'target_round'    => $phase->targetRound(),
            'rounds'          => $rounds,
        ];
    }

    private function getUpperLowerData(CompetitionPhase $phase): array
    {
        $upper  = $phase->upperNodes()->get()->groupBy('round');
        $lower  = $phase->lowerNodes()->get()->groupBy('round');
        $gf     = $phase->grandFinalNodes()->get();

        return [
            'phase_id'     => $phase->id,
            'phase_order'  => $phase->phase_order,
            'status'       => $phase->status,
            'bracket_type' => $phase->bracket_type,
            'upper'        => $upper->map(fn($r) => $r->map(fn($n) => $this->nodeToArray($n))->values())->toArray(),
            'lower'        => $lower->map(fn($r) => $r->map(fn($n) => $this->nodeToArray($n))->values())->toArray(),
            'grand_final'  => $gf->map(fn($n) => $this->nodeToArray($n))->values()->toArray(),
        ];
    }

    private function getFfaData(CompetitionPhase $phase): array
    {
        $results = $phase->ffaResults()->get();
        return [
            'phase_id'    => $phase->id,
            'phase_order' => $phase->phase_order,
            'status'      => $phase->status,
            'bracket_type' => 'ffa',
            'participants' => $results->map(fn($r) => [
                'id'    => $r->id,
                'name'  => $r->participant_name,
                'passed' => $r->passed,
            ])->toArray(),
        ];
    }

    private function getGroupData(CompetitionPhase $phase): array
    {
        $groups = $phase->groups()->with('nodes')->get()->map(function ($group) {
            return [
                'id'        => $group->id,
                'name'      => $group->name,
                'standings' => $group->getSortedStandings(),
                'matches'   => $group->nodes()->orderBy('round')->orderBy('position')->get()
                    ->map(fn($n) => $this->nodeToArray($n))->toArray(),
            ];
        })->toArray();

        return [
            'phase_id'            => $phase->id,
            'phase_order'         => $phase->phase_order,
            'status'              => $phase->status,
            'bracket_type'        => 'round_robin',
            'advancing_per_group' => $phase->advancing_per_group,
            'groups'              => $groups,
        ];
    }

    private function nodeToArray(BracketNode $node): array
    {
        return [
            'id'                       => $node->id,
            'bracket_side'             => $node->bracket_side,
            'round'                    => $node->round,
            'position'                 => $node->position,
            'participant_a'            => $node->participant_a,
            'participant_b'            => $node->participant_b,
            'winner'                   => $node->winner,
            'is_bye'                   => (bool) $node->is_bye,
            'locked'                   => (bool) $node->locked,
            'is_if_necessary'          => (bool) $node->is_if_necessary,
            'loser_routes_to_node_id'  => $node->loser_routes_to_node_id,
            'winner_routes_to_node_id' => $node->winner_routes_to_node_id,
        ];
    }

    // ── Seeding (manual seeding lock) ─────────────────────────────────────────

    public function lockAndStartPhase(CompetitionPhase $phase): void
    {
        if ($phase->status !== 'seeding') {
            throw new \RuntimeException('Phase tidak dalam mode seeding.');
        }

        DB::transaction(function () use ($phase) {
            $totalRounds = BracketHelper::getTotalRounds($phase->participant_count ?: 2);

            $round1Nodes = BracketNode::where('phase_id', $phase->id)
                ->where('bracket_side', 'upper')
                ->where('round', 1)
                ->get();

            foreach ($round1Nodes as $node) {
                $a   = $node->participant_a;
                $b   = $node->participant_b;
                $bye = is_null($a) || is_null($b);
                $win = $bye ? ($a ?? $b) : null;

                $node->update(['is_bye' => $bye, 'winner' => $win]);

                if ($bye && $win) {
                    $this->promoteWinnerSingleElim($node, $totalRounds);
                }
            }

            $phase->update(['status' => 'ongoing']);
        });
    }

    // ── Group advancers ───────────────────────────────────────────────────────

    public function getGroupAdvancers(CompetitionPhase $phase, int $advancingPerGroup): array
    {
        if ($phase->bracket_type !== 'round_robin' || !$phase->isFinished()) return [];

        $advancers = [];
        foreach ($phase->groups()->get() as $group) {
            $sorted = $group->getSortedStandings();
            foreach (array_slice($sorted, 0, $advancingPerGroup) as $row) {
                $advancers[] = $row['participant'];
            }
        }
        return $advancers;
    }

    /**
     * Get survivors from a finished partial SE phase (those who won the target round).
     */
    public function getSesurvivors(CompetitionPhase $phase): array
    {
        $targetRound = $phase->targetRound();
        if (!$targetRound) return [];

        return BracketNode::where('phase_id', $phase->id)
            ->where('bracket_side', 'upper')
            ->where('round', $targetRound)
            ->whereNotNull('winner')
            ->pluck('winner')
            ->toArray();
    }

    // ── Reset ─────────────────────────────────────────────────────────────────

    public function resetBracket(\App\Models\Competition $competition): void
    {
        DB::transaction(function () use ($competition) {
            $competition->phases()->delete();

            if (in_array($competition->status, ['ongoing', 'finished'])) {
                $competition->update(['status' => 'registration_closed']);
            }
        });
    }
}

