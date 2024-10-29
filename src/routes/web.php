<?php

use Illuminate\Support\Facades\Route;
use Iliasdragneel\BrainSsoAuth\Http\Controllers\Auth\SsoAuthenticationController;

Route::middleware(['web'])->group(function () {
    Route::get('/auth/redirect', [SsoAuthenticationController::class, 'redirect'])->name('sso.redirect');
    Route::get('/auth/callback', [SsoAuthenticationController::class, 'authenticate'])->name('sso.authenticate');
});