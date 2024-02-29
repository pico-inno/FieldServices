<?php

namespace App\Helpers;

class ApiHelper
{
    public static function isApiRequest($request)
    {
        return strpos($request->getRequestUri(), '/'.config('prefix_api')) === 0;
    }

    public static function permissionDenied($message = 'Permission Denied')
    {
        return response()->json(['error' => $message], 403);
    }
}
