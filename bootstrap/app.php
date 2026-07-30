<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'verify.api_key' => \App\Http\Middleware\VerifyAgentApiKey::class,
        ]);

        // ต้องอยู่นอก StartSession (prepend) เพื่อลบ Set-Cookie หลัง Laravel ใส่ session ใหม่
        $middleware->prependToGroup('web', \App\Http\Middleware\PreserveSessionOnCrossSiteReturn::class);

        $middleware->validateCsrfTokens(except: [
            'payment/2c2p/backend',
            'payment/2c2p/frontend',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
