<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class HostKey extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'tournament_id',
        'key_hash',
        'revoked_at',
        'used_at',
        'created_at',
    ];

    protected $casts = [
        'revoked_at' => 'datetime',
        'used_at'    => 'datetime',
        'created_at' => 'datetime',
    ];

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function isValid(): bool
    {
        return is_null($this->revoked_at);
    }

    public static function generatePlainKey(): string
    {
        return Str::random(40);
    }

    public static function hashKey(string $plainKey): string
    {
        return $plainKey; // Key disimpan plain (tidak di-hash) sesuai keputusan
    }

    public static function findByPlainKey(string $plainKey): ?self
    {
        return self::where('key_hash', $plainKey)
            ->whereNull('revoked_at')
            ->first();
    }
}
