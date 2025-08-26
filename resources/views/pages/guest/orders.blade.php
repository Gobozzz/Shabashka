@extends('layouts.guest')

@section('title', 'Заказы — сервис Шабашка')
@section('description', 'На странице Заказы в сервисе Шабашка вы можете просматривать, управлять и отслеживать все ваши текущие и завершённые заказы. Быстро находите нужную информацию и удобно взаимодействуйте с исполнителями.')

@section('content')
    <section class="pt-44 max-lg:pt-20">
        <div class="container-custom">
            <h1 class="font-bebas text-6xl mb-8 max-lg:text-4xl">Работа для всех</h1>
            @include('includes.components.session-success-or-error')
            <button id="change_view_filters_button" class="button-default max-w-[320px] mb-5">Фильтры</button>
            <form method="GET" class="mb-11 hidden" id="filters_block">
                <div class="mb-3">
                    <div class="text-lg mb-1">Город</div>
                    @php
                        $old_city_id = request()->input('city_id', auth()->user()?->city?->id);
                    @endphp
                    <select class="select-default max-w-[320px]" name="city_id">
                        <option value="">-</option>
                        @foreach ($filters['cities'] as $city)
                            <option @selected($old_city_id == $city->id) value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <div class="text-lg mb-1">Категории работ</div>
                    <div
                        class="flex items-start gap-4 flex-wrap bg-light-gray p-4 rounded-xl max-h-50 overflow-y-auto mb-4 mt-1">
                        @php
                            $checked_category_work = request()->input('categories_selected', []);
                        @endphp
                        @foreach ($filters['work_categories'] as $category_work)
                            <label
                                class="py-1 px-3 bg-black text-white rounded-lg flex items-center gap-4 cursor-pointer min-h-11">
                                {{ $category_work->name }}
                                <input @checked(in_array($category_work->id, $checked_category_work)) class="accent-fox w-5 h-5"
                                    type="checkbox" name="categories_selected[]" value="{{ $category_work->id }}" />
                            </label>
                        @endforeach
                    </div>
                </div>
                <button class="button-default max-w-[320px] mb-4">Применить</button>
                <a href="{{ route('orders') }}" class="button-default max-w-[320px] !bg-blue">Сбросить</a>
            </form>
            <div class="my-4">
                {{ $orders->links() }}
            </div>
            <div class="cards_wrapper">
                @foreach ($orders as $order)
                    @include('includes.components.card-order', ['order_data' => $order])
                @endforeach
            </div>
            <div class="my-4">
                {{ $orders->links() }}
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const filters_block = document.getElementById('filters_block')
        const change_view_filters_button = document.getElementById('change_view_filters_button')
        change_view_filters_button.onclick = function () {
            $(filters_block).slideToggle(300)
        }
    </script>
@endpush