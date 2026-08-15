<?php

namespace App\Services\Bracket;

use App\Models\BracketNode;
use App\Models\CompetitionPhase;
use Illuminate\Support\Facades\DB;

/**
 * Generates upper + lower bracket nodes for the "double-elimination finals" formats.
 *
 * Supported templates:
 *
 *   upper_lower_4  (4 participants):
 *     Upper: R1(2 matches) → Final(1 match)
 *     Lower: LR1(1 match) ← losers of R1
 *            LR-Final(1 match) ← winner(LR1) vs loser(Upper Final)
 *     Grand Final + If Necessary
 *
 *   upper_lower_6  (6 participants, 2 seeded byes):
 *     Upper: R1(2 matches among seeds 3-6) → Semi(2 matches, seeds 1-2 enter) → Final(1 match)
 *     Lower: LR1(2 matches, mixing R1 losers & semi losers cross-paired)
 *            LR-Semi(1 match) ← winners of LR1
 *            LR-Final(1 match) ← winner(LR-Semi) vs loser(Upper Final)
 *     Grand Final + If Necessary
 */
class UpperLowerGenerator
{
    /**
     * Generate all nodes for an upper_lower phase.
     *
     * @param CompetitionPhase $phase  Must have bracket_type = 'upper_lower_4' or 'upper_lower_6'
     * @param array            $participants  Ordered by seed (best first)
     */
    public function generate(CompetitionPhase $phase, array $participants): void
    {
        $type = $phase->bracket_type;

        DB::transaction(function () use ($phase, $participants, $type) {
            if ($type === 'upper_lower_4') {
                $this->generateLower4($phase, $participants);
                $status = $phase->phase_order > 1 ? 'pending' : 'ongoing';
                $phase->update(['status' => $status, 'participant_count' => count($participants)]);
            } elseif ($type === 'upper_lower_6') {
                $this->generateLower6($phase, $participants);
                $status = $phase->phase_order > 1 ? 'pending' : 'ongoing';
                $phase->update(['status' => $status, 'participant_count' => count($participants)]);
            } else {
                throw new \InvalidArgumentException("Unknown upper_lower type: {$type}");
            }
        });
    }

    // ── Template: lower_4 ─────────────────────────────────────────────────────

    /**
     * 4 players. Match creation order (determines display numbering):
     *
     *  Match 1: upper R1 pos0  – p1(seed1) vs p4(seed4)
     *  Match 2: upper R1 pos1  – p2(seed2) vs p3(seed3)   [wait: standard is 1v4, 2v3]
     *  Actually: seed 1 vs seed 4, seed 2 vs seed 3 in R1 for 4-player:
     *  But screenshot shows: 1,4 and 3,2 → so seed1 vs seed4 (pos0), seed3 vs seed2 (pos1)
     *
     *  Match 3: lower R1 pos0  – Loser(1) vs Loser(2)
     *  Match 4: upper Final    – Winner(1) vs Winner(2)
     *  Match 5: lower Final    – Winner(3) vs Loser(4)
     *  Match 6: Grand Final    – Winner(4) vs Winner(5)
     *  Match 7: If Necessary   – (triggered when lower wins GF)
     */
    private function generateLower4(CompetitionPhase $phase, array $participants): void
    {
        // Seeds: ensure we have at least 4
        $p = array_pad($participants, 4, null);
        // Standard bracket seeding for 4: 1v4, 2v3
        [$p1, $p2, $p3, $p4] = [$p[0], $p[1], $p[2], $p[3]];

        // ── Upper bracket ────────────────────────────────────────────────────
        $ur1p0 = $this->createNode($phase, 'upper', 1, 0, $p1, $p4);  // Match 1
        $ur1p1 = $this->createNode($phase, 'upper', 1, 1, $p2, $p3);  // Match 2

        // ── Lower R1 (needs IDs first, then wire routing) ────────────────────
        $lr1p0 = $this->createNode($phase, 'lower', 1, 0);             // Match 3

        // ── Upper Final ──────────────────────────────────────────────────────
        $uFinal = $this->createNode($phase, 'upper', 2, 0);            // Match 4

        // ── Lower Final ──────────────────────────────────────────────────────
        $lFinal = $this->createNode($phase, 'lower', 2, 0);            // Match 5

        // ── Grand Final ──────────────────────────────────────────────────────
        $gf         = $this->createNode($phase, 'grand_final', 1, 0); // Match 6
        $gfIfNeeded = $this->createNode($phase, 'grand_final', 2, 0,   // Match 7
            null, null, true);

        // ── Wire upper R1 → upper Final (winner) ─────────────────────────────
        $ur1p0->update([
            'loser_routes_to_node_id'  => $lr1p0->id,
            'loser_routes_to_slot'     => 'participant_a',
            'winner_routes_to_node_id' => $uFinal->id,
            'winner_routes_to_slot'    => 'participant_a',
        ]);
        $ur1p1->update([
            'loser_routes_to_node_id'  => $lr1p0->id,
            'loser_routes_to_slot'     => 'participant_b',
            'winner_routes_to_node_id' => $uFinal->id,
            'winner_routes_to_slot'    => 'participant_b',
        ]);

        // ── Wire lower R1 → lower Final (winner) ─────────────────────────────
        $lr1p0->update([
            'winner_routes_to_node_id' => $lFinal->id,
            'winner_routes_to_slot'    => 'participant_a',
        ]);

        // ── Wire upper Final → Grand Final (winner) + lower Final (loser) ────
        $uFinal->update([
            'winner_routes_to_node_id' => $gf->id,
            'winner_routes_to_slot'    => 'participant_a',
            'loser_routes_to_node_id'  => $lFinal->id,
            'loser_routes_to_slot'     => 'participant_b',
        ]);

        // ── Wire lower Final → Grand Final (winner) ───────────────────────────
        $lFinal->update([
            'winner_routes_to_node_id' => $gf->id,
            'winner_routes_to_slot'    => 'participant_b',
        ]);

        // ── Wire Grand Final → If Necessary ───────────────────────────────────
        // (handled dynamically in BracketManager: lower winner triggers if-necessary)

        // ── Auto-handle byes in R1 (if any) ──────────────────────────────────
        $this->handleByeNode($ur1p0, $uFinal, 'participant_a');
        $this->handleByeNode($ur1p1, $uFinal, 'participant_b');
    }

    // ── Template: lower_6 ─────────────────────────────────────────────────────

    /**
     * 6 players. Seeds 1 & 2 have byes to upper semi.
     *
     *  Match 1: upper R1 pos0  – p3 vs p6
     *  Match 2: upper R1 pos1  – p4 vs p5
     *  Match 3: lower R1 pos0  – Loser(Semi2) vs Loser(R1m1)   [cross-paired]
     *  Match 4: lower R1 pos1  – Loser(Semi1) vs Loser(R1m2)   [cross-paired]
     *  Match 5: upper Semi pos0 – p1(bye) vs Winner(R1m1)
     *  Match 6: upper Semi pos1 – p2(bye) vs Winner(R1m2)
     *  Match 7: lower Semi     – Winner(LR1m1) vs Winner(LR1m2)
     *  Match 8: upper Final    – Winner(Semi1) vs Winner(Semi2)
     *  Match 9: lower Final    – Winner(lower Semi) vs Loser(upper Final)
     *  Match 10: Grand Final   – Winner(upper Final) vs Winner(lower Final)
     *  Match 11: If Necessary
     */
    private function generateLower6(CompetitionPhase $phase, array $participants): void
    {
        $p = array_pad($participants, 6, null);
        [$p1, $p2, $p3, $p4, $p5, $p6] = $p;

        // ── Upper R1 ─────────────────────────────────────────────────────────
        $ur1p0 = $this->createNode($phase, 'upper', 1, 0, $p3, $p6); // Match 1: seed3 vs seed6
        $ur1p1 = $this->createNode($phase, 'upper', 1, 1, $p4, $p5); // Match 2: seed4 vs seed5

        // ── Lower R1 (2 matches, cross-paired) ───────────────────────────────
        // Created before semi so their IDs come before semi nodes
        $lr1p0 = $this->createNode($phase, 'lower', 1, 0); // Match 3: Loser(Semi2) vs Loser(R1p0)
        $lr1p1 = $this->createNode($phase, 'lower', 1, 1); // Match 4: Loser(Semi1) vs Loser(R1p1)

        // ── Upper Semi (seeds 1,2 enter with byes) ────────────────────────────
        // participant_a is pre-filled with seed (acts as "bye" position for them)
        $uSemi0 = $this->createNode($phase, 'upper', 2, 0, $p1, null); // Match 5
        $uSemi1 = $this->createNode($phase, 'upper', 2, 1, $p2, null); // Match 6

        // ── Lower Semi ───────────────────────────────────────────────────────
        $lSemi  = $this->createNode($phase, 'lower', 2, 0);            // Match 7

        // ── Upper Final ──────────────────────────────────────────────────────
        $uFinal = $this->createNode($phase, 'upper', 3, 0);            // Match 8

        // ── Lower Final ──────────────────────────────────────────────────────
        $lFinal = $this->createNode($phase, 'lower', 3, 0);            // Match 9

        // ── Grand Final + If Necessary ────────────────────────────────────────
        $gf         = $this->createNode($phase, 'grand_final', 1, 0); // Match 10
        $gfIfNeeded = $this->createNode($phase, 'grand_final', 2, 0,
            null, null, true);                                          // Match 11

        // ── Wire upper R1 → upper Semi (winner) + lower R1 (loser, cross) ────
        $ur1p0->update([
            'winner_routes_to_node_id' => $uSemi0->id,
            'winner_routes_to_slot'    => 'participant_b',
            'loser_routes_to_node_id'  => $lr1p0->id,    // Loser of R1m1 → LR1 pos0 slot B
            'loser_routes_to_slot'     => 'participant_b',
        ]);
        $ur1p1->update([
            'winner_routes_to_node_id' => $uSemi1->id,
            'winner_routes_to_slot'    => 'participant_b',
            'loser_routes_to_node_id'  => $lr1p1->id,    // Loser of R1m2 → LR1 pos1 slot B
            'loser_routes_to_slot'     => 'participant_b',
        ]);

        // ── Wire upper Semi → upper Final (winner) + lower R1 (loser, cross) ─
        // Loser of Semi1 (pos0) → LR1 pos1 slot A (cross-paired)
        // Loser of Semi2 (pos1) → LR1 pos0 slot A (cross-paired)
        $uSemi0->update([
            'winner_routes_to_node_id' => $uFinal->id,
            'winner_routes_to_slot'    => 'participant_a',
            'loser_routes_to_node_id'  => $lr1p1->id,
            'loser_routes_to_slot'     => 'participant_a',
        ]);
        $uSemi1->update([
            'winner_routes_to_node_id' => $uFinal->id,
            'winner_routes_to_slot'    => 'participant_b',
            'loser_routes_to_node_id'  => $lr1p0->id,
            'loser_routes_to_slot'     => 'participant_a',
        ]);

        // ── Wire lower R1 → lower Semi ────────────────────────────────────────
        $lr1p0->update([
            'winner_routes_to_node_id' => $lSemi->id,
            'winner_routes_to_slot'    => 'participant_a',
        ]);
        $lr1p1->update([
            'winner_routes_to_node_id' => $lSemi->id,
            'winner_routes_to_slot'    => 'participant_b',
        ]);

        // ── Wire lower Semi → lower Final ────────────────────────────────────
        $lSemi->update([
            'winner_routes_to_node_id' => $lFinal->id,
            'winner_routes_to_slot'    => 'participant_a',
        ]);

        // ── Wire upper Final → Grand Final (winner) + lower Final (loser) ────
        $uFinal->update([
            'winner_routes_to_node_id' => $gf->id,
            'winner_routes_to_slot'    => 'participant_a',
            'loser_routes_to_node_id'  => $lFinal->id,
            'loser_routes_to_slot'     => 'participant_b',
        ]);

        // ── Wire lower Final → Grand Final (winner) ───────────────────────────
        $lFinal->update([
            'winner_routes_to_node_id' => $gf->id,
            'winner_routes_to_slot'    => 'participant_b',
        ]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function createNode(
        CompetitionPhase $phase,
        string  $side,
        int     $round,
        int     $position,
        ?string $pa          = null,
        ?string $pb          = null,
        bool    $isIfNeeded  = false
    ): BracketNode {
        return BracketNode::create([
            'phase_id'       => $phase->id,
            'bracket_side'   => $side,
            'round'          => $round,
            'position'       => $position,
            'participant_a'  => $pa,
            'participant_b'  => $pb,
            'is_bye'         => false,
            'locked'         => false,
            'is_if_necessary' => $isIfNeeded,
        ]);
    }

    /**
     * If one slot of an R1 node is null (bye), auto-set winner and promote.
     */
    private function handleByeNode(BracketNode $node, BracketNode $nextNode, string $nextSlot): void
    {
        if (is_null($node->participant_a) || is_null($node->participant_b)) {
            $winner = $node->participant_a ?? $node->participant_b;
            if ($winner) {
                $node->update(['is_bye' => true, 'winner' => $winner]);
                $nextNode->update([$nextSlot => $winner]);
            }
        }
    }
}
