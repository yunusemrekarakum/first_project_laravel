<?php

use App\Events\ProductEvent;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/event', function () {
    dd(event(new ProductEvent('mesaj başarılı')));
});

Route::get('/{any}', function () {
    return view('app'); // app.blade.php dosyanız
})->where('any', '.*');
