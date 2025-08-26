<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;

class ChangePasswordController extends Controller
{
    public function __invoke(ChangePasswordRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();
        try {
            $user->update([
                'password' => $data['new_password']
            ]);
            return redirect()->back()->with('success-update-password', __('auth.password-success-change'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error-update-password', __('auth.password-error-change'));
        }
    }
}
