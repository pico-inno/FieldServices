<?php

namespace App\Providers;

use App\Models\Currencies;
use Ramsey\Uuid\Type\Integer;
use App\Helpers\SettingHelpers;
use Illuminate\Pagination\Paginator;
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
            try {
                if (Auth::check()) {
                    $user = Auth::user()->personal_info;
                    $language=$user->language =='mm' ? 'my' : $user->language;
                    app()->setLocale($language ?? 'en');
                }

            } catch (\Throwable $th) {
            }
        });

        Paginator::useBootstrapFive();


    }
}
