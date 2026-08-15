<?php

namespace App\Services\Bracket;

use App\Models\BracketNode;
use App\Models\CompetitionPhase;
use App\Models\CompetitionGroup;

class GroupStageGenerator
{
    /**
     * Generate group stage (round-robin) bracket.
     */
    public function generate(CompetitionPhase $phase, array $participants, int $groupCount): void
    {
        $phase->update(['participant_count' => count($participants), 'status' => 'ongoing']);
        
        shuffle($participants);
        $groups = array_fill(0, $groupCount, []);
        
        // Distribute participants to groups
        foreach ($participants as $index => $participant) {
            $groups[$index % $groupCount][] = $participant;
        }

        foreach ($groups as $i => $groupParticipants) {
            $groupName = 'Grup ' . chr(65 + $i); // Grup A, Grup B, etc
            $standings = [];
            foreach ($groupParticipants as $p) {
                $standings[] = [
                    'participant' => $p,
                    'wins' => 0,
                    'losses' => 0,
                    'points' => 0,
                    'matches_played' => 0,
                ];
            }

            $groupModel = CompetitionGroup::create([
                'phase_id' => $phase->id,
                'name' => $groupName,
                'standings' => $standings,
            ]);

            // Circle Method for Round Robin
            $n = count($groupParticipants);
            $ps = $groupParticipants;
            if ($n % 2 !== 0) {
                $ps[] = null; // Dummy for bye
                $n++;
            }
            $rounds = $n - 1;
            $half = $n / 2;

            $posCounter = []; // Keep track of position in each round

            for ($r = 0; $r < $rounds; $r++) {
                $roundNum = $r + 1;
                $posCounter[$roundNum] = 0;

                for ($idx = 0; $idx < $half; $idx++) {
                    $p1 = $ps[$idx];
                    $p2 = $ps[$n - 1 - $idx];
                    if ($p1 !== null && $p2 !== null) {
                        BracketNode::create([
                            'phase_id'      => $phase->id,
                            'bracket_side'  => 'upper',
                            'group_id'      => $groupModel->id,
                            'round'         => $roundNum,
                            'position'      => $posCounter[$roundNum]++,
                            'participant_a' => $p1,
                            'participant_b' => $p2,
                            'is_bye'        => false,
                            'locked'        => false,
                        ]);
                    }
                }
                
                // Rotate array keeping index 0 fixed
                $temp = $ps[$n - 1];
                for ($k = $n - 1; $k > 1; $k--) {
                    $ps[$k] = $ps[$k - 1];
                }
                $ps[1] = $temp;
            }
        }
    }
}
