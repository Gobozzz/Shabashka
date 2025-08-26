<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Support\Enums\FormMethod;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Title;
use MoonShine\UI\Fields\Textarea;
#[\MoonShine\MenuManager\Attributes\SkipMenu]

class Dashboard extends Page
{
    /**
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    public function getTitle(): string
    {
        return $this->title ?: 'Dashboard';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        return [
            FormBuilder::make(route('admin.send.message.allUser'), FormMethod::POST, [
                Title::make("Отправка уведомлений пользователям"),
                Textarea::make('Сообщение', "text"),
            ])->submit(
                    label: 'Отправить',
                    attributes: ['class' => 'btn-primary']
                ),
        ];
    }
}
