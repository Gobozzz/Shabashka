<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Enums\PaymentType;
use App\Enums\StatusOrder;
use App\Models\City;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use MoonShine\Contracts\UI\ActionButtonContract;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Fields\Relationships\HasOne;
use MoonShine\Support\Enums\FormMethod;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Modal;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends ModelResource<Order>
 */
class OrderResource extends ModelResource
{
    protected string $model = Order::class;

    protected string $title = 'Объявления';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'title'),
            BelongsTo::make('Город', 'city', fn($city) => $city->name),
            BelongsTo::make('Работодатель', 'employer', fn($employer) => $employer->user->name),
            Text::make("Статус", 'status', fn($order) => StatusOrder::translate($order->status->value)),
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
                Text::make('Название', 'title'),
                BelongsTo::make('Город', 'city', fn($city) => $city->name),
                Text::make('Адрес', 'address'),
                Text::make('Тип оплаты', 'payment_type'),
                Text::make('За что платят', 'payment_per'),
                Text::make('Стоимость', 'price'),
                Text::make('Количество людей', 'need_count_workers'),
                TextArea::make('Описание', 'content'),
                Json::make('Фотки', 'images')->onlyValue("Фото", Image::make()->removable()->dir("orders/" . Carbon::now()->format('Y-m'))),
                BelongsToMany::make('Категории', 'workCategories')->selectMode(),
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
            Text::make("Статус", 'status', fn($order) => StatusOrder::translate($order->status->value)),
            Text::make('Название', 'title'),
            BelongsTo::make('Город', 'city', fn($city) => $city->name),
            Text::make('Адрес', 'address'),
            BelongsTo::make('Работодатель', 'employer', fn($employer) => $employer->user->name),
            Text::make('Тип оплаты', 'payment_type'),
            Text::make('За что платят', 'payment_per'),
            Text::make('Стоимость', 'price'),
            Text::make('Количество людей', 'need_count_workers'),
            Text::make('Описание', 'content'),
            Json::make('Фотки', 'images')->onlyValue("Фото", Image::make()),
            BelongsToMany::make('Категории', 'workCategories'),
            Date::make('Дата создания', 'created_at'),
            HasOne::make('Сообщение от администрации в профиле', 'adminOrderMessage', AdminOrderMessageResource::class),
        ];
    }

    protected function indexButtons(): ListOf
    {
        return parent::indexButtons()
            ->prepend(
                ActionButton::make(
                    'Актив',
                    fn(Model $item) => route('admin.order.actived', $item)
                )
            )
            ->prepend(
                ActionButton::make(label: 'Модр')
                    ->inModal(
                        title: 'Отправить объявление на модерацию',
                        name: static fn(mixed $item, ActionButtonContract $ctx): string => "moderation-button-{$ctx->getData()?->getKey()}",
                        builder: fn(Modal $modal, ActionButtonContract $ctx) => $modal->setComponents([
                            FormBuilder::make(route('admin.order.moderated', $ctx->getData()?->getKey()), FormMethod::POST, [
                                Textarea::make('Текст ', 'text'),
                            ])
                        ]),
                    )
            );
    }

    protected function detailButtons(): ListOf
    {
        return parent::indexButtons()
            ->prepend(
                ActionButton::make(
                    'Актив',
                    fn(Model $item) => route('admin.order.actived', $item)
                )
            )
            ->prepend(
                ActionButton::make(label: 'Модр')
                    ->inModal(
                        title: 'Отправить объявление на модерацию',
                        name: static fn(mixed $item, ActionButtonContract $ctx): string => "moderation-button-{$ctx->getData()?->getKey()}",
                        builder: fn(Modal $modal, ActionButtonContract $ctx) => $modal->setComponents([
                            FormBuilder::make(route('admin.order.moderated', $ctx->getData()?->getKey()), FormMethod::POST, [
                                Textarea::make('Текст ', 'text'),
                            ])
                        ]),
                    )
            );
    }

    protected function filters(): iterable
    {
        $optionsStatuses = [];
        foreach (StatusOrder::cases() as $key => $value) {
            $optionsStatuses["{$value->value}"] = StatusOrder::translate($value->value);
        }
        return [
            Text::make('ID', 'orders.id'),
            BelongsToMany::make('Категории', 'workCategories')->selectMode(),
            Select::make("Статус", 'status')
                ->options($optionsStatuses)
                ->nullable(),
            Select::make("Город", 'city_id')
                ->options(City::orderBy('name', 'asc')->pluck('name', 'id')->toArray())
                ->nullable(),
        ];
    }

    /**
     * @param Order $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'title' => "required|string|max:255",
            'content' => "required|string|max:6000",
            'city_id' => "required|numeric|exists:cities,id",
            'address' => "required|string|max:255",
            'payment_type' => "required|string|max:255",
            'price' => "nullable|numeric|min:1|max:10000000",
            'payment_per' => "nullable|string",
            'need_count_workers' => "nullable|numeric|min:1|max:200",
            'images' => 'nullable|array|min:1|max:10',
            'workCategories' => 'required|array|min:1|max:' . config('data_app.max_count_selected_categories_for_new_order'),
            'workCategories.*' => "numeric|exists:work_categories,id",
        ];
    }
}
