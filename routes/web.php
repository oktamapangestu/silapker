<?php

use App\Http\Controllers\Admin\LaporanKerjaController as AdminLaporanKerjaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanKerjaController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth.central')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::redirect('/', '/laporan');
    Route::get('laporan/tanggal-tersedia', [LaporanKerjaController::class, 'tanggalTersedia'])->name('laporan.tanggal-tersedia');
    Route::resource('laporan', LaporanKerjaController::class)->except(['show']);

    Route::prefix('admin')->name('admin.')->middleware('admin.only')->group(function () {
        Route::get('laporan', [AdminLaporanKerjaController::class, 'index'])->name('laporan.index');
        Route::get('laporan/{laporan}', [AdminLaporanKerjaController::class, 'show'])->name('laporan.show');
        Route::post('laporan/{laporan}/review', [AdminLaporanKerjaController::class, 'review'])->name('laporan.review');
    });
});
