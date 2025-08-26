<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Worker extends Model
{
    protected $fillable = [
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workCategories(): BelongsToMany
    {
        return $this->belongsToMany(WorkCategory::class);
    }

    public function response(): HasMany
    {
        return $this->hasMany(Response::class);
    }

}
