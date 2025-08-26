<form class="form !mx-0 mb-6" action="{{ route('change.password') }}" method="post">
    @csrf
    @session('success-update-password')
        <div class="success_message">{{ $value }}</div>
    @endsession
    @session('error-update-password')
        <div class="error_message">{{ $value }}</div>
    @endsession
    @include('includes.components.input', [
        'id' => 'old_password',
        'name' => 'old_password',
        'type' => 'password',
        'placeholder' => __('auth.old_password_placeholder'),
    ])
    @include('includes.components.input', [
        'id' => 'new_password',
        'name' => 'new_password',
        'type' => 'password',
        'placeholder' => __('auth.new_password_placeholder'),
    ])
    @include('includes.components.input', [
        'id' => 'new_password_confirmation',
        'name' => 'new_password_confirmation',
        'type' => 'password',
        'placeholder' => __('auth.password_confirmed_placeholder'),
    ])
    <button class="button-default">{{ __('app.apply') }}</button>
</form>

@push('scripts')
    <script>
        set_password_input_eye('old_password')
        set_password_input_eye('new_password')
        set_password_input_eye('new_password_confirmation')
    </script>
@endpush