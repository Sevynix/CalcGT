<?php

namespace App\Services\Bracket;

/**
 * Maps each fixed slot_count to its available bracket format options.
 *
 * Each option:
 *   code        – unique identifier stored in competitions.bracket_format
 *   label       – short display name
 *   description – longer flow description
 *   phases      – ordered list of phase types that will be created
 *   group_count – number of groups (if applicable)
 *   top_n       – how many advance per group (if applicable)
 */
class FixedBracketOptionsResolver
{
    // ── Public API ────────────────────────────────────────────────────────────

    public static function getOptions(int $slotCount): array
    {
        $options = match ($slotCount) {
            32 => self::options32(),
            24 => self::options24(),
            16 => self::options16(),
            default => [],
        };
        
        if (!empty($options)) {
            $isPowerOfTwo = ($slotCount & ($slotCount - 1)) === 0 && $slotCount > 0;
            
            if ($isPowerOfTwo) {
                $hasSeFull = false;
                $hasSeLower4 = false;
                foreach ($options as $opt) {
                    if ($opt['code'] === 'se_full') $hasSeFull = true;
                    if ($opt['code'] === 'se_lower_4') $hasSeLower4 = true;
                }
                
                $prepend = [];
                if (!$hasSeFull) {
                    $prepend[] = [
                        'code'        => 'se_full',
                        'label'       => 'Single Elimination Penuh',
                        'description' => "Fase gugur hingga mendapat 1 pemenang",
                        'flow'        => "Single Elimination",
                        'phases'      => ['single_elim'],
                        'group_count' => 0,
                        'top_n'       => 0,
                    ];
                }
                if (!$hasSeLower4) {
                    $prepend[] = [
                        'code'        => 'se_lower_4',
                        'label'       => 'SE + Lower Bracket dari 4',
                        'description' => "Single Elim sampai sisa 4, lalu fase upper/lower",
                        'flow'        => "Single Elim ⬇ Lower Bracket (Top 4)",
                        'phases'      => ['single_elim_to_4', 'upper_lower_4'],
                        'group_count' => 0,
                        'top_n'       => 0,
                    ];
                }
                
                $options = array_merge($prepend, $options);
            }
        }

        return $options;
    }

    public static function getOption(int $slotCount, ?string $code): ?array
    {
        if (!$code) return null;
        foreach (self::getOptions($slotCount) as $opt) {
            if ($opt['code'] === $code) return $opt;
        }
        return null;
    }

    public static function validCodes(): array
    {
        return [
            'se_full',
            'se_lower_4',
            'se_lower_6',
        ];
    }

    // ── Per-slot options ──────────────────────────────────────────────────────

    private static function options32(): array
    {
        return [
            [
                'code'        => 'se_full',
                'label'       => 'Single Elimination Penuh',
                'description' => '32 → 16 → 8 → 4 → 2 → 1 (champion)',
                'flow'        => '32→16→8→4→2→1',
                'phases'      => ['single_elim'],
                'group_count' => 0,
                'top_n'       => 0,
            ],
            [
                'code'        => 'se_lower_4',
                'label'       => 'SE + Lower Bracket dari 4',
                'description' => '32 → 16 → 8 → 4, lalu fase upper/lower dari 4 finalis',
                'flow'        => '32→16→8→4 ⬇ Lower Bracket',
                'phases'      => ['single_elim_to_4', 'upper_lower_4'],
                'group_count' => 0,
                'top_n'       => 0,
            ],
        ];
    }

    private static function options24(): array
    {
        return [
            [
                'code'        => 'se_lower_6',
                'label'       => 'SE + Lower Bracket dari 6',
                'description' => '24 → 12 → 6, lalu fase upper/lower dari 6 finalis',
                'flow'        => '24→12→6 ⬇ Lower Bracket',
                'phases'      => ['single_elim_to_6', 'upper_lower_6'],
                'group_count' => 0,
                'top_n'       => 0,
            ],
        ];
    }

    private static function options16(): array
    {
        return [
            [
                'code'        => 'se_full',
                'label'       => 'Single Elimination Penuh',
                'description' => '16 → 8 → 4 → 2 → 1 (champion)',
                'flow'        => '16→8→4→2→1',
                'phases'      => ['single_elim'],
                'group_count' => 0,
                'top_n'       => 0,
            ],
            [
                'code'        => 'se_lower_4',
                'label'       => 'SE + Lower Bracket dari 4',
                'description' => '16 → 8 → 4, lalu fase upper/lower dari 4 finalis',
                'flow'        => '16→8→4 ⬇ Lower Bracket',
                'phases'      => ['single_elim_to_4', 'upper_lower_4'],
                'group_count' => 0,
                'top_n'       => 0,
            ],
        ];
    }
}
