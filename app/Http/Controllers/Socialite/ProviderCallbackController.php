<?php

namespace App\Http\Controllers\Socialite;

use App\Actions\User\GetUrlLkForUser;
use App\Actions\User\IsBlockedAccessUser;
use App\Enums\RolesUser;
use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Str;

class ProviderCallbackController extends Controller
{
    public function __construct(
        public UserService $userService,
        public NotificationService $notifications,
        public IsBlockedAccessUser $blocked
    ) {
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $provider): RedirectResponse
    {
        if (!in_array($provider, ['google'])) {
            return redirect()->route('login')->withErrors(['provider' => 'Invalid Provider']);
        }
        $socialUser = Socialite::driver($provider)->stateless()->user();
        $role = request()->input('state');
        if ($role && !in_array($role, RolesUser::publicRoles())) {
            return redirect()->route('login')->withErrors(['login' => "Не удалось зарегистрировать пользователя. Некорректная роль."]);
        }
        if (!$role) {
            $role = RolesUser::WORKER->value;
        }
        $user_by_provider = $this->userService->getByEmail($socialUser->email);
        if ($user_by_provider) {
            if ($this->blocked->handle($user_by_provider)) {
                return redirect()->route('login')->withErrors(['login' => __('auth.failed_blocked_account')]);
            }
            Auth::login($user_by_provider);
        } else {
            try {
                $password = Str::random(8);
                $user_by_provider = $this->userService->create([
                    'name' => $socialUser->name,
                    'email' => $socialUser->email,
                    'email_verified_at' => now(),
                    'password' => $password,
                    'role' => $role,
                ]);
                $this->notifications->createNewUserNotify($user_by_provider);
                $this->notifications->sendPasswordUser($user_by_provider, $password);
                Auth::login($user_by_provider);
            } catch (\Throwable $th) {
                return redirect()->route('login')->withErrors(['login' => "Не удалось зарегистрировать пользователя"]);
            }
        }
        $url = (new GetUrlLkForUser())->handle($user_by_provider);
        return redirect()->intended($url);
    }
}
