<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin_Controller;

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

Route::get('/admin/giris', [AuthController::class, 'login'])->name('login');
Route::post('/admin/giris', [AuthController::class, 'login'])->name('login');
Route::get('/admin/cikis', [AuthController::class, 'logout']);

Route::get('/admin', [Admin_Controller::class, 'admin'])->name('admin')->middleware('auth:admins');
Route::get('/admin/urun-ekle', [Admin_Controller::class, 'add_product'])->name('admin')->middleware('auth:admins');
Route::get('/admin/hesabim', [Admin_Controller::class, 'account'])->name('admin')->middleware('auth:admins');
