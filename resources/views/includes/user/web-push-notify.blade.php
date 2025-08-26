@php
    $user = auth()->user();
@endphp
<div>
    <div>
        @if (!$user->pushSubscriptions()->exists())
            <div class="info_message my-2">
                Мы используем PUSH уведомления только в целях оповещения о вашей работе, не для рекламных целей.
            </div>
            <button class="button-default !bg-fox max-w-[320px]" onclick="requestPermissionPush()">Подписаться на
                PUSH-уведомления</button>
        @else
            <button class="button-default !bg-gray max-w-[320px]"
                onclick="remove_subscribe_push('{{ $user->pushSubscriptions[0]->endpoint }}')">Отписаться от
                PUSH-уведомлений</button>
        @endif
    </div>
</div>
@push('scripts')
    <script src="{{ asset('js/push/functions.js') }}"></script>
@endpush