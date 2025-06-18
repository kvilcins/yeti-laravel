<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\LotController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ViewedLotsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CatalogController;

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

    // Ставки
    Route::post('/lots/{id}/bid', [LotController::class, 'placeBid'])->name('bids.store');

    // Страница личного кабинета
    Route::get('/account', [ProfileController::class, 'show'])->name('profile');

    // Форма редактирования профиля
    Route::get('/account/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Обновление профиля
    Route::put('/account/update', [ProfileController::class, 'update'])->name('profile.update');

    // Удаление аватара
    Route::put('/account/avatar/delete', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');

    // Просмотренные лоты
    Route::get('/viewed-lots', [ViewedLotsController::class, 'index'])->name('viewed.lots');
});

// Страницы лотов
Route::get('/catalog/{category_slug}/{slug}', [LotController::class, 'show'])->name('lot.show');

// Показать форму регистрации и входа
Route::get('/register', [AuthController::class, 'create'])->name('register');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Обработка данных регистрации и логина
Route::post('/register', [AuthController::class, 'store'])->name('register.store');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Разлогирование
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Поиск
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Поисковые подсказки
Route::get('/search-suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

// Страницы категорий
Route::get('/catalog/{slug}', [CategoryController::class, 'show'])->name('category.show');

// Страница каталога
Route::get('/catalog', [CatalogController::class, 'show'])->name('catalog');
