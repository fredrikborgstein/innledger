<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRequirePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->password_change_required) {
            if (! $request->routeIs('profile.edit') && ! $request->routeIs('logout')) {
                return redirect()->route('profile.edit')->with('status', 'password-change-required');
            }
        }

        return $next($request);
    }
}
