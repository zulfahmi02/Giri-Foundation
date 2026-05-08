<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ThrottleRequestsException $exception, Request $request) {
            $redirectRoute = match ($request->route()?->getName()) {
                'contact.store' => 'contact.show',
                'donate.store' => 'donate.show',
                'partners.store' => 'partners.index',
                default => null,
            };

            if ($redirectRoute === null) {
                return null;
            }

            return to_route($redirectRoute)
                ->withInput()
                ->withErrors([
                    'form' => 'Terlalu banyak percobaan pengiriman. Mohon tunggu sebentar lalu coba lagi.',
                ]);
        });
    })->create();
