<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'body',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function loadPosts()
    {
        $this->posts = $this->user->posts()->with('user')->latest()->get();
    }

}
