<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\AdminOrderMessage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Traits\Resource\ResourceWithParent;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends ModelResource<AdminOrderMessage>
 */
class AdminOrderMessageResource extends ModelResource
{
    use ResourceWithParent;
    protected string $model = AdminOrderMessage::class;

    protected string $title = 'AdminOrderMessages';

    protected function getParentResourceClassName(): string
    {
        return OrderResource::class;
    }

    protected function getParentRelationName(): string
    {
        return 'order';
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
                ID::make('ID Объявления', 'order_id', fn() => $this->getParentId()),
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
     * @param AdminOrderMessage $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'order_id' => 'required|numeric|exists:orders,id',
            'text' => "required|string",
        ];
    }
}
