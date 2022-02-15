<?php

use App\Http\Controllers\EmailVerifyController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
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
