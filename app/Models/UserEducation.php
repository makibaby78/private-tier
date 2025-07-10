<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserEducation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'school',
        'degree',
        'field_of_study',
        'start_date',
        'end_date',
        'is_current',
        'visibility',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

