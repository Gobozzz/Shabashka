<?php

namespace App\Actions\User;

use App\Models\User;

// Проврека на обновляемые поля, чтобы послать пользователя на модерацию
class IsFieldsUpdatedUserModeration
{
    public array $fields = [
        'name',
        'image',
    ];
    public function handle(User $user): bool
    {
        foreach ($this->fields as $field) {
            if ($user->isDirty($field)) {
                return true;
            }
        }
        return false;
    }
}