<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LinkedMessenger extends Model
{
    protected $fillable = [
        'user_id',
        'messenger_id',
        'user_messenger_id',
        'is_selected',
    ];

    public function messenger(): BelongsTo
    {
        return $this->belongsTo(Messenger::class);
    }

}
