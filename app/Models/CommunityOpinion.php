<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityOpinion extends Model
{
    use HasFactory;

    protected $fillable = [
        'community_post_id',
        'name',
        'opinion',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function post()
    {
        return $this->belongsTo(CommunityPost::class, 'community_post_id');
    }
}
