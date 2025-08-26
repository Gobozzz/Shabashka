@session('errorMessengerMessage')
    <div class="error_message mb-2">{{ $value }}</div>
@endsession
@session('successMessengerMessage')
    <div class="success_message mb-2">{{ $value  }}</div>
@endsession
@session('successCreateRequestMessenger')
    <div>
        <div class="success_message mb-2">{{ __('messages.messenger_success_request', ['token' => $value['token']]) }}</div>
        <div class="success_message mb-2"><a target="_blank"
                href="{{ $value['link_bot'] }}">{{ __('messages.messenger_success_request_btn', ['name' => $value['name_bot']]) }}</a>
        </div>
    </div>
@endsession
<div class="flex items-stretch gap-12 flex-wrap max-sm:gap-6">
    @foreach ($messengers as $messenger)
        <div class="w-32 flex flex-col gap-2 items-center justify-between max-sm:w-24">
            <div>
                <img class="w-full rounded-lg aspect-square object-cover object-center mb-2"
                    src="{{ asset("storage/{$messenger->image}") }}" alt="">
                <div class="text-lg text-center">{{ $messenger->name }}</div>
            </div>
            @if ($messenger->linked)
                @if ($messenger->linked->is_selected)
                    <form class="w-full" action="{{ route('messengers.unselect.linked', $messenger->linked->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <button class="button-default !bg-gray" type="submit" class="">{{ __('app.reset') }}</button>
                    </form>
                @else
                    <form class="w-full" action="{{ route('messengers.select.linked', $messenger->linked->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <button class="button-default !bg-blue" type="submit" class="">{{ __('app.select') }}</button>
                    </form>
                @endif
                <form class="w-full" method="post"
                    action="{{ route('messengers.remove.linked', $messenger->linked->getKey()) }}">
                    @csrf
                    <button class="button-default !bg-fox">{{ __('app.unlinked') }}</button>
                </form>
            @else
                <form class="w-full" method="post" action="{{ route('messengers.create.request', $messenger) }}">
                    @csrf
                    <button class="button-default !bg-fox">{{ __('app.linked') }}</button>
                </form>
            @endif
        </div>
    @endforeach
</div>