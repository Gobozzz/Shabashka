<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestLinkedMessenger extends Model
{
    protected $fillable = [
        "user_id",
        "messenger_id",
        "token",
    ];

    public function messenger(): BelongsTo
    {
        return $this->belongsTo(Messenger::class);
    }

}
