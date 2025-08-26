@extends('layouts.guest')

@section('title', 'Как найти работу — сервис Шабашка')
@section('description', 'Узнайте, как легко и быстро найти подходящие заказы на платформе Шабашка. Следуйте нашим рекомендациям и шагам, чтобы начать зарабатывать уже сегодня и успешно реализовать свои навыки.')

@section('content')
    <section class="pt-44 max-lg:pt-20">
        <div class="container-custom">
            <h1 class="font-bebas text-6xl mb-8 max-lg:text-4xl">{{ __('how_search_work_page.title') }}</h1>
            <div class="flex items-start justify-between gap-16 max-lg:flex-col">
                <div class="flex-1/2">
                    <p class="text-lg mb-4">{{ __('how_search_work_page.text') }}</p>
                    <ul class="list-disc pl-4 flex flex-col gap-y-2">
                        {!! __('how_search_work_page.text_points') !!}
                    </ul>
                </div>
                <div class="flex-1/2 aspect-square">
                    <img class="w-full h-full object-cover object-center rounded-tl-[240px] rounded-br-[180px] rounded-tr-[80px] rounded-bl-[40px]"
                        src="{{ asset('images/how-search-work.webp') }}" alt="">
                </div>
            </div>
        </div>
    </section>
@endsection