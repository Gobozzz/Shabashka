@use('App\Enums\RolesUser')
@extends('layouts.guest')

@section('content')
    <section class="pt-56 max-lg:pt-22">
        <div class="container-custom">
            <form action="{{ route('register.store') }}" method="post" class="form">
                @csrf
                <div>
                    <select id="role_select" class="select-default" name="role">
                        <option @selected(old('role') == RolesUser::WORKER->value) value="{{ RolesUser::WORKER->value }}">
                            {{ RolesUser::translate(RolesUser::WORKER->value) }}
                        </option>
                        <option @selected(old('role') == RolesUser::EMPLOYER->value) value="{{ RolesUser::EMPLOYER->value }}">
                            {{ RolesUser::translate(RolesUser::EMPLOYER->value) }}
                        </option>
                    </select>
                    @include('includes.components.error-input', ['name' => "role"])
                </div>
                @include('includes.components.input', ['name' => 'name', 'placeholder' => __('auth.name_placeholder')])
                @include('includes.components.input', ['name' => 'email', 'type' => "email", 'placeholder' => __('auth.email_placeholder')])
                @include('includes.components.input', ['name' => 'phone', 'type' => "tel", 'placeholder' => __('auth.phone_placeholder')])
                @include('includes.components.input', ['name' => 'password', 'id' => 'register_password_input', 'type' => 'password', 'placeholder' => __('auth.password_placeholder')])
                @include('includes.components.input', ['name' => 'password_confirmation', 'id' => 'register_password_confirmed_input', 'type' => 'password', 'placeholder' => __('auth.password_confirmed_placeholder')])
                <button class="button-default">{{ __('auth.register_btn_send') }}</button>
                <div class="text-center">
                    <div class="mb-2 flex flex-col gap-1">
                        <a class="social_link" href="{{ route('auth.redirect', 'google') }}">Войти через Google</a>
                    </div>
                    <div class="text-sm text-gray mb-2">
                        Если у вас нет аккаунта, вы будете зарегистрированы как "Работник", выберите подходящую роль в
                        списке выше, после чего вы можете также
                        войти через удобный вам сервис
                    </div>
                    <div class="text-sm text-gray">
                        Регистрируясь в данном сервисе, вы подтверждаете, что ознакомлены и соглашаетесь с <a href="{{ route('politic') }}">Политикой конфиденциальности</a> и <a href="{{ route('rules') }}">Правилами сервиса</a>.
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/functions/password_input.js') }}"></script>
    <script>
        set_password_input_eye('register_password_input')
        set_password_input_eye('register_password_confirmed_input')

        const role_select = document.getElementById('role_select');
        const social_links = Array.from(document.querySelectorAll('.social_link'))

        function changeRoleLinkSocial() {
            if (social_links.length > 0) {
                social_links.map(link => {
                    const base_url_link = link.href.split('?')[0];
                    link.href = `${base_url_link}?role=${role_select.value}`;
                })
            }
        }
        changeRoleLinkSocial()
        role_select.onchange = changeRoleLinkSocial
    </script>
@endpush