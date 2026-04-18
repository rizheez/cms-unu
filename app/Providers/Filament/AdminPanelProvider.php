<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
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
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->darkMode(false)
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->strictAuthorization()
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Konten')
                    ->icon(Heroicon::OutlinedDocumentText),
                NavigationGroup::make()
                    ->label('Akademik')
                    ->icon(Heroicon::OutlinedAcademicCap),
                NavigationGroup::make()
                    ->label('Media')
                    ->icon(Heroicon::OutlinedPhoto),
                NavigationGroup::make()
                    ->label('Website')
                    ->icon(Heroicon::OutlinedCog6Tooth),
                NavigationGroup::make()
                    ->label('SEO')
                    ->icon(Heroicon::OutlinedDocumentMagnifyingGlass),
                NavigationGroup::make()
                    ->label('Komunikasi')
                    ->icon(Heroicon::OutlinedInbox),
                NavigationGroup::make()
                    ->label('Sistem')
                    ->icon(Heroicon::OutlinedUsers),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationLabel('Peran & Izin')
                    ->navigationGroup('Sistem')
                    ->navigationSort(20),
            ])
            ->spa()
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
