@use('App\Enums\PaymentType')
@extends('layouts.employer')

@section('title', 'Публикация работы')

@section('content')
    <h1 class="page_title">{{ __('app.create_order') }}</h1>
    <section>
        @include('includes.components.session-success-or-error')
        <form action="{{ route('orders.store') }}" method="post" class="max-w-[600px]">
            @csrf
            @if ($errors->any())
                <div class="error_message mb-2">Были выявлены ошибки</div>
            @endif
                <div class="flex flex-col gap-8">
                    @include('includes.components.input', [
                        'name' => "title",
                        'placeholder' => "Поднять ламинат, выгрузить мебель, ...",
                        "title" => "Название работы",
                    ])
                    @include('includes.components.textarea', [
                        'name' => "content",
                        'placeholder' => "Расскажите подробнее о работе...",
                        "title" => "Описание работы",
                    ])
                    <div>
                        <div class="input_title">Город</div>
                        <select name="city_id" class="select-default">
                            @foreach ($cities as $city)
                                <option @selected($city->id == old('city_id', auth()->user()->city_id)) value="{{ $city->getKey() }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @include('includes.components.input', [
                        'name' => "address",
                        'placeholder' => "Улица, номер дома, ...",
                        "title" => "Адрес",
                    ])
                    <div>
                        <div class="input_title">Оплата</div>
                        <div class="flex items-start gap-4 mb-4">
                            @foreach (PaymentType::cases() as $key => $value)
                                <label class="cursor-pointer text-lg inline-flex items-center gap-2">
                                    {{ $value }}
                                    <input @checked($value->value === old('payment_type', PaymentType::FIXED->value)) name="payment_type" value="{{ $value->value }}" onchange="update_visible_payments_type_more()" class="accent-black w-5 h-5 payment_type" type="radio" />
                                </label>
                            @endforeach
                        </div>
                        <div>
                            <div class="payment_type_fixed_more payment_type_more">
                                <div class="flex flex-col gap-4">
                                    @include('includes.components.input', [
                                        'name' => "price",
                                        'placeholder' => "1000",
                                        "title" => "Обьём оплаты, руб",
                                        'type' => "number",
                                    ])
                                    <div>
                                        <div class="input_title">За что платите? *</div>
                                        @include('includes.components.error-input', ['name' => 'payment_per'])
                                        <select name="payment_per" class="select-default">
                                            @foreach ($payment_pers as $payment_per)
                                                <option @selected($payment_per->getKey() == old('payment_per')) value="{{ $payment_per->getKey() }}">{{ $payment_per->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="input_title">Категории (максимум {{ config('data_app.max_count_selected_categories_for_new_order') }}) *</div>
                        @include('includes.components.error-input', ['name' => 'categories_selected'])
                        <div class="flex items-start gap-4 flex-wrap bg-gray p-4 rounded-xl max-h-50 overflow-y-auto mb-4 mt-1">
                            @foreach ($categories_works as $category_work)
                                <label class="py-1 px-3 bg-black text-white rounded-lg flex items-center gap-4 cursor-pointer min-h-11">
                                    {{ $category_work->name }}
                                    <input class="accent-fox w-5 h-5" type="checkbox" name="categories_selected[]"
                                        value="{{ $category_work->id }}" @checked(in_array($category_work->id, old('categories_selected', []))) />
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @include('includes.components.input', [
                        'name' => "need_count_workers",
                        'placeholder' => "1",
                        "title" => "Количество человек (необязательно)",
                        'type' => "number",
                    ])
                    <div>
                        <div class="input_title">Фото (необязательно)</div>
                        <div class="button-default relative mb-4">
                            <input multiple type="file" id="images_input_file" class="absolute top-0 left-0 w-full h-full opacity-0" >
                                Выбрать фото
                        </div>
                        <div class="many_images_container" id="images_container">
                            @if (old('images'))
                                @foreach (old('images') as $image)
                                    <div class="many_images_item">
                                        <input type="hidden" name="images[]" value="{{ $image }}">
                                        <img src="{{ asset("storage/$image") }}" alt="">
                                        <div class="many_images_item_action">
                                            <div onclick="delete_image(this)" class="many_images_item_action_btn">
                                                <img src="{{ asset('icons/delete.svg') }}" alt="">
                                            </div>
                                            <div class="flex items-end gap-2">
                                                <div onclick="replace_image(this, DIRECTION_LEFT)" class="many_images_item_action_btn !bg-black">
                                                    <img src="{{ asset('icons/arrow_left.svg') }}" alt="">
                                                </div>
                                                <div onclick="replace_image(this, DIRECTION_RIGHT)" class="many_images_item_action_btn !bg-black rotate-180">
                                                    <img src="{{ asset('icons/arrow_left.svg') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <button class="button-default !bg-fox">Опубликовать</button>
                </div>
            </form>
        </section>
@endsection

@push('scripts')
    <script>
        const FIXED_PAYMENT = "{{ PaymentType::FIXED->value }}";
    </script>
    <script src="{{ asset('js/pages/create-order/payment_types.js') }}"></script>
    <script src="{{ asset('js/pages/create-order/images.js') }}"></script>
@endpush