<?php

namespace App\Services\Bracket;

class BracketHelper
{
    public static function nextPowerOfTwo(int $n): int
    {
        // Minimum bracket size is 2 (need at least 2 slots to form a match)
        if ($n <= 1) return 2;
        // If already a power of two
        if (($n & ($n - 1)) === 0) return $n;
        return (int) pow(2, ceil(log($n, 2)));
    }

    public static function getTotalRounds(int $participantCount): int
    {
        $bracketSize = self::nextPowerOfTwo($participantCount);
        return (int) log($bracketSize, 2);
    }
}
