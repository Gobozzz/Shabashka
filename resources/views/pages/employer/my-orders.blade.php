@extends('layouts.employer')

@section('title', __('app.my_orders'))

@section('content')
    <h1 class="page_title">{{ __('app.my_orders') }}</h1>
    <section>
        <div class="max-w-[320px] mb-4">
            <a class="button-default !bg-fox" href="{{ route('employer.orders.create') }}">{{ __('app.create_order') }}</a>
        </div>
        @include('includes.components.session-success-or-error')
        <div>
            <div class="my-4">
                {{ $orders->links() }}
            </div>
            <div class="cards_wrapper max-w-[1200px]">
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