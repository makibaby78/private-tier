<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'body',
        'public_id',
        'image',
        'video',
    ];

    public function media()
    {
        return $this->hasMany(\App\Models\PostMedia::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function loadPosts()
    {
        $this->posts = $this->user->posts()->with('user')->latest()->get();
    }

    public function relevanceScore(): float
    {
        $ageInMinutes = now()->diffInMinutes($this->created_at) + 1;
    
        // Get type from the first media item (or decide how to calculate overall type)
        $firstMediaType = $this->media->first()?->type ?? 'default';
    
        $typeWeight = match($firstMediaType) {
            'video' => 3,
            'image' => 2,
            default => 1,
        };

        $priorityWeight = $this->is_pinned ? 100 : 1;

        return ($typeWeight * $priorityWeight) / $ageInMinutes;
    }

}
