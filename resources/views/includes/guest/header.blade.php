@use('App\Actions\User\GetUrlLkForUser')

<button id="mobile_menu_btn"
    class="fixed z-3 top-4 right-4 w-11 h-11 bg-black rounded-xl flex-col justify-center items-center gap-2 hidden max-lg:flex">
    <div class="w-[65%] h-0.5 bg-white rouned-xl"></div>
    <div class="w-[65%] h-0.5 bg-white rouned-xl"></div>
    <div class="w-[65%] h-0.5 bg-white rouned-xl"></div>
</button>

<header id="header" class="fixed z-3 shadow-lg rounded-b-2xl -translate-y-full left-1/2 -translate-x-1/2 transition-transform duration-500 top-0 w-full max-w-[1440px] max-[1460px]:max-w-[calc(100%-20px)] bg-white flex justify-between items-center p-5
    max-lg:w-[450px] max-lg:h-[100vh] max-lg:right-0 max-lg:flex-col max-lg:justify-start max-lg:left-auto
    max-lg:rounded-[0px] max-lg:translate-y-0 max-lg:translate-x-[120%]
    max-sm:w-[270px] max-lg:overflow-y-auto
    ">
    <a href="{{ route('welcome') }}" class="font-bebas text-5xl max-lg:mb-5">{{ __('app.name') }}</a>
    <nav class="max-lg:w-full">
        <ul class="flex items-center gap-x-6 max-lg:flex-col max-lg:gap-3 max-lg:items-stretch max-lg:w-full">
            <li
                class="block text-xl py-1 px-2 rounded-lg bg-fox text-white max-lg:p-0">
                <a class="max-lg:w-full max-lg:h-11 max-lg:flex max-lg:items-center max-lg:justify-center max-sm:text-base" href="{{ route('orders') }}">{{ __('app.orders') }}</a>
            </li>
            <li
                class="block text-xl py-1 px-2 rounded-lg bg-fox text-white max-lg:p-0">
                <a class="max-lg:w-full max-lg:h-11 max-lg:flex max-lg:items-center max-lg:justify-center max-sm:text-base" href="{{ route('about') }}">{{ __('app.header_about') }}</a>
            </li>
            <li
                class="block text-xl py-1 px-2 rounded-lg bg-fox text-white max-lg:p-0">
                <a class="max-lg:w-full max-lg:h-11 max-lg:flex max-lg:items-center max-lg:justify-center max-sm:text-base" href="{{ route('how-search-work') }}">{{ __('app.header_how_search_work') }}</a>
            </li>
            <li>
                <div class="flex flex-col items-center bg-my-red gap-y-3 max-lg:w-full">
                    @guest
                        <a class="text-xl py-1 px-2 rounded-lg bg-black text-white max-lg:w-full max-lg:h-11 max-lg:flex max-lg:items-center max-lg:justify-center max-sm:text-base"
                            href="{{ route('login') }}">{{ __('app.header_login') }}</a>
                        <a class="text-xl py-1 px-2 rounded-lg bg-black text-white max-lg:w-full max-lg:h-11 max-lg:flex max-lg:items-center max-lg:justify-center max-sm:text-base"
                            href="{{ route('register') }}">{{ __('app.header_register') }}</a>
                    @endguest
                    @auth
                        <a class="text-xl py-1 px-2 rounded-lg bg-black text-white max-lg:w-full max-lg:h-11 max-lg:flex max-lg:items-center max-lg:justify-center max-sm:text-base"
                            href="{{ url((new GetUrlLkForUser())->handle(auth()->user())) }}">{{ __('app.header_profile') }}</a>
                    @endauth
                </div>
            </li>
        </ul>
    </nav>
</header>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const header = document.getElementById('header');
            setTimeout(() => {
                header.classList.remove('-translate-y-full')
            }, 500)
            const mobile_menu_btn = document.getElementById('mobile_menu_btn');
            mobile_menu_btn.addEventListener('click', function () {
                if (!header.classList.contains('header_mobile_menu_active')) {
                    header.classList.add('header_mobile_menu_active')
                    setTimeout(() => {
                        document.addEventListener('click', hide_mobile_menu)
                    }, 100)
                }
            })

            function hide_mobile_menu(event) {
                if (!header.contains(event.target)) {
                    header.classList.remove('header_mobile_menu_active')
                    document.removeEventListener('click', hide_mobile_menu);
                }
            }

        })
    </script>
@endpush