<?php

namespace App\Providers;

use Ramsey\Uuid\Type\Integer;
use App\Helpers\SettingHelpers;
use App\Models\Currencies;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Models\settings\businessSettings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Currency;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        view()->composer('*', function($view) {
            if (Auth::check()) {
                $user = Auth::user();
                app()->setLocale($user->language ?? 'en');
            }
        });
    }
}
