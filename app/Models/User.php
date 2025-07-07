<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profilePictures()
    {
        return $this->hasManyThrough(
            \App\Models\ProfilePicture::class,
            \App\Models\Post::class,
            'user_id',   // Foreign key on Post table
            'post_id',   // Foreign key on ProfilePicture table
            'id',        // Local key on User
            'id'         // Local key on Post
        );
    }

    public function getProfilePhotoUrlAttribute()
    {
        $post = $this->profilePictures()
            ->where('is_current', true)
            ->with('post.mediaFile') // eager load media file
            ->latest()
            ->first();

        return $post?->post?->mediaFile?->url
            ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
    }

    public function getProfilePostIdAttribute()
    {
        $post = $this->profilePictures()
            ->where('is_current', true)
            ->latest()
            ->first();
        
        return $post?->post_id;
    }

    public function getProfilePublicIdAttribute()
    {
        $post = $this->profilePictures()
            ->where('is_current', true)
            ->with('post.mediaFile')
            ->latest()
            ->first();

        return $post?->post?->mediaFile?->public_id;
    }

    public function media()
    {
        return $this->hasManyThrough(
            \App\Models\PostMedia::class,  // Final model (target)
            \App\Models\Post::class,       // Intermediate model
            'user_id',                     // Foreign key on Post table
            'post_id',                     // Foreign key on PostMedia table
            'id',                          // Local key on User
            'id'                           // Local key on Post
        );
    }

    public function getNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Requests sent by this user
    public function friendsOfMine()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    // Requests received by this user
    public function friendOf()
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    // All confirmed friends (merged)
    public function friends()
    {
        return $this->friendsOfMine()->wherePivot('status', 'accepted')
            ->get()->merge(
                $this->friendOf()->wherePivot('status', 'accepted')->get()
            );
    }

    // Check if a friend request is sent to another user
    public function hasSentFriendRequestTo(User $user)
    {
        return $this->friendsOfMine()
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    // Check if a friend request was received from another user
    public function hasReceivedFriendRequestFrom(User $user)
    {
        return $this->friendOf()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    // Check if already friends
    public function isFriendsWith(User $user)
    {
        return $this->friends()
            ->contains($user);
    }

    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class);
    }

}
