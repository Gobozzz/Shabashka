@use('App\Actions\User\IsUserActive')
@use('App\Actions\User\GetListErrorsUserActiveStatus')
@php
    $user = auth()->user();
    $is_active = (new IsUserActive())->handle($user);
    if (!$is_active) {
        $errors_active = (new GetListErrorsUserActiveStatus())->handle($user);
    }
@endphp
<div class="flex flex-col gap-y-2">
    @if ($is_active)
        <div class="success_message">{{ __('statuses.profile_ok') }}</div>
    @else
        @foreach ($errors_active as $error)
            <div class="error_message">{{ $error }}</div>
        @endforeach
    @endif
    @if($user->adminMessage()->exists())
        <div class="info_message">{!! nl2br(e($user->adminMessage->text)) !!}</div>
    @endif
</div>