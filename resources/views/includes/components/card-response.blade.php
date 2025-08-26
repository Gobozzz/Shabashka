@use('Carbon\Carbon')
<div class="bg-light-gray px-3 py-2 rounded-xl flex justify-between items-center">
    <div class="flex flex-col gap-1">
        <div class="text-base">Имя: {{ $response->worker->user->name }}</div>
        @if ($response->worker->user->phone)
            <div class="text-base">Телефон: <a href="tel:{{ $response->worker->user->phone }}">{{ $response->worker->user->phone }}</a></div>
        @endif
        <div class="text-base">Почта: <a href="mailto:{{ $response->worker->user->email }}">{{ $response->worker->user->email }}</a></div>
        <div class="text-base">Дата отклика: {{ Carbon::parse($response->updated_at)->translatedFormat('d F H:i') }}</div>
    </div>
    @if ($response->worker->user->phone)
        <a href="tel:{{ $response->worker->user->phone }}">
            <img class="min-w-14 max-w-14" src="{{ asset('icons/phone.png') }}" alt="">
        </a>
    @endif
</div>