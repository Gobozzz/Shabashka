<?php

namespace App\Models;

use App\Enums\StatusOrder;
use App\Enums\StatusUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        "title",
        "content",
        "images",
        "address",
        "city_id",
        "payment_type",
        "price",
        "payment_per",
        "need_count_workers",
        "status",
        "employer_id",
    ];

    public function casts(): array
    {
        return [
            'images' => "array",
            'status' => StatusOrder::class,
        ];
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }

    public function workCategories(): BelongsToMany
    {
        return $this->belongsToMany(WorkCategory::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function adminOrderMessage(): HasOne
    {
        return $this->hasOne(AdminOrderMessage::class);
    }

    // Scopes
    public function scopeActived($query)
    {
        return $query->where('status', StatusOrder::ACTIVE)->whereHas('employer', function ($query) {
            return $query->whereHas('user', function ($subQuery) {
                return $subQuery->where('users.status', StatusUser::ACTIVE)->whereNotNull('users.phone');
            });
        });
    }

}
