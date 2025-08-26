<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Laravel\Components\Layout\{Locales, Notifications, Profile, Search};
use MoonShine\MenuManager\MenuGroup;
use MoonShine\UI\Components\{
    Breadcrumbs,
    Components,
    Layout\Flash,
    Layout\Div,
    Layout\Body,
    Layout\Burger,
    Layout\Content,
    Layout\Footer,
    Layout\Head,
    Layout\Favicon,
    Layout\Assets,
    Layout\Meta,
    Layout\Header,
    Layout\Html,
    Layout\Layout,
    Layout\Logo,
    Layout\Menu,
    Layout\Sidebar,
    Layout\ThemeSwitcher,
    Layout\TopBar,
    Layout\Wrapper,
    When
};
use App\MoonShine\Resources\CountryResource;
use MoonShine\MenuManager\MenuItem;
use App\MoonShine\Resources\CityResource;
use App\MoonShine\Resources\WorkCategoryResource;
use App\MoonShine\Resources\MessengerResource;
use App\MoonShine\Resources\UserResource;
use App\MoonShine\Resources\PaymentPerResource;
use App\MoonShine\Resources\OrderResource;
use App\MoonShine\Resources\EmployerResource;
use App\MoonShine\Resources\AdminOrderMessageResource;

final class MoonShineLayout extends AppLayout
{
    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        return [
            ...parent::menu(),
            MenuGroup::make('Локации', [
                MenuItem::make('Страны', CountryResource::class),
                MenuItem::make('Города', CityResource::class),
            ]),
            MenuGroup::make('Для объвлений', [
                MenuItem::make('Категории работ', WorkCategoryResource::class),
                MenuItem::make('Форматы оплат', PaymentPerResource::class),
            ]),
            MenuItem::make('Объявления', OrderResource::class),
            MenuItem::make('Мессенджеры', MessengerResource::class),
            MenuItem::make('Пользователи', UserResource::class),
        ];
    }

    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }

    public function build(): Layout
    {
        return parent::build();
    }
}
