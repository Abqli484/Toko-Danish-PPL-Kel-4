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

        if (env('GOOGLE_CLOUD_KEY_BASE64')) {
        $path = storage_path('app/google/laravel-gcs.json');

        if (!file_exists($path)) {
            \Illuminate\Support\Facades\File::ensureDirectoryExists(dirname($path));
            file_put_contents($path, base64_decode(env('GOOGLE_CLOUD_KEY_BASE64')));
        }
    }
    }
}
