<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilePicture extends Model
{
    protected $fillable = [
        'post_id',
        'is_current',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->post?->user();
    }
}
