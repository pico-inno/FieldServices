<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckSession
{
    public function handle($request, Closure $next)
    {
        // Determine the guard in use
        $guard = Auth::getDefaultDriver();

        // Set the custom user_id column in the session
        session()->put('user_id', "{$guard}_".auth($guard)->id());

        return $next($request);
    }
}
