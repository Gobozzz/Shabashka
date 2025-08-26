<section>
    <div class="container-custom">
        <h2 class="font-bebas text-6xl mb-8 max-lg:text-4xl">{{ __('welcome_page.title_who_need') }}</h2>
        <div class="flex items-start justify-between gap-16 max-lg:flex-col">
            <div class="flex-1/2">
                <p class="text-lg mb-4">
                    {!! __('welcome_page.text_who_need') !!}
                </p>
                <ul class="list-disc pl-4 flex flex-col gap-y-2">{!! __('welcome_page.title_who_need_points') !!}</ul>
            </div>
            <div class="flex-1/2 aspect-square">
                <img class="lazyload w-full h-full object-cover object-center rounded-tl-[240px] rounded-br-[180px] rounded-tr-[80px] rounded-bl-[40px]"
                    src="{{ asset('images/welcome_about.webp') }}" alt="">
            </div>
        </div>
    </div>
</section>