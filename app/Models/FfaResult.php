<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FfaResult extends Model
{
    protected $fillable = [
        'competition_id',
        'phase_id',
        'participant_name',
        'passed',
    ];

    protected $casts = [
        'passed' => 'boolean',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function phase(): BelongsTo
    {
        return $this->belongsTo(CompetitionPhase::class, 'phase_id');
    }
}
