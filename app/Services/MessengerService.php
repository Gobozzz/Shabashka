<?php

namespace App\Services;

use App\Models\Messenger;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class MessengerService
{
    public function getAllWithLinkedInfoUser(User $user): Collection
    {
        return Messenger::all()->map(function ($messenger) use ($user) {
            $messenger->linked = $user->linkedMessenger()->where('messenger_id', $messenger->getKey())->first();
            return $messenger;
        });
    }

}