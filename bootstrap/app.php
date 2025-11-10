<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function ()  {
          Route::middleware('web')->group(__DIR__.'/../routes/authRoute.php');
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
      $middleware->redirectGuestsTo(fn (Request $request) => route('auth.loginView'));
      $middleware->redirectUsersTo(fn (Request $request) => route('user.profile',Auth::user()->role));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
