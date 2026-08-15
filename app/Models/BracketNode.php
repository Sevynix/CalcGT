<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BracketNode extends Model
{
    protected $fillable = [
        'phase_id',
        'bracket_side',
        'round',
        'position',
        'participant_a',
        'participant_b',
        'winner',
        'is_bye',
        'locked',
        'group_id',
        'loser_routes_to_node_id',
        'loser_routes_to_slot',
        'winner_routes_to_node_id',
        'winner_routes_to_slot',
        'is_if_necessary',
    ];

    protected $casts = [
        'is_bye'          => 'boolean',
        'locked'          => 'boolean',
        'is_if_necessary' => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function phase(): BelongsTo
    {
        return $this->belongsTo(CompetitionPhase::class, 'phase_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(CompetitionGroup::class);
    }

    public function loserRoutesTo(): BelongsTo
    {
        return $this->belongsTo(BracketNode::class, 'loser_routes_to_node_id');
    }

    public function winnerRoutesTo(): BelongsTo
    {
        return $this->belongsTo(BracketNode::class, 'winner_routes_to_node_id');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function hasWinner(): bool
    {
        return !is_null($this->winner);
    }

    public function isLocked(): bool
    {
        return (bool) $this->locked;
    }

    public function canCorrect(): bool
    {
        if (is_null($this->winner)) return false;

        if ($this->winner_routes_to_node_id) {
            $next = BracketNode::find($this->winner_routes_to_node_id);
            if ($next && $next->hasWinner()) return false;
        } elseif ($this->phase && $this->phase->isSingleElim() && $this->bracket_side === 'upper') {
            $nextRound = $this->round + 1;
            $nextPosition = (int) floor($this->position / 2);
            $next = BracketNode::where('phase_id', $this->phase_id)
                ->where('bracket_side', 'upper')
                ->where('round', $nextRound)
                ->where('position', $nextPosition)
                ->first();
            if ($next && $next->hasWinner()) return false;
        }

        if ($this->loser_routes_to_node_id) {
            $lower = BracketNode::find($this->loser_routes_to_node_id);
            if ($lower && $lower->hasWinner()) return false;
        }

        // If this is the target round of a phase that feeds into another manually seeded phase,
        // check if the next phase has started and has any winners.
        if ($this->phase && $this->phase->isSingleElim() && $this->round == $this->phase->targetRound()) {
            $nextPhase = $this->phase->competition->phases()->where('phase_order', '>', $this->phase->phase_order)->first();
            if ($nextPhase) {
                $hasNextWinners = BracketNode::where('phase_id', $nextPhase->id)->whereNotNull('winner')->exists();
                if ($hasNextWinners) {
                    return false;
                }
            }
        }

        return true;
    }

    public function loser(): ?string
    {
        if (!$this->winner) return null;
        if ($this->winner === $this->participant_a) return $this->participant_b;
        return $this->participant_a;
    }

    public function isUpperBracket(): bool
    {
        return $this->bracket_side === 'upper';
    }

    public function isLowerBracket(): bool
    {
        return $this->bracket_side === 'lower';
    }

    public function isGrandFinal(): bool
    {
        return $this->bracket_side === 'grand_final';
    }
}
