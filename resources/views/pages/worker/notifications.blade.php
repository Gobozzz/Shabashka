@extends('layouts.worker')

@section('title', 'Уведомления')

@section('content')
    <h1 class="page_title">{{ __('app.notifications') }}</h1>
    <section>
        @include('includes.components.session-success-or-error')
        <div class="info_message mb-6">
            Важно! Уведомления о заказах можно получить только, если вы указали город и подписались на нужные вам категории.
        </div>
        <div class="mb-6">
            <h2 class="mb-4">{{ __('app.notifications_worker_title') }}</h2>
            <x-messenger-selected />
        </div>
        <div class="mb-11">
            @include('includes.user.web-push-notify')
        </div>
        <h2 class="mb-4">
            {{ __('app.notifications_categories_workers', ['count' => config('data_app.max_count_selected_categories_for_notify')]) }}
        </h2>
        <form action="{{ route('worker.updateSubscriptionWorkCategories') }}" method="post">
            @csrf
            @method('PUT')
            @include('includes.components.error-input', ['name' => 'categories_selected'])
            @php
                $old_sub_categories_works = old('categories_selected', $subscription_categories_works);
            @endphp
            <div class="flex items-start gap-4 flex-wrap bg-light-gray p-4 rounded-xl max-h-50 overflow-y-auto mb-4 mt-1">
                @foreach ($categories_works as $category_work)
                    <label class="py-1 px-3 bg-black text-white rounded-lg flex items-center gap-4 cursor-pointer min-h-11">
                        {{ $category_work->name }}
                        <input class="accent-fox w-5 h-5" type="checkbox" name="categories_selected[]"
                            value="{{ $category_work->id }}" @checked(in_array($category_work->id, $old_sub_categories_works)) />
                    </label>
                @endforeach
            </div>
            <div class="max-w-[320px]">
                <button class="button-default">Выбрать</button>
            </div>
        </form>
    </section>
@endsection