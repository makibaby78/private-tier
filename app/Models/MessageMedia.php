<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageMedia extends Model
{
    protected $fillable = [
        'message_id',
        'type',
        'url',
        'caption',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

}
