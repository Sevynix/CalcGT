<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'competition_id',
        'grow_id',
        'grow_id_normalized',
        'client_token',
        'ip_hash',
        'flagged_duplicate',
        'created_at',
    ];

    protected $casts = [
        'flagged_duplicate' => 'boolean',
        'created_at'        => 'datetime',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public static function normalizeGrowId(string $growId): string
    {
        return strtolower(trim($growId));
    }

    public static function hashIp(string $ip): string
    {
        return hash('sha256', $ip . config('app.key'));
    }
}
