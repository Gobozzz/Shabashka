<?php

namespace App\Enums;

enum StatusOrder: int
{
    case ACTIVE = 1;
    case MODERATION = 2;
    case FREEZ = 3;
    public static function translate(string|int $status): array|string|null
    {
        $translate = [
            self::ACTIVE->value => __('statuses.active_order'),
            self::MODERATION->value => __('statuses.moderation_order'),
            self::FREEZ->value => __('statuses.freez_order'),
        ];
        return $translate[$status] ?? null;
    }
}
