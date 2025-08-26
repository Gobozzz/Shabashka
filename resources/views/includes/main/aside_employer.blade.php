<aside id="aside_block"
    class="fixed overflow-y-auto top-0 left-0 z-20 w-[var(--width-aside)] h-[100vh] bg-white rounded-tr-lg shadow rounded-br-lg p-2
    max-lg:left-auto max-lg:right-0 max-lg:rounded-0 max-lg:transition-transform duration-300 max-lg:translate-x-[120%] max-lg:w-[250px]">
    <a class="aside_logo block max-w-[80%] mx-auto mb-12" href="{{ route('welcome') }}">
        <div class="w-full h-0.5 bg-black"></div>
        <div class="text-lg text-center uppercase">{{ __('app.name') }}</div>
        <div class="w-full h-0.5 bg-black"></div>
    </a>
    <nav>
        <ul class="flex flex-col gap-y-4 text-center">
            <li>
                <a class="aside_point @activeMenu('*profile*')"
                    href="{{ route('employer.profile') }}">{{ __('app.profile') }}</a>
            </li>
            <li>
                <a class="aside_point @activeMenu('*notifications*')"
                    href="{{ route('employer.notifications') }}">{{ __('app.notifications') }}</a>
            </li>
            <li>
                <a class="aside_point @activeMenu('*orders*')"
                    href="{{ route('employer.orders') }}">{{ __('app.my_orders') }}</a>
            </li>
            <li>
                <a class="aside_point" href="{{ route('orders') }}">{{ __('app.orders') }}</a>
            </li>
        </ul>
    </nav>
</aside>

<button id="mobile_menu_btn"
    class="fixed z-19 top-4 right-4 w-11 h-11 bg-black rounded-xl flex-col justify-center items-center gap-2 hidden max-lg:flex">
    <div class="w-[65%] h-0.5 bg-white rouned-xl"></div>
    <div class="w-[65%] h-0.5 bg-white rouned-xl"></div>
    <div class="w-[65%] h-0.5 bg-white rouned-xl"></div>
</button>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const aside = document.getElementById('aside_block');
            const mobile_menu_btn = document.getElementById('mobile_menu_btn');
            mobile_menu_btn.addEventListener('click', function () {
                if (!aside.classList.contains('header_mobile_menu_active')) {
                    aside.classList.add('header_mobile_menu_active')
                    setTimeout(() => {
                        document.addEventListener('click', hide_mobile_menu)
                    }, 100)
                }
            })

            function hide_mobile_menu(event) {
                if (!aside.contains(event.target)) {
                    aside.classList.remove('header_mobile_menu_active')
                    document.removeEventListener('click', hide_mobile_menu);
                }
            }

        })
    </script>
@endpush