<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/giris-yap', function () {
    return view('login');
});

Route::get('/kayit-ol', function () {
    return view('register');
});

Route::get('/hesabim', function () {
    return view('account');
});