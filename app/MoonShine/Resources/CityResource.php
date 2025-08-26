<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\City;

use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<City>
 */
class CityResource extends ModelResource
{
    protected string $model = City::class;

    protected string $title = 'Города';

    protected string $column = "name";

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'name')->sortable(),
            BelongsTo::make('Страна', 'country')->sortable(),
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
                BelongsTo::make('Страна', 'country'),
            ])
        ];
    }

    protected function filters(): iterable
    {
        return [
            Text::make('Название', 'name')
                ->onApply(fn(Builder $query, ?string $value) => $value === null ? $query : $query->whereLike('name', "%$value%")),
            BelongsTo::make('Страна', 'country')
                ->onApply(fn(Builder $query, ?string $value) => $value === null ? $query : $query->where('country_id', $value)),
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
            BelongsTo::make('Страна', 'country'),
        ];
    }

    /**
     * @param City $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
        ];
    }
}
