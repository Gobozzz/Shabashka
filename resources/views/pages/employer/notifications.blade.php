@extends('layouts.employer')

@section('title', 'Уведомления')

@section('content')
    <h1 class="page_title">{{ __('app.notifications') }}</h1>
    <section>
        <div class="max-w-[320px] mb-11">
            <a class="button-default !bg-fox" href="{{ route('employer.orders.create') }}">{{ __('app.create_order') }}</a>
        </div>
        @include('includes.components.session-success-or-error')
        <h2 class="mb-5">{{ __('app.notifications_worker_title') }}</h2>
        <div class="mb-11">
            <x-messenger-selected />
        </div>
        <div class="mb-11">
            @include('includes.user.web-push-notify')
        </div>
    </section>
@endsection