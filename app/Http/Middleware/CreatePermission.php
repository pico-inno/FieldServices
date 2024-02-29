<?php

namespace App\Http\Middleware;

use App\Helpers\ApiHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CreatePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $feature): Response
    {
        if (hasCreate($feature)){
            return $next($request);
        }else{
            if (ApiHelper::isApiRequest($request)) {
                return ApiHelper::permissionDenied('You do not have permission to access this resource.');
            }
            return abort(403);
        }
    }
}
