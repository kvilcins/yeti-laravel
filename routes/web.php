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

// Homepage
Route::get('/', [MainController::class, 'index'])->name('home');

// Public authentication pages
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'create'])->name('register');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/register', [AuthController::class, 'store'])->name('register.store');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// Logout (available only to authenticated users)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Routes group for authenticated users
Route::middleware('auth')->group(function () {

    // Lots
    Route::prefix('lots')->group(function () {
        Route::get('/add', [LotController::class, 'create'])->name('lot.create');
        Route::post('/add', [LotController::class, 'store'])->name('lot.store');
        Route::post('/{id}/bid', [LotController::class, 'placeBid'])->name('bids.store');
    });

    // User profile
    Route::prefix('account')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::delete('/avatar', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
    });

    // Viewed lots
    Route::get('/viewed-lots', [ViewedLotsController::class, 'index'])->name('viewed.lots');
});

// Public pages (no authentication required)
// Search
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/search-suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

// Catalog and categories
Route::get('/catalog', [CatalogController::class, 'show'])->name('catalog');
Route::get('/catalog/{slug}', [CategoryController::class, 'show'])->name('category.show');

// Lot pages (should be at the end to avoid intercepting other routes)
Route::get('/catalog/{category_slug}/{slug}', [LotController::class, 'show'])->name('lot.show');
