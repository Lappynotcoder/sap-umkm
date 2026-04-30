<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalisisController;

Route::get('/', [AnalisisController::class, 'index'])->name('home');
Route::get('/tentang', [AnalisisController::class, 'tentang'])->name('tentang');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('riwayat');
    })->name('dashboard');

    Route::get('/upload',  [AnalisisController::class, 'showUpload'])->name('upload.form');
    Route::post('/proses', [AnalisisController::class, 'prosesUpload'])->name('upload.proses');
    Route::get('/dashboard/{id}', [AnalisisController::class, 'dashboard'])->name('dashboard.show');
    Route::get('/riwayat', [AnalisisController::class, 'riwayat'])->name('riwayat');
    Route::get('/laporan', [AnalisisController::class, 'laporan'])->name('laporan');
    Route::get('/analisis', [AnalisisController::class, 'analisis'])->name('analisis');
    Route::delete('/laporan/{id}', [AnalisisController::class, 'hapus'])->name('laporan.hapus');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
