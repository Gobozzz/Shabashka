@extends('layouts.employer')

@section('title', 'Профиль')

@section('content')
    <h1 class="page_title">{{ __('app.profile') }}</h1>
    <div class="flex flex-col gap-y-8">
        @include('includes.components.status-user')
        <div class="max-w-[320px] max-sm:max-w-full">
            <a class="button-default !bg-fox" href="{{ route('employer.orders.create') }}">{{ __('app.create_order') }}</a>
        </div>
        @include('includes.user.personal-data-profile')
        <div class="max-w-[320px] max-sm:max-w-full">
            <a class="button-default" href="{{ route('employer.profile.edit') }}">{{ __('app.change_btn') }}</a>
        </div>
        @include('includes.components.email-verify')
        <x-messenger-selected />
        @include('includes.user.web-push-notify')
        @include('includes.components.logout')
    </div>
@endsection