@use('Carbon\Carbon')
@use('App\Enums\PaymentType')
@use('App\Enums\StatusOrder')
@use('App\Enums\RolesUser')
@use('App\Helpers\CorrectWord')
@use('App\Models\Response')

<article>
    <a href="{{ route('orders.show', $order_data->getKey()) }}"
        class="block h-[200px] relative overflow-hidden rounded-tl-lg rounded-tr-lg">
        @if ($order_data['images'])
            <img class="lazyload absolute top-0 left-0 w-full h-full object-cover object-center"
                src="{{ asset("storage/" . $order_data['images'][0]) }}" alt="">
        @else
            <img class="lazyload absolute top-0 left-0 w-full h-full object-cover object-center"
                src="{{ asset("images/not_image.png") }}" alt="">
        @endif
        @if ($order_data->workCategories)
            <div class="absolute top-0 left-0 w-full flex flex-wrap gap-2 p-2">
                @foreach ($order_data->workCategories->take(2) as $work_category)
                    <div class="bg-white py-1 px-2 rounded-sm text-base shadow">{{ $work_category->name }}</div>
                @endforeach
                @AuthRole('employer')
                    @if (auth()->user()->can('my_order', $order_data))
                        <div class="bg-white py-1 px-2 rounded-sm text-base shadow">
                            {{ StatusOrder::translate($order_data->status->value) }}
                        </div>
                    @endif
                @endAuthRole
            </div>
        @endif
        <time class="absolute bottom-2 left-2 bg-white px-2 py-1 rounded-xl shadow"
            datetime="{{ date('Y-m-d', strtotime($order_data->updated_at)) }}">{{ Carbon::parse($order_data->updated_at)->translatedFormat('d F H:i') }}</time>
        @if ($order->employer->user->image)
            <img class="lazyload shadow rounded-full absolute bottom-2 right-2 w-11 h-11 object-cover object-center"
                src="{{ asset("storage/{$order->employer->user->image}") }}" alt="">
        @else
            <img class="lazyload shadow rounded-full absolute bottom-2 right-2 w-11 h-11 object-cover object-center"
                src="{{ asset('images/user.png') }}" alt="">
        @endif
    </a>
    <div class="shadow-lg p-4 rounded-br-lg rounded-bl-lg">
        <div
            class="mb-2 flex flex-col gap-1 {{ $order_data->status->value === StatusOrder::FREEZ->value ? "opacity-50" : "" }}">
            <h2 class="text-lg line-clamp-1"><a href="">{{ $order_data->title }}</a></h2>
            <p class="text-base line-clamp-3">{{ $order_data->content }}</p>
            <p class="text-base">№ {{ $order_data->getKey() }}</p>
            @if($order_data->payment_type == PaymentType::FIXED->value)
                <div class="font-bold">💰 {{ number_format($order_data->price, 0, '.', ' ') }} руб
                    {{ $order_data->payment_per }}
                </div>
            @elseif($order_data->payment_type == PaymentType::DOGOVOR->value)
                <div class="font-bold">💰 Договорная</div>
            @endif
            @if ($order_data->need_count_workers)
                <div class="font-bold">👲 {{ $order_data->need_count_workers }}
                    {{ CorrectWord::getPeopleWord($order_data->need_count_workers) }}
                </div>
            @endif
            <div class="font-bold line-clamp-1">📍 {{ $order_data->city->name }}, {{ $order_data->address }}</div>
        </div>
        <div class="flex flex-col gap-2">
            @AuthRole('employer')
                @if (auth()->user()->can('my_order', $order_data) && $order_data->adminOrderMessage()->exists())
                    <div class="info_message">{!! nl2br(e($order_data->adminOrderMessage->text)) !!}</div>
                @endif
                @if (auth()->user()->can('my_order', $order_data))
                    <a class="button-default !bg-fox" href="{{ route('orders.responses', $order_data->getKey()) }}">Отклики</a>
                @endif
                @if (auth()->user()->can('update', $order_data))
                    <a class="button-default"
                        href="{{ route('employer.orders.edit', $order_data->getKey()) }}">Редактировать</a>
                @endif
                @if (auth()->user()->can('freez', $order_data))
                    <form onsubmit="return confirm('Вы уверены, что хотите заморозить работу?');"
                        action="{{ route('orders.freezing', $order_data->getKey()) }}" method="post">
                        @csrf
                        @method('PUT')
                        <button class="button-default">Заморозить</button>
                    </form>
                @endif
                @if (auth()->user()->can('unfreez', $order_data))
                    <form onsubmit="return confirm('Вы уверены, что хотите разморозить работу?');"
                        action="{{ route('orders.unfreezing', $order_data->getKey()) }}" method="post">
                        @csrf
                        @method('PUT')
                        <button class="button-default">Разморозить</button>
                    </form>
                @endif
                @if (auth()->user()->can('delete', $order_data))
                    <form onsubmit="return confirm('Вы уверены, что хотите удалить работу?');"
                        action="{{ route('orders.destroy', $order_data->getKey()) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="button-default !bg-red">Удалить</button>
                    </form>
                @endif
            @endAuthRole
            @if (!auth()->user() || auth()->user()->can('create', Response::class))
                <form action="{{ route('orders.response', $order_data->getKey()) }}" method="post">
                    @csrf
                    <button class="button-default !bg-fox">Откликнуться</button>
                </form>
            @endif
        </div>
    </div>
</article>