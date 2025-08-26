<?php

namespace App\Actions\User;

use App\Enums\RolesUser;
use App\Models\User;

class GetUrlLkForUser
{
    public function handle(User $user): string
    {
        return match ($user->role->value) {
            RolesUser::WORKER->value => route('worker.profile'),
            RolesUser::EMPLOYER->value => route('employer.profile'),
            default => route('welcome'),
        };
    }
}