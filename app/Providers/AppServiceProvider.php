<?php

namespace App\Providers;

use App\Models\Solicitud_Admision;
use App\Observers\SolicitudObserver;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Illuminate\Support\Facades\Route;
use Filament\Facades\Filament;
use Filament\Panel;

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
         Livewire::setUpdateRoute(function ($handle) {
        return Route::post('/solicitarcita/public/livewire/update', $handle);
    });

    Livewire::setScriptRoute(function ($handle) {
        return Route::get('/solicitarcita/public/livewire/livewire.js', $handle);
    });
    }
    
}
