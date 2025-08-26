<?php

namespace App\Http\Middleware;

use App\Actions\User\GetUrlLkForUser;
use App\Enums\RolesUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RolesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        $getUrl = new GetUrlLkForUser();
        if (!in_array(RolesUser::getRoleNameEn($user->role->value), $roles)) {
            return redirect()->to($getUrl->handle($user));
        }
        return $next($request);
    }
}
