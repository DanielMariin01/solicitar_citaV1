<?php

namespace App\Providers;

use App\Models\Solicitud_Admision;
use App\Observers\SolicitudObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         Solicitud_Admision::observe(SolicitudObserver::class);
    }
}
