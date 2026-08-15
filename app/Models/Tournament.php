<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tournament extends Model
{
    protected $fillable = [
        'name',
        'description',
        'banner_path',
        'banner_aspect_ratio',
        'status',
        'is_visible',
        'created_by',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function competitions(): HasMany
    {
        return $this->hasMany(Competition::class)->orderBy('reveal_order');
    }

    public function hostKeys(): HasMany
    {
        return $this->hasMany(HostKey::class);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function activeHostKey(): ?HostKey
    {
        return $this->hostKeys()
            ->whereNull('revoked_at')
            ->latest()
            ->first();
    }
}
