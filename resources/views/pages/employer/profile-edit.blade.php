@extends('layouts.employer')

@section('title', 'Редактирование профиля')

@push('scripts')
    <script src="{{ asset('js/functions/password_input.js') }}"></script>
@endpush

@section('content')
    <h1 class="page_title">{{ __('app.profile_edit') }}</h1>
    <div class="flex flex-col gap-y-8">
        @include('includes.components.status-user')
        @include('includes.user.personal-data-edit')
        @include('includes.user.password-edit')
    </div>
@endsection