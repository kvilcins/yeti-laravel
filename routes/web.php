<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\LotController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ViewedLotsController;

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

// Главная страница
Route::get('/', [MainController::class, 'index'])->name('home');

// Доступ к добавлению лота только для авторизованных пользователей
Route::middleware('auth')->group(function () {
    Route::match(['get', 'post'], '/add', [LotController::class, 'handleForm'])->name('add.form');
});

// Я что-то забыла, для чего это :D
Route::post('/lot/store', [LotController::class, 'handleForm'])->name('lot.store');

// Страницы лотов
Route::get('/lot/{id}', [LotController::class, 'show'])->name('lot.show');

// Ставки (в разработке)
Route::post('/lot/{id}/bid', [BidController::class, 'store'])->name('bids.store');

// Авторизация
Route::match(['get', 'post'], '/login', [AuthController::class, 'handleLogin'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Регистрация
Route::match(['get', 'post'], '/register', [AuthController::class, 'handleRegistration'])->name('register');

// Просмотренные лоты
Route::get('/viewed-lots', [ViewedLotsController::class, 'index'])->name('viewed.lots');
