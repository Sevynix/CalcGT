<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompetitionGroup extends Model
{
    protected $fillable = [
        'phase_id',
        'name',
        'standings',
    ];

    protected $casts = [
        'standings' => 'array',
    ];

    public function phase(): BelongsTo
    {
        return $this->belongsTo(CompetitionPhase::class, 'phase_id');
    }

    public function nodes(): HasMany
    {
        return $this->hasMany(BracketNode::class, 'group_id');
    }

    public function getSortedStandings(): array
    {
        $standings = $this->standings ?? [];
        usort($standings, function ($a, $b) {
            if ($a['points'] === $b['points']) {
                if ($a['wins'] === $b['wins']) {
                    return $a['losses'] <=> $b['losses'];
                }
                return $b['wins'] <=> $a['wins'];
            }
            return $b['points'] <=> $a['points'];
        });
        return $standings;
    }
}
