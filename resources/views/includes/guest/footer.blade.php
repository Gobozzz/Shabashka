<footer class="mt-16">
    <div class="px-6 py-4 bg-gray flex justify-between items-center max-lg:flex-col gap-3">
        <ul class="flex items-center gap-x-6 max-lg:flex-col gap-3">
            <li>
                <a class="text-white text-lg flex items-center gap-x-1" href="mailto:{{ config('data_app.support_email') }}">
                    <img class="lazyload" src="{{ asset('icons/email_icon.svg') }}" alt="">
                    {{ config('data_app.support_email') }}</a>
            </li>
        </ul>
        <ul class="flex items-center gap-x-6 max-lg:flex-col gap-3">
            <li>
                <a class="text-white text-lg" href="{{ route('politic') }}">{{ __('app.footer_politic') }}</a>
            </li>
            <li>
                <a class="text-white text-lg" href="{{ route('rules') }}">{{ __('app.footer_rules') }}</a>
            </li>
        </ul>
    </div>
</footer>