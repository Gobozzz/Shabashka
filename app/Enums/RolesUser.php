<?php

namespace App\Enums;

enum RolesUser: int
{
    case WORKER = 1; // работник
    case EMPLOYER = 2; // работодатель
    case ADMIN = 3;
    public static function translate(string|int $role, string|null $locale = null): array|string|null
    {
        $translate = [
            RolesUser::WORKER->value => __('roles.worker', locale: $locale),
            RolesUser::EMPLOYER->value => __('roles.employer', locale: $locale),
            RolesUser::ADMIN->value => __('roles.admin', locale: $locale),
        ];
        return $translate[$role] ?? null;
    }
    // Роли доступные для выбора пользователем
    public static function publicRoles(): array
    {
        return [
            RolesUser::WORKER->value,
            RolesUser::EMPLOYER->value
        ];
    }

    public static function getRoleNameEn(int $role): string
    {
        $translate = [
            RolesUser::WORKER->value => "worker",
            RolesUser::EMPLOYER->value => "employer",
        ];
        if (RolesUser::tryFrom($role)) {
            return $translate[$role];
        }
        return null;
    }

}
