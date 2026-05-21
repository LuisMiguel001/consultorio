<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\NoCache;
use App\Http\Middleware\VerificarSuscripcionActiva;
use App\Http\Middleware\VerificarModuloPlan;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'nocache' => NoCache::class,
        ]);
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'suscripcion.activa' => VerificarSuscripcionActiva::class,
        ]);
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'suscripcion.activa' => VerificarSuscripcionActiva::class,
            'modulo.plan' => VerificarModuloPlan::class,
        ]);
    })
    ->withMiddleware(function ($middleware) {

        $middleware->alias([
            'demo.activo' =>
            \App\Http\Middleware\DemoExpiradoMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
