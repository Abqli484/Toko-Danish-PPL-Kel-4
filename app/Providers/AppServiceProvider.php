<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Kavist\RajaOngkir\RajaOngkir;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }       

        $this->app->singleton(RajaOngkir::class, function ($app) {
            return new RajaOngkir(env('API_ONGKIR_KEY'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $view->with('cart_count', Auth::user()->cart->total_item);
            }
        });
    }
}
