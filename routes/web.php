<?php

use App\Http\Controllers\Auth\ConfirmSmsController;
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

Route::prefix('/register')->group(function () {
    Route::view('/', 'auth.register');
    Route::post('/create', [RegisterController::class, 'create'])->name('auth.register.create');
    Route::post('/confirm', [ConfirmSmsController::class, 'handle'])->name('auth.register.confirm');
});

Route::view('/login', 'auth.login')->name('auth.login');
Route::view('/confirm', 'auth.confirm')->name('auth.confirm.sms');
