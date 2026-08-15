<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManualParticipant extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'competition_id',
        'name',
        'name_normalized',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public static function normalizeName(string $name): string
    {
        return strtolower(trim($name));
    }
}
