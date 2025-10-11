<?php

namespace App\Providers\Filament;

use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession as MiddlewareAuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use App\Providers\RouteServiceProvider;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default() // Ce panneau est le panneau par défaut de ton app
            ->id('admin')
            ->profile()
            ->path('admin')
            ->brandLogo(asset('images/tumaini_letu.jpg'))
            ->brandLogoHeight('3rem') // Agrandit le logo (tu peux mettre 4rem, 60px, etc.)
            ->brandName('TUMAINI LETU SYSTEM') // Nom affiché dans la barre du haut
            ->login() // Active la page de connexion
            ->registration() // Si tu veux permettre la création de compte
            ->passwordReset() // Pour permettre la réinitialisation de mot de passe
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Gestion du système')
                    ->icon('heroicon-o-cog'),
                NavigationGroup::make()
                    ->label('Comptes et utilisateurs')
                    ->icon('heroicon-o-user-group'),
            ])
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                HandlePrecognitiveRequests::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class, // 🔒 Protège le panneau : login obligatoire
            ]);
    }
}
