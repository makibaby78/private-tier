<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserRelationship extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'partner_id',
        'since',
        'visibility',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }
}

