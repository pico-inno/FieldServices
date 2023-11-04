<?php

namespace App\Providers;

use App\Models\Currencies;
use Ramsey\Uuid\Type\Integer;
use App\Helpers\SettingHelpers;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\settings\businessSettings;
use App\Repositories\CurrencyRepository;
use App\Repositories\interfaces\CurrencyRepositoryInterface;
use App\Repositories\interfaces\LocationRepositoryInterface;
use App\Repositories\interfaces\SettingRepositoryInterface;
use App\Repositories\LocationRepository;
use App\Repositories\SettingRepository;
use Illuminate\Support\Facades\Artisan;
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

        $this->app->bind(LocationRepositoryInterface::class, LocationRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(CurrencyRepositoryInterface::class, CurrencyRepository::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        if (!Schema::hasTable('sessions')) {
            Artisan::call('session-create');
        }
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
