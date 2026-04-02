<?php

use App\Http\Middleware\RateLimitMiddleware;
use App\Http\Middleware\policyControl;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php', 
        apiPrefix:'',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            '/*',
            '*/*',
            '*',
        ]);
        $middleware->append(policyControl::class);
        $middleware->append(RateLimitMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
