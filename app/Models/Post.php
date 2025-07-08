<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use SoftDeletes, HasFactory;

    const TYPE_STATUS = 'status';
    const TYPE_ALBUM  = 'album';
    const TYPE_SHARED = 'shared';
    const TYPE_MEDIA = 'media';

    protected $fillable = [
        'user_id',
        'body',
        'type',
    ];

    public function profilePictures()
    {
        return $this->hasMany(ProfilePicture::class);
    }

    public function isProfilePicture(): bool
    {
        return $this->profilePictures()->exists();
    }

    public function isMedia(): bool
    {
        return $this->type === self::TYPE_MEDIA;
    }

    public function isAlbum(): bool
    {
        return $this->type === self::TYPE_ALBUM;
    }

    public function isStatus(): bool
    {
        return $this->type === self::TYPE_STATUS;
    }

    public function isShared(): bool
    {
        return $this->type === self::TYPE_SHARED;
    }

    public function media()
    {
        return $this->hasMany(\App\Models\PostMedia::class);
    }

    public function mediaFile()
    {
        return $this->hasOne(\App\Models\PostMedia::class);
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
        // Make sure created_at is valid
        if (!$this->created_at instanceof \Illuminate\Support\Carbon) {
            return 0; // fallback if no valid timestamp
        }

        $ageInMinutes = now()->diffInMinutes($this->created_at);

        // Avoid log(0) by forcing minimum age of 1 minute
        $ageInMinutes = max($ageInMinutes, 1);

        // Scoring
        $baseScore = match ($this->media->first()?->type ?? null) {
            'video' => 500,
            'image' => 400,
            default => 100,
        };

        $bodyLength = strlen(strip_tags($this->body ?? ''));
        $bodyBonus = match (true) {
            $bodyLength >= 300 => 20,
            $bodyLength >= 100 => 10,
            default => 0,
        };

        $pinBonus = $this->is_pinned ? 10000 : 0;

        // Safe log decay
        $ageDecay = log($ageInMinutes + 1);
        if (!is_finite($ageDecay) || $ageDecay <= 0) {
            $ageDecay = 1; // fallback
        }

        return ($baseScore + $bodyBonus + $pinBonus) / $ageDecay;
    }

}
