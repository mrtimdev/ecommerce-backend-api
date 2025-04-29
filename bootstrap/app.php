<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function() {
            Route::middleware('api')
                ->prefix('ftd/api/v1')
                ->group(base_path('routes/api_v1.php'));
            Route::group(
                [
                    'middleware' => ['web', 'backend.access']
                ], function () {
                    Route::prefix('admin')->group(base_path('routes/admin.php'));
            });
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            \App\Http\Middleware\SetLocale::class,
        ]);
        $middleware->api(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
        $middleware->alias([
            'backend.access' => \App\Http\Middleware\BackendAccess::class,
            'frontend.access' => \App\Http\Middleware\FrontendAccess::class,
            'email.verified' => \App\Http\Middleware\Frontend\CheckEmailVerified::class,
        ]);
        $middleware->statefulApi();
        
        $middleware->validateCsrfTokens(except: [
            'ftd/api/v1/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
