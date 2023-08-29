<?php

namespace App\Http\Middleware;

use App\Models\BusinessUser;
use App\Models\settings\businessSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class businessActivate
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
                return $next($request);
            } else {
                return redirect('/home');
            };
        } catch (\Throwable $th) {
            return redirect()->route('envConfigure');
        }
    }
}
