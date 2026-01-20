<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\LogRequestResponse;
use App\Http\Middleware\SetLocaleFromHeader;
use App\Http\Middleware\ClientAuthMiddleware;
use App\Http\Middleware\CustomAuthenticate;
use App\Http\Middleware\AdminAccess;
use App\Http\Middleware\LogViewerAccess;

use Illuminate\Routing\Middleware\SubstituteBindings;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('api', [
            SubstituteBindings::class,
            LogRequestResponse::class,
            SetLocaleFromHeader::class,
        ]);
        $middleware->alias([
            'client.passport' => ClientAuthMiddleware::class,
            'auth.check' => CustomAuthenticate::class,
            'web.log-viewer-access' => LogViewerAccess::class,
            'admin.access' => AdminAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
