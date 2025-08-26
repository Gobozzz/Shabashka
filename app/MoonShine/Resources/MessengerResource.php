<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Messenger;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<Messenger>
 */
class MessengerResource extends ModelResource
{
    protected string $model = Messenger::class;

    protected string $title = 'Мессенджеры';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'name'),
            Image::make('Фото', 'image'),
            Text::make('Название бота', 'name_bot'),
            Text::make('Ссылка на бота', 'link_bot'),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Название', 'name'),
                Image::make('Фото', 'image')->dir('messengers'),
                Text::make('Название бота', 'name_bot'),
                Text::make('Ссылка на бота', 'link_bot'),
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            Text::make('Название', 'name'),
            Image::make('Фото', 'image'),
            Text::make('Название бота', 'name_bot'),
            Text::make('Ссылка на бота', 'link_bot'),
        ];
    }

    /**
     * @param Messenger $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        if ($item) {
            return [
                'name' => "required|string|max:255|unique:messengers,name,{$item->id}",
                'image' => 'nullable|image',
                'name_bot' => "required|string|max:255",
                'link_bot' => "required|string|max:255",
            ];
        }
        return [
            'name' => 'required|string|max:255|unique:messengers,name',
            'image' => 'required|image',
            'name_bot' => "required|string|max:255",
            'link_bot' => "required|string|max:255",
        ];
    }
}
