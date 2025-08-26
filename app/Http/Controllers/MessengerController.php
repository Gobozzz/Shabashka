<?php

namespace App\Http\Controllers;

use App\Http\Requests\Messenger\ChangeSelectedLinkedRequest;
use App\Http\Requests\Messenger\RemoveLinkedRequest;
use App\Models\LinkedMessenger;
use App\Models\Messenger;
use App\Services\LinkedMessengerService;
use App\Services\RequestLinkedMessengerService;
use Illuminate\Http\RedirectResponse;

class MessengerController extends Controller
{
    public function __construct(
        public LinkedMessengerService $linked,
        public RequestLinkedMessengerService $requestLinked
    ) {
    }
    public function createRequestLinked(Messenger $messenger): RedirectResponse
    {
        $user_id = auth()->user()->getKey();
        $messenger_id = $messenger->getKey();
        $linkedService = $this->linked->getByUserMessenger($user_id, $messenger_id);
        if ($linkedService) {
            return redirect()->back()->with('errorMessengerMessage', __('messages.messenger_already_linked'));
        }
        $requestLinked = $this->requestLinked->create($user_id, $messenger_id);
        return redirect()->back()->with('successCreateRequestMessenger', ["link_bot" => $messenger->link_bot, "name_bot" => $messenger->name_bot, 'token' => $requestLinked->token]);
    }

    public function removeLinked(RemoveLinkedRequest $request, LinkedMessenger $linkedMessenger): RedirectResponse
    {
        if ($this->linked->remove($linkedMessenger)) {
            return redirect()->back()->with('successMessengerMessage', __('messages.messenger_success_remove'));
        }
        return redirect()->back()->with('errorMessengerMessage', __('messages.messenger_error_remove'));
    }

    public function selectLinked(ChangeSelectedLinkedRequest $request, LinkedMessenger $linkedMessenger): RedirectResponse
    {
        if ($this->linked->select($linkedMessenger)) {
            return redirect()->back()->with('successMessengerMessage', __('messages.messenger_success_change'));
        }
        return redirect()->back()->with('errorMessengerMessage', __('messages.messenger_error_change'));
    }
    public function unselectLinked(ChangeSelectedLinkedRequest $request, LinkedMessenger $linkedMessenger): RedirectResponse
    {
        if ($this->linked->unselect($linkedMessenger)) {
            return redirect()->back()->with('successMessengerMessage', __('messages.messenger_success_reset'));
        }
        return redirect()->back()->with('errorMessengerMessage', __('messages.messenger_error_reset'));
    }

}
