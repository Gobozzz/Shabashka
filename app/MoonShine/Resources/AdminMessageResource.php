<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\AdminMessage;


use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Traits\Resource\ResourceWithParent;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends ModelResource<AdminMessage>
 */
class AdminMessageResource extends ModelResource
{
    use ResourceWithParent;
    protected string $model = AdminMessage::class;

    protected string $title = 'AdminMessages';


    protected function getParentResourceClassName(): string
    {
        return UserResource::class;
    }

    protected function getParentRelationName(): string
    {
        return 'user';
    }

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Textarea::make('Сообщение', 'text'),
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
                ID::make('ID Пользователя', 'user_id', fn() => $this->getParentId()),
                Textarea::make('Сообщение', 'text'),
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
            Textarea::make('Сообщение', 'text'),
        ];
    }

    /**
     * @param AdminMessage $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'user_id' => 'required|numeric|exists:users,id',
            'text' => "required|string",
        ];
    }
}
