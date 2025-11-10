<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\authController;
// use Illuminate\Auth\Middleware\RedirectIfAuthenticated;

use App\Http\Middleware\RedirectIfAuthenticated;



Route::middleware(["guest"])->group(function () {
  Route::get('/', [authController::class, 'loginView'])->name('auth.loginView');
  Route::post('/login', [authController::class, 'login'])->name('auth.login');
  Route::get('/register', [authController::class, 'registerView'])->name('auth.registerView');
  Route::post('/register', [authController::class, 'register'])->name('auth.register');
});

Route::middleware(['auth'])->group(function () {
  Route::post('/application/close', [authController::class, 'closeApplication'])->name('application.close');
  Route::post('/application/open', [authController::class, 'openApplication'])->name('application.open');
});


Route::get('/logout', [authController::class, 'logout'])->name('auth.logout');

Route::get('/forget-password',[authController::class, 'forgetPassword'])->name('forgetPassword');
Route::post('/forget-password',[authController::class, 'sendLink'])->name('forgot.send');



Route::get('/reset-password', [authController::class,'showResetForm'])->name('password.reset.form'); // expects ?token=&email=
Route::post('/reset-password', [authController::class,'reset'])->name('password.reset');
