<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['title', 'image_path', 'link_url', 'position', 'is_active', 'starts_at', 'ends_at'];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Prepare a date for array / JSON serialization.
     * Prevents Laravel from appending timezone (Z) which causes Flatpickr to shift hours.
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function isCurrentlyRented()
    {
        if (!$this->starts_at || !$this->ends_at) {
            return false;
        }
        $now = now();
        return $now->between($this->starts_at, $this->ends_at);
    }

    public function getCurrentImagePathAttribute()
    {
        if ($this->isCurrentlyRented() && $this->image_path) {
            return $this->image_path;
        }
        return $this->default_image_path ?: 'images/promos/banner.png';
    }

    public function getCurrentLinkUrlAttribute()
    {
        if ($this->isCurrentlyRented()) {
            return $this->link_url;
        }
        return $this->default_link_url ?: 'https://discord.gg/QuFkQyAnJ8';
    }
}
