<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // dd(env('SSO_CLIENT_ID'));
    dd(config('sso.client_id'));
    return view('welcome');
});
