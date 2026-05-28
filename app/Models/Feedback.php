<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = ['name', 'type', 'message', 'image_path', 'admin_reply', 'is_published', 'is_read'];
}
