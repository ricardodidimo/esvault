<?php

use App\Http\Controllers\CredentialController;
use App\Http\Middleware\CustomEnsureEmailVerified;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    CustomEnsureEmailVerified::class
])->group(function () {
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
