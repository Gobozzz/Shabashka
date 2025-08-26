<?php

namespace App\Providers;

use App\Enums\RolesUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeDirectiveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Blade::directive('activeMenu', function ($expression) {
            return "<?php
        \$urlPattern = {$expression};
        echo request()->is(\$urlPattern) ? 'active' : '';
    ?>";
        });
        Blade::if('AuthRole', function ($roles): bool {
            $user = Auth::user();
            if (!$user) {
                return false;
            }
            $role = RolesUser::getRoleNameEn($user->role->value);
            $roles = explode(',', $roles);
            if (in_array($role, $roles)) {
                return true;
            }
            return false;
        });
    }
}
