<?php

namespace App\Enums;

enum StatusUser: int
{
    case ACTIVE = 1;
    case MODERATION = 2;
    case BLOCKED = 3;
    public static function translate(string|int $status, string|null $locale = null): array|string|null
    {
        $translate = [
            self::ACTIVE->value => __('statuses.active', locale: $locale),
            self::MODERATION->value => __('statuses.moderation', locale: $locale),
            self::BLOCKED->value => __('statuses.blocked', locale: $locale),
        ];
        return $translate[$status] ?? null;
    }
}
