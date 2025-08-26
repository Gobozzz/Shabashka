<?php

namespace App\Actions\User;

use App\Enums\StatusUser;
use App\Models\User;

class IsBlockedAccessUser
{
    public function handle(User $user): bool
    {
        // Все условия при которых пользователю ограничен доступ к сервису
        if ($user->status->value === StatusUser::BLOCKED->value) {
            return true;
        }
        return false;
    }
}