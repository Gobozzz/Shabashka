@extends('layouts.guest')

@section('title', 'О нас — сервис Шабашка')
@section('description', 'Узнайте больше о сервисе Шабашка — нашей миссии, команде и ценностях. Мы создаем удобную платформу для поиска и выполнения различных заказов, чтобы сделать ваш опыт максимально простым и надежным.')

@section('content')
    <section class="pt-44 max-lg:pt-20">
        <div class="container-custom">
            <h1 class="font-bebas text-6xl mb-8 max-lg:text-4xl">{{ __('about_page.title') }}</h1>
            <div class="flex items-start justify-between gap-16 max-lg:flex-col">
                <div class="flex-1/2">
                    <p class="text-lg  mb-4">{!! __('about_page.text') !!}</p>
                </div>
                <div class="flex-1/2 aspect-square">
                    <img class="w-full h-full object-cover object-center rounded-tl-[240px] rounded-br-[180px] rounded-tr-[80px] rounded-bl-[40px]"
                        src="{{ asset('images/about-image.jpeg') }}" alt="">
                </div>
            </div>
        </div>
    </section>
@endsection