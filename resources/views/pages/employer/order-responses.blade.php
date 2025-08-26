@extends('layouts.employer')

@section('title', "Мои отклики")

@section('content')
    <h1 class="page_title">Отклики</h1>
    <div class="text-lg mb-5">Объявление №{{ $order->id }}: <b>{{ $order->title }}</b></div>
    <section>
        @include('includes.components.session-success-or-error')
        <div>
            <div class="my-4">
                {{ $responses->links() }}
            </div>
            @if ($responses->count() == 0)
                <div class="info_message" >На ваше объявление пока нет откликов</div>
            @endif
            <div class="flex flex-col gap-4 max-w-[1200px]">
                @foreach ($responses as $response)
                    @include('includes.components.card-response', ['response', $response])
                @endforeach
            </div>
            <div class="my-4">
                {{ $responses->links() }}
            </div>
        </div>
    </section>
@endsection