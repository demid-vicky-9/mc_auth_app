<?php

use App\Http\Controllers\Auth\ConfirmSmsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'guest'], function () {
    Route::prefix('/register')->group(function () {
        Route::view('/', 'auth.register')->name('auth.register');
        Route::post('/create', [RegisterController::class, 'create'])->name('auth.register.create');
        Route::post('/confirm', [ConfirmSmsController::class, 'handle'])->name('auth.register.confirm');
    });

    Route::view('/login', 'auth.login')->name('auth.login');
    Route::view('/confirm', 'auth.confirm')->name('auth.confirm.sms');

    Route::post('/login', [LoginController::class, 'handle'])->name('login.user');
});

Route::group(['middleware' => 'auth'], function () {
    Route::view('/main', 'front.index')->name('front.index');
    Route::get('/logout', [LogoutController::class, 'handle'])->name('auth.logout');
});
