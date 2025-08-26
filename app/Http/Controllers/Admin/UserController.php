<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusUser;
use App\Models\User;
use App\Notifications\User\ActiveAccount;
use App\Notifications\User\BlockedAccount;
use App\Notifications\User\ModerationAccount;
use App\Services\NotificationService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController
{
    public function __construct(
        public UserService $users,
        public NotificationService $notifications,
    ) {
    }
    public function blocked(User $user): RedirectResponse
    {
        if ($this->users->blocked($user)) {
            $this->notifications->userBlocked($user);
            return redirect()->back()->with('success', "Пользователь заблокирован");
        }
        return redirect()->back()->with('error', "Не удалось заблокировать пользователя");
    }

    public function actived(User $user): RedirectResponse
    {
        if ($this->users->actived($user)) {
            $this->notifications->userActived($user);
            return redirect()->back()->with('success', "Пользователь активирован");
        }
        return redirect()->back()->with('error', "Не удалось активировать пользователя");
    }

    public function moderated(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate(['text' => 'nullable|string']);
        if ($this->users->moderated($user)) {
            $this->notifications->userModerated($user);
            if (isset($data['text'])) {
                $this->users->setAdminMessage($user, $data['text']);
            }
            return redirect()->back()->with('success', "Пользователь активирован");
        }
        return redirect()->back()->with('error', "Не удалось активировать пользователя");
    }
}
