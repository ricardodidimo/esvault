<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('guest')->group(function () {
    Route::post('/users', [UserController::class, 'store'])->name('user.register');
    Route::post('/account', [AccountController::class, 'store'])->name('account.login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::put(
        '/users',
        [UserController::class, 'update']
    )->name('user.update');

    Route::delete(
        '/users',
        [UserController::class, 'destroy']
    )->name('user.destroy');

    Route::get(
        '/account',
        [AccountController::class, 'index']
    )->name('account.index');

    Route::post(
        '/account/confirmation',
        [AccountController::class, 'confirm']
    )->name('account.confirm');

    Route::delete(
        '/account',
        [AccountController::class, 'destroy']
    )->name('account.destroy');
});
