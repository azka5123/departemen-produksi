<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginControler;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;

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

Route::get('/login', [LoginControler::class, 'show'])->name('login-show');
Route::post('/login-submit', [LoginControler::class, 'login_submit'])->name('login-submit');
Route::get('/logout', [LoginControler::class, 'logout'])->name('logout');

Route::get('/forget', [LoginControler::class, 'forget'])->name('forget');
Route::post('/forget/submit', [LoginControler::class, 'forget_submit'])->name('forget_submit');
Route::get('/reset-password/{token}/{email}', [LoginControler::class, 'reset_password'])->name('reset_password');
Route::post('/reset-submit', [LoginControler::class, 'reset_submit'])->name('reset_submit');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect('/dashboard');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => ['role:admin']], function () {
        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('user.index');
            Route::get('/create', [UserController::class, 'create'])->name('user.create');
            Route::post('/store', [UserController::class, 'store'])->name('user.store');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
            Route::post('/update/{id}', [UserController::class, 'update'])->name('user.update');
            Route::get('/destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');
        });

        Route::prefix('barang')->group(function () {
            Route::get('/', [BarangController::class, 'index'])->name('barang.index');
            Route::get('/create', [BarangController::class, 'create'])->name('barang.create');
            Route::post('/store', [BarangController::class, 'store'])->name('barang.store');
            Route::get('/edit/{id}', [BarangController::class, 'edit'])->name('barang.edit');
            Route::post('/update/{id}', [BarangController::class, 'update'])->name('barang.update');
            Route::get('/destroy/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
        });
    });

    Route::prefix('transaksi')->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/create', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('/store', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('/edit/{id}', [TransaksiController::class, 'edit'])->name('transaksi.edit');
        Route::post('/update/{id}', [TransaksiController::class, 'update'])->name('transaksi.update');
        Route::get('/destroy/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
    });
});
