@if (!auth()->user()->hasVerifiedEmail())
    <section>
        @if (session('status') && session('status') === 'verification-link-sent')
            <p class="success_message mb-2">{{ __('auth.verification_email_send_success') }}</p>
        @endif
        <p class="text-base mb-2">{{ __('auth.verification_email_send_need') }}</p>
        <form class="max-w-[320px]" action="{{ route('verification.send') }}" method="post">
            @csrf
            <button class="button-default">{{ __('auth.verification_email_send_get_btn') }}</button>
        </form>
    </section>
@endif