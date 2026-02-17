<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsPropertyManager
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || ! auth()->user()->property_manager) {
            abort(403, 'Access denied. Property manager access required.');
        }

        return $next($request);
    }
}
