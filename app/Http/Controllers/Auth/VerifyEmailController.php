<?php

namespace App\Http\Controllers\Auth;

use App\Actions\User\GetUrlLkForUser;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request, GetUrlLkForUser $getUrl): RedirectResponse
    {
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }
        return redirect()->intended($getUrl->handle($request->user()));
    }
}
