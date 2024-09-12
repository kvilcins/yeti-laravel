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
    Route::get('/add', [LotController::class, 'create'])->name('lot.create');
    Route::post('/add', [LotController::class, 'store'])->name('lot.store');
    
    // Пока закомментирую на будущее роуты для редактирования, удаления и обновления лотов
    
    // Route::get('/lot/{id}/edit', [LotController::class, 'edit'])->name('lot.edit');
    // Route::put('/lot/{id}', [LotController::class, 'update'])->name('lot.update');
    // Route::delete('/lot/{id}', [LotController::class, 'destroy'])->name('lot.destroy');
});
// Страницы лотов
Route::get('/lot/{id}', [LotController::class, 'show'])->name('lot.show');

// Ставки (в разработке)
Route::post('/lot/{id}/bid', [BidController::class, 'store'])->name('bids.store');

// Просмотренные лоты
Route::get('/viewed-lots', [ViewedLotsController::class, 'index'])->name('viewed.lots');

// Показать форму регистрации и входа
Route::get('/register', [AuthController::class, 'create'])->name('register');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Обработка данных регистрации и логина
Route::post('/register', [AuthController::class, 'store'])->name('register.store');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Разлогирование
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');