@extends('layouts.guest')

@section('content')
    <section class="pt-56">
        <div class="container-custom">
            <form class="form" method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->token }}">
                @session('status')
                    <p>{{ $value }}</p>
                @endsession
                @include('includes.components.input', ['name' => 'email', 'placeholder' => __('auth.email_placeholder'), 'type' => 'email', 'default_value' => $request->email])
                @include('includes.components.input', ['name' => 'password', 'id' => 'new_password', 'placeholder' => __('auth.new_password_placeholder'), 'type' => 'password'])
                @include('includes.components.input', ['name' => 'password_confirmation', 'id' => 'new_password_confirmation', 'placeholder' => __('auth.password_confirmed_placeholder'), 'type' => 'password'])
                <button class="button-default">{{ __('auth.reset_send_btn') }}</button>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/functions/password_input.js') }}"></script>
    <script>
        set_password_input_eye('new_password')
        set_password_input_eye('new_password_confirmation')
    </script>
@endpush