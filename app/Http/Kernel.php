<?php

namespace App\Http;

use App\Http\Middleware\activateBusinessCheckMiddleware;
use App\Http\Middleware\CreatePermission;
use App\Http\Middleware\DeletePermission;
use App\Http\Middleware\InstallPermission;
use App\Http\Middleware\TransferPermission;
use App\Http\Middleware\UninstallPermission;
use App\Http\Middleware\UpdatePermission;
use App\Http\Middleware\UploadPermission;
use App\Http\Middleware\ViewPermission;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\businessActivate;
use App\Http\Middleware\install;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\CheckSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'isActive' => \App\Http\Middleware\CheckAccountStatus::class,
        'canView' => \App\Http\Middleware\ViewPermission::class,
        'canCreate' => \App\Http\Middleware\CreatePermission::class,
        'canUpdate' => \App\Http\Middleware\UpdatePermission::class,
        'canDelete' => \App\Http\Middleware\DeletePermission::class,
        'canInstall' => InstallPermission::class,
        'canUninstall' => UninstallPermission::class,
        'canUpload' => UploadPermission::class,
        'canTransfer' => TransferPermission::class,
        'businessActivate' => businessActivate::class,
        'activateBusinessCheckMiddleware' => activateBusinessCheckMiddleware::class,
        'install' => install::class,

    ];
}
