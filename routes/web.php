<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::get('/admin/giris', function () {
    if (Auth::check()) {
        return redirect('/admin');
    } else {
        return view('admin/login');
    }
});

Route::get('/admin', function () {
    return view('admin/product/list');
});

Route::get('/admin/urun-ekle', function () {
    return view('admin/product/add');
});

Route::get('/admin/hesabim', function () {
    return view('admin/account');
});



Route::post('/panellogin', [AuthController::class, 'login']);
