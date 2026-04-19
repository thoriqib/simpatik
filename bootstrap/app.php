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

    // User belum login → ke halaman login
    $middleware->redirectGuestsTo(fn (Request $request) => route('login'));

    // User sudah login buka halaman login → ke dashboard sesuai role
    $middleware->redirectUsersTo(function (Request $request) {
        if ($request->user()?->hasRole('admin'))   return route('admin.dashboard');
        if ($request->user()?->hasRole('petugas')) return route('petugas.dashboard');
        return route('home');
    });
})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
