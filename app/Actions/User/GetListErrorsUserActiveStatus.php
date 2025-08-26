<?php
namespace App\Actions\User;

use App\Enums\StatusUser;
use App\Models\User;

class GetListErrorsUserActiveStatus
{
    public function handle(User $user): array
    {
        $errors = [];
        if ($user->status->value === StatusUser::MODERATION->value) {
            array_push($errors, __('statuses.profile_moderation_message'));
        }
        if ($user->status->value === StatusUser::BLOCKED->value) {
            array_push($errors, __('statuses.profile_blocked_message'));
        }
        if (!$user->phone) {
            array_push($errors, __('statuses.phone_not_ok_message'));
        }
        return $errors;
    }
}