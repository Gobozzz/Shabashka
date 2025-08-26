@extends('layouts.guest')

@section('content')
    <section class="pt-56">
        <div class="container-custom">
            <form class="form" method="POST" action="{{ route('password.email') }}">
                @csrf
                @session('status')
                    <p class="text-base">{{ $value }}</p>
                @endsession
                @include('includes.components.input', ['name' => 'email', 'placeholder' => __('auth.email_placeholder'), 'type' => 'email'])
                <button class="button-default">{{ __('auth.forgot_send_btn') }}</button>
            </form>
        </div>
    </section>
@endsection