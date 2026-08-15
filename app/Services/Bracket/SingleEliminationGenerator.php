<?php

namespace App\Services\Bracket;

use App\Models\BracketNode;
use App\Models\CompetitionPhase;

class SingleEliminationGenerator
{
    /**
     * Generate bracket nodes for a phase given a list of participants.
     * Uses single elimination with fair bye distribution for non-power-of-2 counts.
     */
    public function generate(CompetitionPhase $phase, array $participants, bool $isManual = false): void
    {
        $slotCount   = count($participants) ?: $phase->competition->effectiveSlotCount() ?: 2;
        
        $targetCount = null;
        if ($phase->bracket_type === 'single_elim_to_6') $targetCount = 6;
        if ($phase->bracket_type === 'single_elim_to_4') $targetCount = 4;
        
        $straightReduction = false;
        $bracketSize = BracketHelper::nextPowerOfTwo($slotCount);
        $totalRounds = (int) log($bracketSize, 2);
        
        if ($targetCount && $slotCount > $targetCount && ($slotCount % $targetCount === 0)) {
            $factor = $slotCount / $targetCount;
            if (($factor & ($factor - 1)) === 0) { // Factor is a power of 2
                $straightReduction = true;
                $bracketSize = $slotCount;
                $totalRounds = (int) log($factor, 2);
            }
        }
        
        $matchCount  = (int) ($bracketSize / 2);

        // Update participant count
        $phase->update([
            'participant_count' => $slotCount,
            'status' => (empty($participants) || $isManual) ? 'seeding' : 'ongoing',
        ]);

        // 1. Create empty nodes for ALL rounds first (so promoteWinnerSingleElim can find them)
        for ($round = 1; $round <= $totalRounds; $round++) {
            $matchesInRound = (int) ($bracketSize / pow(2, $round));

            for ($pos = 0; $pos < $matchesInRound; $pos++) {
                BracketNode::create([
                    'phase_id'     => $phase->id,
                    'bracket_side' => 'upper',
                    'round'        => $round,
                    'position'     => $pos,
                    'is_bye'       => false,
                    'locked'       => false,
                ]);
            }
        }

        if (empty($participants) || $isManual) {
            // For empty or manual seeding, we leave all nodes empty
            return;
        }

        $count    = count($participants);
        $byeCount = $straightReduction ? 0 : ($bracketSize - $count);

        // Shuffle participants for random seeding
        shuffle($participants);

        // Fair bye distribution: spread byes evenly across positions
        $byePositions = $straightReduction ? [] : $this->calculateFairByePositions($matchCount, $byeCount);

        // Build slots for each match
        $slots = [];
        $pIndex = 0;
        for ($i = 0; $i < $matchCount; $i++) {
            if (in_array($i, $byePositions)) {
                $slots[] = [$participants[$pIndex++], null]; // bye: 1 participant vs null
            } else {
                $slots[] = [$participants[$pIndex++], $participants[$pIndex++]];
            }
        }

        // 2. Populate round 1 matches and handle byes
        for ($pos = 0; $pos < $matchCount; $pos++) {
            [$a, $b] = $slots[$pos];
            $bye = is_null($a) || is_null($b);

            $node = BracketNode::where('phase_id', $phase->id)
                ->where('bracket_side', 'upper')
                ->where('round', 1)
                ->where('position', $pos)
                ->first();

            $node->update([
                'participant_a' => $a,
                'participant_b' => $b,
                'is_bye'        => $bye,
                'winner'        => $bye ? ($a ?? $b) : null,
            ]);

            // If bye, auto-promote winner to round 2
            if ($bye) {
                app(BracketManager::class)->promoteWinnerSingleElim($node, $totalRounds);
            }
        }
    }

    /**
     * Calculate fair bye positions spread evenly across the bracket.
     *
     * Instead of [0, 1, 2] for 3 byes in 8 matches,
     * returns [0, 4, 2] - spread across top, bottom, and middle.
     *
     * Uses a "mirror seeding" approach:
     * Position priority: 0, N/2, N/4, 3N/4, N/8, 5N/8, 3N/8, 7N/8, ...
     * This ensures byes are maximally spread apart.
     */
    private function calculateFairByePositions(int $matchCount, int $byeCount): array
    {
        if ($byeCount === 0) return [];
        if ($byeCount >= $matchCount) {
            return range(0, $matchCount - 1);
        }

        // Generate position priority order using bit-reversal permutation
        $priorityOrder = $this->generateByePriorityOrder($matchCount);

        return array_slice($priorityOrder, 0, $byeCount);
    }

    /**
     * Generate bye position priority using a spread-out ordering.
     * This uses a simple interval-based approach to ensure maximum spread.
     */
    private function generateByePriorityOrder(int $matchCount): array
    {
        // Start with first and last, then fill in gaps
        $order = [];
        $queue = [[0, $matchCount - 1]];

        // Add endpoints first
        $order[] = 0;
        if ($matchCount > 1) {
            $order[] = $matchCount - 1;
        }

        // Then fill midpoints
        while (count($order) < $matchCount) {
            $nextQueue = [];
            foreach ($queue as [$lo, $hi]) {
                if ($hi - $lo < 2) continue;
                $mid = (int) floor(($lo + $hi) / 2);
                if (!in_array($mid, $order)) {
                    $order[] = $mid;
                }
                $nextQueue[] = [$lo, $mid];
                $nextQueue[] = [$mid, $hi];
            }
            if (empty($nextQueue)) break;
            $queue = $nextQueue;
        }

        return array_slice($order, 0, $matchCount);
    }
}

