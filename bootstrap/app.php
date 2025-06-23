<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$gcsKeyBase64 = getenv('GOOGLE_CLOUD_KEY_BASE64');
$keyFilePath = __DIR__ . '/../storage/app/google/laravel-gcs.json';

if ($gcsKeyBase64 && !file_exists($keyFilePath)) {
    @mkdir(dirname($keyFilePath), 0755, true);
    file_put_contents($keyFilePath, base64_decode($gcsKeyBase64));
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
         
        $middleware->alias([

            'seller' => \App\Http\Middleware\Seller::class,

            
        ]);
        
        $middleware->validateCsrfTokens(except: [
            // '/login',
            // '/register',
            ]);

    //     $middleware->trustProxies(at: [
    //     '0.0.0.0/0',
    //     '*.railway.app'
    // ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
