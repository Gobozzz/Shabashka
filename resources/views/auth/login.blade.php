@extends('layouts.guest')

@section('content')
    <section class="pt-56 max-lg:pt-40">
        <div class="container-custom">
            <form action="{{ route('login.store') }}" method="post" class="form">
                @csrf
                @include('includes.components.input', ['name' => 'login', 'placeholder' => __('auth.login_placeholder')])
                @include('includes.components.input', ['name' => 'password', 'id' => 'login_password_input', 'type' => 'password', 'placeholder' => "******"])
                <button class="button-default">{{ __('auth.login_btn_send') }}</button>
                <a class="inline-block" href="{{ route('password.request') }}">{{ __('auth.forgot_password') }}</a>
                <div class="text-center">
                    <div class="mb-2 flex flex-col gap-1">
                        <a href="{{ route('auth.redirect', 'google') }}">Войти через Google</a>
                    </div>
                    <div class="text-sm text-gray mb-4">Если у вас нет аккаунта, вы будете зарегистрированы как "Работник", для
                        выбора роли перейдите в раздел <a class="text-blue" href="{{ route('register') }}">"Регистрация"</a>
                        и выберите подходящую роль, после чего вы можете также войти через удобный вам сервис
                    </div>
                    <div class="text-sm text-gray">
                        Регистрируясь в данном сервисе, вы подтверждаете, что ознакомлены и соглашаетесь с <a
                            href="{{ route('politic') }}">Политикой конфиденциальности</a> и <a
                            href="{{ route('rules') }}">Правилами сервиса</a>.
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/functions/password_input.js') }}"></script>
    <script>
        set_password_input_eye('login_password_input')
    </script>
@endpush