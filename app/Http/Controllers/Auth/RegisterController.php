<?php

namespace App\Http\Controllers\Auth;

use App\Actions\User\GetUrlLkForUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\NotificationService;
use App\Services\UserService;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterController extends Controller
{

    public function __construct(public UserService $userService, public NotificationService $notifications)
    {
    }

    public function registerForm(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request, GetUrlLkForUser $getUrl): RedirectResponse
    {
        $data = $request->validated();
        $user = $this->userService->create($data); // создаем пользователя
        Auth::login($user);
        $this->notifications->createNewUserNotify($user);
        return redirect()->to($getUrl->handle($user));
    }
}
