<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
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
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            // ->login(CustomLogin::class)
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->brandName('SDN Heavenhold 1')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
            // ->renderHook(
            //     'panels::auth.login.form.after', // Posisi hook
            //     fn () => Blade::render('
            //         <style>
            //             /* 1. Ganti Background Halaman */
            //             body {
            //                 background-image: url("https://images.unsplash.com/photo-1580582932707-520aed937b7b?q=80&w=2064&auto=format&fit=crop"); 
            //                 background-size: cover;
            //                 background-position: center;
            //                 background-repeat: no-repeat;
            //             }

            //             /* 2. Efek Glassmorphism pada Kotak Login */
            //             .fi-simple-main-ctn {
            //                 background-color: rgba(255, 255, 255, 0.85) !important; /* Putih Transparan */
            //                 backdrop-filter: blur(10px); /* Efek Blur di belakang kotak */
            //                 border-radius: 1.5rem; /* Sudut membulat */
            //                 box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            //                 padding: 2rem;
            //             }
                        
            //             /*Dark Mode Adjustment */
            //             .dark .fi-simple-main-ctn {
            //                 background-color: rgba(17, 24, 39, 0.85) !important;
            //             }
            //         </style>
            //     ')
            // );
    }
}
