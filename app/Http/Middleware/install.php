<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\BusinessUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\settings\businessSettings;
use Symfony\Component\HttpFoundation\Response;

class install
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (Schema::hasTable('business_users') && Schema::hasTable('permissions') && Schema::hasTable('role_permissions')) {
                return redirect()->route('activationForm');
            } else {
                return $next($request);
            };
        } catch (\Throwable $th) {
            return $next($request);
        }
    }
}
