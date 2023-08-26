<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\BusinessUser;
use Illuminate\Http\Request;
use App\Models\settings\businessSettings;
use Symfony\Component\HttpFoundation\Response;

class activateBusinessCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $businessUserCount = BusinessUser::count();
            $businessCount = businessSettings::count();
            if ($businessUserCount == 0 && $businessCount == 0) {
                return redirect()->route('activationForm');
            } else {
                return $next($request);
            };
        } catch (\Throwable $th) {
            return redirect()->route('envConfigure');
        }
        return $next($request);
    }
}
