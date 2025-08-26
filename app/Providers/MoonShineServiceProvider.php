<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\CountryResource;
use App\MoonShine\Resources\CityResource;
use App\MoonShine\Resources\WorkCategoryResource;
use App\MoonShine\Resources\MessengerResource;
use App\MoonShine\Resources\UserResource;
use App\MoonShine\Resources\AdminMessageResource;
use App\MoonShine\Resources\PaymentPerResource;
use App\MoonShine\Resources\OrderResource;
use App\MoonShine\Resources\EmployerResource;
use App\MoonShine\Resources\AdminOrderMessageResource;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  MoonShine  $core
     * @param  MoonShineConfigurator  $config
     *
     */
    public function boot(CoreContract $core, ConfiguratorContract $config): void
    {
        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                CountryResource::class,
                CityResource::class,
                WorkCategoryResource::class,
                MessengerResource::class,
                UserResource::class,
                AdminMessageResource::class,
                PaymentPerResource::class,
                OrderResource::class,
                EmployerResource::class,
                AdminOrderMessageResource::class,
            ])
            ->pages([
                ...$config->getPages(),
            ])
        ;
    }
}
