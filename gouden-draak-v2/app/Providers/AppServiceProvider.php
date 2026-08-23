<?php

namespace App\Providers;

use App\Models\SiteNavbarItem;
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
        View::composer('main.layout', function ($view): void {
            $view->with('navbarItems', SiteNavbarItem::query()->with('page')->orderBy('order')->get());
        });
    }
}
