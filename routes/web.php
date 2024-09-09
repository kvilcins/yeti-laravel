<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\LotController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\AuthController;

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

Route::get('/', [MainController::class, 'index'])->name('home');

Route::match(['get', 'post'], '/add', [LotController::class, 'handleForm'])->name('add.form');

Route::post('/lot/store', [LotController::class, 'handleForm'])->name('lot.store');

Route::get('/lot/{id}', [LotController::class, 'show'])->name('lot.show');

Route::post('/lot/{id}/bid', [BidController::class, 'store'])->name('bids.store');

Route::match(['get', 'post'], '/login', [AuthController::class, 'handleLogin'])->name('login');
