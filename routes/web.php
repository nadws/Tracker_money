<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// ── Redirect root ke dashboard ───────────────────────────────────
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ── Semua route di bawah memerlukan login ────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Transaksi – resource route (CRUD lengkap)
    Route::resource('transactions', TransactionController::class);
    Route::resource('categories', CategoryController::class)->except('show');

    // Profile (dari Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Route autentikasi (dari Laravel Breeze) ──────────────────────
require __DIR__ . '/auth.php';
