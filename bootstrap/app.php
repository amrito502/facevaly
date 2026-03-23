<?php

use App\Http\Middleware\AdminAuthenticate;
use App\Http\Middleware\EnsureForgotStep;
use App\Http\Middleware\EnsureRegisterStep;
use App\Http\Middleware\RedirectIfAuthenticatedByRole;
use App\Http\Middleware\SellerAuthenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;




return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'register.step' => EnsureRegisterStep::class,
            'forgot.step' => EnsureForgotStep::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'redirect.if.authenticated' => RedirectIfAuthenticatedByRole::class,
            'admin.auth' => AdminAuthenticate::class,
            'seller.auth' => SellerAuthenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
