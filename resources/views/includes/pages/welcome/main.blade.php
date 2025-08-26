<section class="w-full h-[100vh] flex items-center mb-16" id="main">
    <img class="absolute top-0 left-0 w-full h-full object-cover object-center" src="{{ asset('images/welcome.jpg') }}"
        alt="">
    <div class="relative z-2 ml-[10vw] max-lg:ml-2.5">
        <h2
            class="welcome_text opacity-0 translate-y-12 transition-all duration-500 delay-500 font-bebas text-white [font-size:clamp(3.438rem,_2.525rem_+_4.56vw,_8rem)] [line-height:clamp(3.438rem,_2.525rem_+_4.56vw,_8rem)]">
            {!! __('welcome_page.title') !!}
        </h2>
        <div
            class="welcome_text opacity-0 translate-y-12 transition-all duration-500 delay-500 font-bebas text-white [font-size:clamp(3.125rem,_2.15rem_+_4.88vw,_8rem)] [line-height:clamp(3.125rem,_2.15rem_+_4.88vw,_8rem)]">
            {!! __('welcome_page.subtitle') !!}
        </div>
    </div>
</section>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const welcome_texts = Array.from(document.querySelectorAll('.welcome_text'));
            if (welcome_texts) {
                welcome_texts.map(text => {
                    text.classList.remove('opacity-0', 'translate-y-12')
                })
            }
        })
    </script>
@endpush