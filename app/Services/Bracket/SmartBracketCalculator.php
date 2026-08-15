<?php

namespace App\Services\Bracket;

class SmartBracketCalculator
{
    /**
     * Calculate the optimal bracket format for N participants without byes.
     *
     * Returns an array of options, each with:
     *  - format: 'direct_elim' | 'group_then_elim'
     *  - group_count: int
     *  - group_sizes: [int, ...]
     *  - advancing_per_group: int
     *  - playoff_size: int (power of 2)
     *  - total_matches: int (estimated)
     *  - has_bye: bool
     *  - description: string
     *  - recommended: bool
     */
    public function calculate(int $count): array
    {
        if ($count < 2) {
            return [[
                'format'              => 'direct_elim',
                'group_count'         => 0,
                'group_sizes'         => [],
                'advancing_per_group' => 0,
                'playoff_size'        => 0,
                'total_matches'       => 0,
                'has_bye'             => false,
                'description'         => 'Peserta terlalu sedikit untuk turnamen.',
                'recommended'         => false,
            ]];
        }

        $options = [];

        // Option A: Direct single elimination (may have byes)
        $bracketSize = BracketHelper::nextPowerOfTwo($count);
        $byeCount    = $bracketSize - $count;
        $elimMatches = $bracketSize - 1;

        $options[] = [
            'format'              => 'direct_elim',
            'group_count'         => 0,
            'group_sizes'         => [],
            'advancing_per_group' => 0,
            'playoff_size'        => $bracketSize,
            'total_matches'       => $count - 1, // single elim always has N-1 matches
            'has_bye'             => $byeCount > 0,
            'bye_count'           => $byeCount,
            'description'         => $byeCount === 0
                ? "Single Elimination langsung ({$count} peserta, tanpa bye)"
                : "Single Elimination ({$count} peserta, {$byeCount} bye)",
            'recommended'         => $byeCount === 0, // only recommend if no byes
        ];

        // If already power of 2, direct elim is perfect - no need for group options
        if ($byeCount === 0) {
            return $options;
        }

        // Option B-D: Group stage → Playoff combinations (NO BYES)
        // Try various playoff sizes and advancing_per_group combos
        $bestGroupOption = null;
        $bestScore       = PHP_INT_MAX;

        foreach ([2, 4, 8, 16] as $playoffSize) {
            if ($playoffSize >= $count) continue; // playoff can't be bigger than total
            if ($playoffSize < 2) continue;

            foreach ([1, 2, 3] as $apg) { // advancing per group
                $groupCount = (int) ceil($playoffSize / $apg);
                if ($groupCount < 2) continue;

                // Calculate group sizes (distribute as evenly as possible)
                $groupSizes = $this->distributeToGroups($count, $groupCount);

                // Validate: every group must have at least (advancing_per_group + 1) members
                // so that advancing makes sense (can't advance everyone)
                $minGroupSize = min($groupSizes);
                if ($minGroupSize <= $apg) continue;

                // Also skip if actual advancing total != playoffSize
                $actualAdvancing = $groupCount * $apg;
                if ($actualAdvancing < $playoffSize) continue;
                // If more advance than playoff slots, we'll take top runners-up
                // For simplicity, only accept exact or +1 (wildcard) scenarios
                if ($actualAdvancing > $playoffSize + $groupCount) continue;

                // Calculate total matches
                $groupMatches = 0;
                foreach ($groupSizes as $gs) {
                    $groupMatches += $this->roundRobinMatchCount($gs);
                }
                $playoffMatches = $playoffSize - 1; // single elim always N-1
                $totalMatches   = $groupMatches + $playoffMatches;

                // Score: prefer fewer groups, reasonable group sizes (3-5 ideal),
                // and advancing_per_group of 2
                $avgGroupSize = $count / $groupCount;
                $sizeDeviation = abs($avgGroupSize - 4); // ideal group = 4
                $apgPenalty    = abs($apg - 2);          // ideal advancing = 2
                $score = $sizeDeviation * 10 + $apgPenalty * 5 + $groupCount;

                if ($score < $bestScore) {
                    $bestScore = $score;
                    
                    // Ensure playoff size matches exactly
                    $effectivePlayoffSize = min($actualAdvancing, $playoffSize);
                    $effectivePlayoffSize = BracketHelper::nextPowerOfTwo($effectivePlayoffSize);

                    $desc = "{$groupCount} Grup → Top {$apg}/grup → Playoff {$effectivePlayoffSize} peserta";
                    $groupSizeStr = implode(', ', array_map(fn($gs, $i) => chr(65 + $i) . ": {$gs}", $groupSizes, array_keys($groupSizes)));

                    $bestGroupOption = [
                        'format'              => 'group_then_elim',
                        'group_count'         => $groupCount,
                        'group_sizes'         => $groupSizes,
                        'advancing_per_group' => $apg,
                        'playoff_size'        => $effectivePlayoffSize,
                        'total_matches'       => $totalMatches,
                        'has_bye'             => false,
                        'bye_count'           => 0,
                        'description'         => "{$desc} - Grup ({$groupSizeStr})",
                        'recommended'         => true,
                    ];
                }
            }
        }

        if ($bestGroupOption) {
            // Place recommended option first
            array_unshift($options, $bestGroupOption);
            // Mark the direct_elim as not recommended since we have a better option
            $options[1]['recommended'] = false;
        }

        return $options;
    }

    /**
     * Distribute N participants into G groups as evenly as possible.
     * Returns array of group sizes.
     *
     * Example: distribute(7, 2) => [4, 3]
     *          distribute(10, 3) => [4, 3, 3]
     */
    public function distributeToGroups(int $total, int $groupCount): array
    {
        $base      = (int) floor($total / $groupCount);
        $remainder = $total % $groupCount;
        $groups    = [];

        for ($i = 0; $i < $groupCount; $i++) {
            $groups[] = $base + ($i < $remainder ? 1 : 0);
        }

        return $groups;
    }

    /**
     * Round-robin match count for N participants.
     * = N * (N-1) / 2
     */
    public function roundRobinMatchCount(int $n): int
    {
        return (int) ($n * ($n - 1) / 2);
    }

    /**
     * Check if N is a power of two.
     */
    private function isPowerOfTwo(int $n): bool
    {
        return $n > 0 && ($n & ($n - 1)) === 0;
    }
}

