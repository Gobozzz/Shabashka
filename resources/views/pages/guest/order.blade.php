@use('Carbon\Carbon')
@use('App\Enums\PaymentType')
@use('App\Enums\StatusOrder')
@use('App\Enums\RolesUser')
@use('App\Helpers\CorrectWord')
@use('App\Models\Response')

@extends('layouts.guest')

@section('title', "Работа на Шабашка | {$order->title}")
@section('description', mb_substr($order->content, 0, 150))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/swiper-bundle.min.css') }}">
@endpush

@section('content')
    <section class="pt-44 max-lg:pt-20">
        <div class="container-custom">
            <div class="font-bebas text-6xl mb-8 max-lg:text-4xl">Подробнее о работе</div>
            @include('includes.components.session-success-or-error')
            <h1 class="text-2xl mb-8">{{ $order->title }}</h1>
            @if ($order->images)
                <div id="slider_order" class="overflow-hidden max-w-[800px] mb-5">
                    <div class="swiper-wrapper">
                        @foreach ($order->images as $image)
                            <div class="swiper-slide">
                                <img class="lazyload rounded-xl" src="{{ asset("storage/$image") }}" alt="">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="flex flex-col gap-5">
                <div class="flex items-center gap-3">
                    <time class="bg-white px-2 py-1 rounded-xl shadow"
                        datetime="{{ date('Y-m-d', strtotime($order->updated_at)) }}">{{ Carbon::parse($order->updated_at)->translatedFormat('d F H:i') }}</time>
                    @if ($order->employer->user->image)
                        <img class="lazyload shadow-sm rounded-full w-11 h-11 object-cover object-center"
                            src="{{ asset("storage/{$order->employer->user->image}") }}" alt="">
                    @else
                        <img class="lazyload shadow-sm rounded-full w-11 h-11 object-cover object-center"
                            src="{{ asset('images/user.png') }}" alt="">
                    @endif
                </div>
                <div class="flex flex-col gap-3">
                    <div>📍 {{ $order->city->name }}, {{ $order->address }}</div>
                    @if($order->payment_type == PaymentType::FIXED->value)
                        <div class="font-bold">💰 {{ number_format($order->price, 0, '.', ' ') }} руб
                            {{ $order->payment_per }}
                        </div>
                    @elseif($order->payment_type == PaymentType::DOGOVOR->value)
                        <div class="font-bold">💰 Договорная</div>
                    @endif
                    @if ($order->need_count_workers)
                        <div class="font-bold">👲 {{ $order->need_count_workers }}
                            {{ CorrectWord::getPeopleWord($order->need_count_workers) }}
                        </div>
                    @endif
                </div>
                <p class="text-base">{{ $order->content }}</p>
                <div class="flex flex-col gap-3 max-w-[320px] mb-5 max-sm:max-w-full">
                    @AuthRole('employer')
                        @if (auth()->user()->can('my_order', $order) && $order->adminOrderMessage()->exists())
                            <div class="info_message">{!! nl2br(e($order->adminOrderMessage->text)) !!}</div>
                        @endif
                        @if (auth()->user()->can('my_order', $order))
                            <a class="button-default !bg-fox" href="{{ route('orders.responses', $order->getKey()) }}">Отклики</a>
                        @endif
                        @if (auth()->user()->can('update', $order))
                            <a class="button-default" href="{{ route('employer.orders.edit', $order->getKey()) }}">Редактировать</a>
                        @endif
                        @if (auth()->user()->can('freez', $order))
                            <form onsubmit="return confirm('Вы уверены, что хотите заморозить работу?');"
                                action="{{ route('orders.freezing', $order->getKey()) }}" method="post">
                                @csrf
                                @method('PUT')
                                <button class="button-default">Заморозить</button>
                            </form>
                        @endif
                        @if (auth()->user()->can('unfreez', $order))
                            <form onsubmit="return confirm('Вы уверены, что хотите разморозить работу?');"
                                action="{{ route('orders.unfreezing', $order->getKey()) }}" method="post">
                                @csrf
                                @method('PUT')
                                <button class="button-default">Разморозить</button>
                            </form>
                        @endif
                        @if (auth()->user()->can('delete', $order))
                            <form onsubmit="return confirm('Вы уверены, что хотите удалить работу?');"
                                action="{{ route('orders.destroy', $order->getKey()) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="button-default !bg-red">Удалить</button>
                            </form>
                        @endif
                    @endAuthRole
                    @if (!auth()->user() || auth()->user()->can('create', Response::class))
                        <form action="{{ route('orders.response', $order->getKey()) }}" method="post">
                            @csrf
                            <button class="button-default !bg-fox">Откликнуться</button>
                        </form>
                    @endif
                </div>
                <div>№ {{ $order->getKey() }}</div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/libs/swiper-bundle.min.js') }}"></script>
    <script>
        const slider = document.getElementById('slider_order')
        if (slider) {
            const swiper = new Swiper(slider, {
                slidesPerView: 1.5,
                spaceBetween: 10
            });
        }
    </script>
@endpush