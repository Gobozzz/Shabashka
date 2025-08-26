<?php

namespace App\Http\Controllers\Socialite;

use App\Enums\RolesUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class ProviderRedirectController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $provider): RedirectResponse
    {
        if (!in_array($provider, ['google'])) {
            return redirect()->route('login')->withErrors(['provider' => 'Неизвестный провадер']);
        }
        try {
            return Socialite::driver($provider)->with([
                'state' => request()->input('role')
            ])->redirect();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['provider' => $e->getMessage()]);
        }
    }
}
