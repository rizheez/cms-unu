<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Support\Facades\View;
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
        View::composer('layouts.app', function ($view): void {
            $headerMenuItems = Menu::query()
                ->where('location', 'header')
                ->where('is_active', true)
                ->with('rootItemsRecursive')
                ->first()
                ?->rootItemsRecursive
                ?? collect();

            $footerMenuItems = Menu::query()
                ->where('location', 'footer')
                ->where('is_active', true)
                ->with('rootItemsRecursive')
                ->first()
                ?->rootItemsRecursive
                ?? collect();

            $view->with([
                'headerMenuItems' => $headerMenuItems,
                'footerMenuItems' => $footerMenuItems,
            ]);
        });
    }
}
