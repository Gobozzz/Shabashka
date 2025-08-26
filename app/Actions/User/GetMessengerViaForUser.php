<?php

namespace App\Actions\User;

use App\Enums\Messengers;
use App\Models\User;

/**
 * Summary of GetViaForUser
 * Получаем нужный канал для доставки писем, В приоритете привязанный мессенджер
 * Можно привязать дефолтный канал, в случае отсутствия привязки будет использован он
 */
class GetMessengerViaForUser
{

    public function handle(User $user, string|null $default_channel = null): array
    {
        $default_channel_array = $default_channel ? [$default_channel] : [];
        $linked = (new GetLinkedMessengerUser())->handle($user);
        if (!$linked) {
            return $default_channel_array;
        }
        return match ($linked->messenger->name) {
            Messengers::TELEGRAM->value => ['telegram'],
            Messengers::VK->value => ['vk'],
            default => $default_channel_array
        };
    }

}