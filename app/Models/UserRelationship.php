<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasVisibility;

class UserRelationship extends Model
{
    use HasFactory;
    use HasVisibility;

    protected $fillable = [
        'user_id',
        'status',
        'partner_id',
        'confirmed',
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

