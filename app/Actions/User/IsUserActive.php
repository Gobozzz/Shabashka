<?php
namespace App\Actions\User;

use App\Enums\StatusUser;
use App\Models\User;

class IsUserActive
{
    public function handle(User $user): bool
    {
        // Пользователь полностю валиден
        // 1. Статус пользователя Active
        // 2. И есть номер телефона
        if (
            $user->status->value === StatusUser::ACTIVE->value && $user->phone
        ) {
            return true;
        }
        return false;
    }
}