<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

// Login
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// DASHBOARD UNTUK SEMUA ROLE YANG LOGIN
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// ------------------------
// UNTUK ADMIN SAJA (role_id = 1)
// ------------------------
Route::middleware(['auth', 'role:1'])->group(function () {
    // PRODUK: CRUD 
    Route::get('/produk/create', [ProdukController::class, 'create']);
    Route::post('/produk', [ProdukController::class, 'store']);
    Route::get('/produk/{id}', [ProdukController::class, 'edit']);
    Route::put('/produk/{id}', [ProdukController::class, 'update']);
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy']);
    Route::post('/produk/import', [ProdukController::class, 'import']);

    Route::post('/rating/import', [RatingController::class, 'import']);
});

// ------------------------
// UNTUK USER BIASA (role_id = 2)
// ------------------------
Route::middleware(['auth', 'role:2'])->group(function () {

    // RATING: CRUD 
    Route::get('/rating/create', [RatingController::class, 'create']);
    Route::post('/rating', [RatingController::class, 'store']);
    Route::get('/rating/{id}', [RatingController::class, 'edit']);
    Route::put('/rating/{id}', [RatingController::class, 'update']);
});

// ------------------------
// AKSES BERSAMA (ADMIN & USER)
// ------------------------
Route::middleware(['auth', 'role:1,2'])->group(function () {
    // PEMBELI: CRUD
    Route::get('/pembeli', [PembeliController::class, 'index']);
    Route::get('/pembeli/create', [PembeliController::class, 'create']);
    Route::post('/pembeli', [PembeliController::class, 'store']);
    Route::get('/pembeli/{id}', [PembeliController::class, 'edit']);
    Route::put('/pembeli/{id}', [PembeliController::class, 'update']);
    Route::delete('/pembeli/{id}', [PembeliController::class, 'destroy']);
    Route::post('/pembeli/import', [PembeliController::class, 'import']);

    // PRODUK:TAMPILAN
    Route::get('/produk', [ProdukController::class, 'index']);

    // RATING
    Route::get('/rating', [RatingController::class, 'index']);
    Route::delete('/rating/{id}', [RatingController::class, 'destroy']);

    // REKOMENDASI
    Route::get('/rekomendasi', [RekomendasiController::class, 'index'])->name('rekomendasi.index');
    Route::get('/rekomendasi/show/{id}/{selected?}', [RekomendasiController::class, 'show'])->name('rekomendasi.show');
});


/*
|--------------------------------------------------------------------------
| HALAMAN TETAP
|--------------------------------------------------------------------------
*/
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
