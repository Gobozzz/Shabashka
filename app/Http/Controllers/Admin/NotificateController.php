<?php

namespace App\Http\Controllers\Admin;

use App\Actions\User\GetLinkedMessengerUser;
use App\Contracts\Messengers\TelegramInterface;
use App\Http\Controllers\Controller;
use App\Jobs\SendNotificationUsers;
use App\Models\User;
use App\Notifications\Admin\SendMessageUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificateController extends Controller
{
    public function __construct(
        public TelegramInterface $telegram,
        public GetLinkedMessengerUser $getLinked
    ) {
    }

    public function sendMessageAllUsers(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'text' => "required|string"
        ]);
        User::select('id')->chunk(100, function ($users) use ($data) {
            $userIds = $users->pluck('id')->toArray();
            SendNotificationUsers::dispatch($userIds, $data['text']);
        });
        return redirect()->back();
    }

    public function sendMessageOneUser(Request $request, User $user)
    {
        $data = $request->validate([
            'text' => "required|string"
        ]);
        $user->notify(new SendMessageUser($data['text']));
        return redirect()->back();
    }

}
