<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AgendarPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('agendar')
            ->path('agendar')
              ->login()
            ->favicon(asset('imagenes/logo.png'))
            ->brandLogo(asset('imagenes/logo.png'))
            ->brandLogoHeight('60px')
            ->colors([
                'primary' => '#1D4ED8',
                'danger' => Color::Red, 
                // poner imagen de logo (comentario)
            ])
            ->discoverResources(in: app_path('Filament/Agendar/Resources'), for: 'App\\Filament\\Agendar\\Resources')
            ->discoverPages(in: app_path('Filament/Agendar/Pages'), for: 'App\\Filament\\Agendar\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Agendar/Widgets'), for: 'App\\Filament\\Agendar\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
               
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
