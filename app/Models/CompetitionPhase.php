<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompetitionPhase extends Model
{
    protected $fillable = [
        'competition_id',
        'phase_order',
        'bracket_type',        // single_elim | round_robin | upper_lower_4 | upper_lower_6 | ffa
        'participant_source',  // spin_wheel | all_registered | manual | survivors
        'participant_count',
        'survivor_count',
        'advancing_per_group',
        'status',              // pending | seeding | ongoing | finished
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function nodes(): HasMany
    {
        return $this->hasMany(BracketNode::class, 'phase_id')
            ->orderBy('bracket_side')
            ->orderBy('round')
            ->orderBy('position');
    }

    public function upperNodes(): HasMany
    {
        return $this->hasMany(BracketNode::class, 'phase_id')
            ->where('bracket_side', 'upper')
            ->orderBy('round')
            ->orderBy('position');
    }

    public function lowerNodes(): HasMany
    {
        return $this->hasMany(BracketNode::class, 'phase_id')
            ->where('bracket_side', 'lower')
            ->orderBy('round')
            ->orderBy('position');
    }

    public function grandFinalNodes(): HasMany
    {
        return $this->hasMany(BracketNode::class, 'phase_id')
            ->where('bracket_side', 'grand_final')
            ->orderBy('round')
            ->orderBy('position');
    }

    public function groups(): HasMany
    {
        return $this->hasMany(CompetitionGroup::class, 'phase_id');
    }

    public function ffaResults(): HasMany
    {
        return $this->hasMany(FfaResult::class, 'phase_id');
    }

    // ── Status helpers ────────────────────────────────────────────────────────

    public function isSeeding(): bool
    {
        return $this->status === 'seeding';
    }

    public function isOngoing(): bool
    {
        return $this->status === 'ongoing';
    }

    public function isFinished(): bool
    {
        return $this->status === 'finished';
    }

    // ── Type helpers ──────────────────────────────────────────────────────────

    public function isSingleElim(): bool
    {
        return in_array($this->bracket_type, ['single_elim', 'single_elim_to_4', 'single_elim_to_6']);
    }

    public function isGroupStage(): bool
    {
        return $this->bracket_type === 'round_robin';
    }

    public function isUpperLower(): bool
    {
        return in_array($this->bracket_type, ['upper_lower_4', 'upper_lower_6']);
    }

    public function isFfa(): bool
    {
        return $this->bracket_type === 'ffa';
    }

    public function targetRound(): ?int
    {
        if (!$this->survivor_count || !$this->participant_count) {
            return null;
        }
        $bracketSize = (int) pow(2, ceil(log($this->participant_count, 2)));
        $target = $this->survivor_count;
        return (int) log($bracketSize / $target, 2);
    }
}
