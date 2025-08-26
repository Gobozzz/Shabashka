<?php

namespace App\Http\Controllers\Auth;

use App\Actions\User\GetUrlLkForUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(GetUrlLkForUser $getUrl): RedirectResponse|View
    {
        return redirect()->intended($getUrl->handle(auth()->user()));
    }
}
