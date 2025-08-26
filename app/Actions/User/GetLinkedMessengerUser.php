<?php

namespace App\Actions\User;

use App\Models\LinkedMessenger;
use App\Models\User;

class GetLinkedMessengerUser
{
    public function handle(User $user): LinkedMessenger|null
    {
        $linked = LinkedMessenger::where('user_id', $user->getKey())->where('is_selected', true)->first();
        return $linked;
    }
}