<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\EmailVerifyController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CustomEnsureEmailVerified;
use Illuminate\Http\Request;
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

    Route::delete(
        '/account',
        [AccountController::class, 'destroy']
    )->name('account.destroy');

    Route::get(
        '/email/is-verified',
        [EmailVerifyController::class, 'isVerified']
    )->name('verification.checkIfVerified');

    Route::post(
        '/email/verification-notification',
        [EmailVerifyController::class, 'resendEmail']
    )->name('verification.send');
});

Route::middleware(['auth:sanctum', 'signed'])->group(function () {
    Route::get(
        '/email/verify/{id}/{hash}',
        [EmailVerifyController::class, 'verifyEmail']
    )->name('verification.verify');
});

Route::middleware([
    'auth:sanctum',
    CustomEnsureEmailVerified::class
])->group(function () {
    Route::get('/isAuth', function (Request $request) {
        return response("Hello {$request->user()->name}. You are authenticated.", 200);
    });

    Route::get(
        '/credentials/{id}',
        [CredentialController::class, 'show']
    )->name('credentials.show')->whereNumber('id');

    Route::get(
        '/credentials/{title?}',
        [CredentialController::class, 'index']
    )->name('credentials.index');

    Route::post(
        '/credentials',
        [CredentialController::class, 'store']
    )->name('credentials.create');

    Route::put(
        '/credentials/{id}',
        [CredentialController::class, 'update']
    )->name('credentials.update');

    Route::delete(
        '/credentials/{id}',
        [CredentialController::class, 'destroy']
    )->name('credentials.destroy');
});
