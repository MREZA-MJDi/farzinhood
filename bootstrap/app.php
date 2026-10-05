<?php

use App\Http\Middleware\CustomerMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\AdminMiddleware;

return Application::configure(
    basePath: dirname(__DIR__)
)
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (
        Middleware $middleware
    ) {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'customer' => CustomerMiddleware::class,
        ]);

        $middleware->redirectUsersTo(
            fn (Request $request) => $request->user()?->isAdmin()
                ? route('admin.dashboard')
                : route('customer.dashboard')
        );
    })

    ->withExceptions(function (
        Exceptions $exceptions
    ) {
        //
    })
    ->create();
