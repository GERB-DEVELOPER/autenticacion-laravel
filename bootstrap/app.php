<?php
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Cache\RateLimiting\RateLimiter;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // Agregar la ruta de la API
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
               'role' => \App\Http\Middleware\CheckRole::class,
               'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        ]);

        // Configurar el grupo de middleware 'api' para Sanctum
         $middleware->group('api', [
              \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
              \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
              \Illuminate\Routing\Middleware\SubstituteBindings::class,
         ]);
    })
  ->withProviders([
    App\Providers\RateLimitingServiceProvider::class,
    ])

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
