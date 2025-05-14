<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

//Auth
Route::get('/', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(\App\Http\Middleware\RoleMiddleware::class);

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/pembeli', [PembeliController::class, 'index'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::get('/pembeli/create', [PembeliController::class, 'create'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::post('/pembeli', [PembeliController::class, 'store'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::get('/pembeli/{id}', [PembeliController::class, 'edit'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::put('/pembeli/{id}', [PembeliController::class, 'update'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::delete('/pembeli/{id}', [PembeliController::class, 'destroy'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::post('/pembeli/import', [PembeliController::class, 'import'])->middleware(\App\Http\Middleware\RoleMiddleware::class);

Route::get('/produk', [ProdukController::class, 'index'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::get('/produk/create', [ProdukController::class, 'create'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::post('/produk', [ProdukController::class, 'store'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::get('/produk/{id}', [ProdukController::class, 'edit'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::put('/produk/{id}', [ProdukController::class, 'update'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::post('/produk/import', [ProdukController::class, 'import'])->middleware(\App\Http\Middleware\RoleMiddleware::class);

Route::get('/rating', [RatingController::class, 'index'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::get('/rating/create', [RatingController::class, 'create'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::post('/rating', [RatingController::class, 'store'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::get('/rating/{id}', [RatingController::class, 'edit'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::put('/rating/{id}', [RatingController::class, 'update'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::delete('/rating/{id}', [RatingController::class, 'destroy'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::post('/rating/import', [RatingController::class, 'import'])->middleware(\App\Http\Middleware\RoleMiddleware::class);

Route::get('/rekomendasi', [RekomendasiController::class, 'index'])
    ->middleware(\App\Http\Middleware\RoleMiddleware::class)
    ->name('rekomendasi.index');
Route::get('/rekomendasi/create', [RekomendasiController::class, 'create'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::post('/rekomendasi', [RekomendasiController::class, 'store'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::get('/rekomendasi/{id}', [RekomendasiController::class, 'edit'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::put('/rekomendasi/{id}', [RekomendasiController::class, 'update'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::delete('/rekomendasi/{id}', [RekomendasiController::class, 'destroy'])->middleware(\App\Http\Middleware\RoleMiddleware::class);
Route::get('/rekomendasi/show/{id}', [RekomendasiController::class, 'show'])->name('rekomendasi.show')->middleware(\App\Http\Middleware\RoleMiddleware::class);
