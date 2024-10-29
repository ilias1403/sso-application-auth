<?php

use Illuminate\Support\Facades\Route;
use Iliasdaniel\Brainsso\Controllers\Auth\SsoAuthenticationController;

Route::middleware(['web'])->group(function () {
    Route::get('/auth/redirect', [SsoAuthenticationController::class, 'redirect'])->name('sso.redirect');
    Route::get('/auth/callback', [SsoAuthenticationController::class, 'authenticate'])->name('sso.authenticate');
});
