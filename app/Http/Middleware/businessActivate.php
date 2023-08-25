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

            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);
            // dd($envFile);
            if ($businessUserCount == 0 && $businessCount == 0) {
                return redirect()->route('activationForm');
            } else {
                return $next($request);
            };
        } catch (\Throwable $th) {
            return redirect()->route('envConfigure');
        }
    }
}
