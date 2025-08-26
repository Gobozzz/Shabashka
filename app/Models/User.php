<?php

namespace App\Models;

use App\Enums\StatusUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RolesUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasPushSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'phone',
        'password',
        'role',
        'status',
        'image',
        'city_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'role' => RolesUser::class,
            'status' => StatusUser::class,
        ];
    }

    public function linkedMessenger(): HasMany
    {
        return $this->hasMany(LinkedMessenger::class);
    }
    public function worker(): HasOne
    {
        return $this->hasOne(Worker::class);
    }
    public function employer(): HasOne
    {
        return $this->hasOne(Employer::class);
    }
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
    public function adminMessage(): HasOne
    {
        return $this->hasOne(AdminMessage::class);
    }
}
