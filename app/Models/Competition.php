<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competition extends Model
{
    protected $fillable = [
        'tournament_id',
        'name',
        'detail',
        'photo_path',
        'reveal_order',
        'slot_total',
        'slot_count',
        'has_registration',
        'bracket_format',
        'phase2_target_count',
        'status',
        'has_bracket',
    ];

    protected $casts = [
        'has_registration' => 'boolean',
        'has_bracket'      => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function manualParticipants(): HasMany
    {
        return $this->hasMany(ManualParticipant::class);
    }

    public function phases(): HasMany
    {
        return $this->hasMany(CompetitionPhase::class)->orderBy('phase_order');
    }

    public function ffaResults(): HasMany
    {
        return $this->hasMany(FfaResult::class);
    }

    // ── Phase helpers ─────────────────────────────────────────────────────────

    public function phase(int $order): ?CompetitionPhase
    {
        return $this->phases()->where('phase_order', $order)->first();
    }

    public function phase1(): ?CompetitionPhase
    {
        return $this->phase(1);
    }

    public function phase2(): ?CompetitionPhase
    {
        return $this->phase(2);
    }

    public function phase3(): ?CompetitionPhase
    {
        return $this->phase(3);
    }

    public function latestPhase(): ?CompetitionPhase
    {
        return $this->phases()->reorder()->orderByDesc('phase_order')->first();
    }

    public function activePhase(): ?CompetitionPhase
    {
        return $this->phases()->where('status', 'ongoing')->first();
    }

    // ── Participant helpers ───────────────────────────────────────────────────

    public function getRegisteredNames(): array
    {
        return $this->registrations()->pluck('grow_id')->toArray();
    }

    public function getManualNames(): array
    {
        return $this->manualParticipants()->pluck('name')->toArray();
    }

    // ── Bracket format helpers ────────────────────────────────────────────────

    public function hasLowerBracket(): bool
    {
        return in_array($this->bracket_format, [
            'se_lower_4', 'se_lower_6',
        ]);
    }

    public function formatLabel(): string
    {
        if (!$this->bracket_format) return 'Belum dipilih';
        $opt = \App\Services\Bracket\FixedBracketOptionsResolver::getOption($this->effectiveSlotCount(), $this->bracket_format);
        return $opt ? $opt['label'] : $this->bracket_format;
    }

    public function effectiveSlotCount(): int
    {
        return $this->slot_count ?? $this->slot_total ?? 8;
    }

    // ── Status helpers ────────────────────────────────────────────────────────

    public function isRegistrationOpen(): bool
    {
        return $this->status === 'registration_open';
    }

    public function isVisible(): bool
    {
        return !in_array($this->status, ['hidden']);
    }

    public function isOngoing(): bool
    {
        return $this->status === 'ongoing';
    }

    public function isFinished(): bool
    {
        return $this->status === 'finished';
    }

    public function canBeRevealed(): bool
    {
        if ($this->status !== 'hidden') {
            return false;
        }

        $previousUnrevealed = Competition::where('tournament_id', $this->tournament_id)
            ->where('reveal_order', '<', $this->reveal_order)
            ->where('status', 'hidden')
            ->exists();

        return !$previousUnrevealed;
    }

    public function canSetupBracket(): bool
    {
        if ($this->status === 'ongoing') return false;
        if ($this->status === 'finished') return false;

        if (!$this->has_registration) {
            return in_array($this->status, ['revealed', 'registration_closed']);
        }

        return $this->status === 'registration_closed';
    }
}
