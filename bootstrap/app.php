<?php

use App\Http\Middleware\LanguageMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append:[
            App\Http\Middleware\LanguageMiddleware::class,
        ]);
        
        $middleware->redirectGuestsTo(fn (Request $request) => route('login', ['lang' => Session::get('locale') ?? 'lv']));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
