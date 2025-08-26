<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="@yield('description', 'Ищете временных работников или хотите подработать? Шабашка — ваш надежный партнер для быстрого поиска и найма временных сотрудников. Удобно, просто и выгодно!')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('fonts/Bebas/bebasneue.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/Roboto/roboto.css') }}">
    @stack('styles')
    @stack('scripts_head')
    <title>@yield('title', 'Шабашка — Быстрый и надежный сервис по найму временных работников')</title>
</head>

<body>
    <div class="flex flex-col justify-between min-h-[100vh] pr-2.5 pl-6 py-5 ml-[var(--width-aside)] max-lg:ml-0 max-lg:pl-2.5">
        @include('includes.main.aside_employer')
        <div class="max-lg:pt-20">
            @yield('content')
        </div>
        @include('includes.main.footer')
    </div>
    <script src="{{ asset('js/libs/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/config.js') }}"></script>
    <script src="{{ asset('js/libs/lazysizes.min.js') }}"></script>
    @stack('scripts')
</body>

</html>