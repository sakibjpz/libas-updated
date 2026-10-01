<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Facebook's _fbc/_fbp cookies are set by JS (not encrypted) — skip decryption
        // so MetaConversionsApi can read them for better event match quality.
        $middleware->encryptCookies(except: ['_fbc', '_fbp']);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
