<?php

namespace App\Http\Controllers\Auth;

use App\Actions\User\GetUrlLkForUser;
use App\Actions\User\IsBlockedAccessUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\UserService;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(public UserService $userService)
    {
    }
    public function loginForm(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request, GetUrlLkForUser $getUrl, IsBlockedAccessUser $blocked): RedirectResponse
    {
        $data = $request->validated();
        $user = $this->userService->getByEmailPhone($data['login']);
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return redirect()->back()->withErrors(['login' => __('auth.failed')]);
        }
        if ($blocked->handle($user)) {
            return redirect()->back()->withErrors(['login' => __('auth.failed_blocked_account')]);
        }
        Auth::login($user);
        return redirect()->intended($getUrl->handle($user));
    }

}
