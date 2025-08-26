<section>
    <form class="max-w-[320px] max-sm:max-w-full" action="{{ route('logout') }}" method="post">
        @csrf
        <button class="button-default">{{ __('auth.logout') }}</button>
    </form>
</section>