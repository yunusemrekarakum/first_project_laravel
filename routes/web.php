<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin_Controller;

Route::get('/{any}', function () {
    return view('app'); // app.blade.php dosyanız
})->where('any', '.*');

