<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Enums\RolesUser;
use App\Enums\StatusUser;
use App\Models\City;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

use MoonShine\Contracts\UI\ActionButtonContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\HasOne;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Enums\FormMethod;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Modal;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends ModelResource<User>
 */
class UserResource extends ModelResource
{
    protected string $model = User::class;

    protected string $title = 'Пользователи';

    protected string $column = "name";

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Имя', 'name')->sortable(),
            Text::make('Телефон', 'phone'),
            Text::make('Почта', 'email'),
            Text::make("Роль", "role", fn($user) => RolesUser::translate($user->role->value)),
            Text::make('Статус', 'status', fn($user) => StatusUser::translate($user->status->value)),
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
                Text::make('Имя', 'name'),
                Text::make('Телефон', 'phone'),
                Text::make('Почта', 'email'),
                Image::make('Фото', 'image')->removable()->dir('users'),
                BelongsTo::make('Город', 'city')->nullable(),
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
            Text::make('Имя', 'name')->sortable(),
            Text::make('Телефон', 'phone'),
            Text::make('Почта', 'email'),
            Text::make('Статус', 'status', fn($user) => StatusUser::translate($user->status->value)),
            Image::make('Фото', 'image'),
            BelongsTo::make('Город', 'city', fn($city) => $city->name ?? "Не выбран"),
            HasOne::make('Сообщение от администрации в профиле', 'adminMessage', AdminMessageResource::class),
        ];
    }

    protected function indexButtons(): ListOf
    {
        return parent::indexButtons()
            ->prepend(
                ActionButton::make(
                    'Блок',
                    fn(Model $item) => route('admin.user.blocked', $item)
                )
            )
            ->prepend(
                ActionButton::make(
                    'Актив',
                    fn(Model $item) => route('admin.user.actived', $item)
                )
            )
            ->prepend(
                ActionButton::make(label: 'Модр')
                    ->inModal(
                        title: 'Отправить пользователя на модерацию',
                        name: static fn(mixed $item, ActionButtonContract $ctx): string => "moderation-button-{$ctx->getData()?->getKey()}",
                        builder: fn(Modal $modal, ActionButtonContract $ctx) => $modal->setComponents([
                            FormBuilder::make(route('admin.user.moderated', $ctx->getData()?->getKey()), FormMethod::POST, [
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
                    'Блок',
                    fn(Model $item) => route('admin.user.blocked', $item)
                )
            )
            ->prepend(
                ActionButton::make(
                    'Актив',
                    fn(Model $item) => route('admin.user.actived', $item)
                )
            )
            ->prepend(
                ActionButton::make('Модр')
                    ->inModal(
                        title: 'Отправить пользователя на модерацию',
                        name: static fn(mixed $item, ActionButtonContract $ctx): string => "moderation-button-{$ctx->getData()?->getKey()}",
                        builder: fn(Modal $modal, ActionButtonContract $ctx) => $modal->setComponents([
                            FormBuilder::make(route('admin.user.moderated', $ctx->getData()?->getKey()), FormMethod::POST, [
                                Textarea::make('Текст ', 'text'),
                            ])
                        ]),
                    )
            )
            ->prepend(
                ActionButton::make('Уведомление')
                    ->inModal(
                        title: 'Отправить пользователю уведомление',
                        name: static fn(mixed $item, ActionButtonContract $ctx): string => "sending-button-{$ctx->getData()?->getKey()}",
                        builder: fn(Modal $modal, ActionButtonContract $ctx) => $modal->setComponents([
                            FormBuilder::make(route('admin.send.message.user', $ctx->getData()?->getKey()), FormMethod::POST, [
                                Textarea::make('Текст ', 'text'),
                            ])
                        ]),
                    )
            );
    }

    protected function filters(): iterable
    {
        $optionsStatuses = [];
        foreach (StatusUser::cases() as $key => $value) {
            $optionsStatuses["{$value->value}"] = StatusUser::translate($value->value);
        }
        $optionsRoles = [];
        foreach (RolesUser::cases() as $key => $value) {
            $optionsRoles["{$value->value}"] = RolesUser::translate($value->value);
        }
        return [
            Text::make('ID', 'id'),
            Text::make('Телефон', 'phone')->onApply(function (Builder $query, $value) {
                if ($value) {
                    $query->whereLike('phone', "%{$value}%");
                }
            }),
            Text::make('Почта', 'email'),
            Select::make("Статус", 'status')
                ->options($optionsStatuses)
                ->nullable(),
            Select::make("Роль", 'role')
                ->options($optionsRoles)
                ->nullable(),
            Select::make("Город", 'city_id')
                ->options(City::orderBy('name', 'asc')->pluck('name', 'id')->toArray())
                ->nullable(),
        ];
    }

    /**
     * @param User $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => "required|string|max:255|unique:users,email," . $item->getKey(),
            'phone' => "nullable|phone:AUTO|unique:users,phone," . $item->getKey(),
            'city_id' => "nullable|numeric|exists:cities,id",
            'image' => "nullable|image|max:3072",
        ];
    }
}
