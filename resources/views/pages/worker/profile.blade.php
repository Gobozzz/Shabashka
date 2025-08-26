@extends('layouts.worker')

@section('title', 'Профиль')

@section('content')
    <h1 class="page_title">{{ __('app.profile') }}</h1>
    <div class="flex flex-col gap-y-8">
        @include('includes.components.status-user')
        @include('includes.user.personal-data-profile')
        <div class="max-w-[320px] max-sm:max-w-full">
            <a class="button-default" href="{{ route('worker.profile.edit') }}">{{ __('app.change_btn') }}</a>
        </div>
        @include('includes.components.email-verify')
        <x-messenger-selected />
        @include('includes.user.web-push-notify')
        @include('includes.components.logout')
    </div>
@endsection