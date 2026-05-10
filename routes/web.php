<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Storage;

// Fallback storage route (for shared hosting where symlinks don't work)
Route::get('/file/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('path', '.*')->name('storage.file');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Registration
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister'])->name('register.post');
    Route::get('/register/verify', [AuthController::class, 'showVerifyRegister'])->name('register.verify');
    Route::post('/register/verify', [AuthController::class, 'verifyRegister'])->name('register.verify.post');

    // Forgot Password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetOtp'])->name('password.email');
    Route::get('/forgot-password/verify', [AuthController::class, 'showVerifyReset'])->name('password.verify');
    Route::post('/forgot-password/verify', [AuthController::class, 'processResetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/', function () {
    return redirect()->route('login');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');

    // Semua user bisa tambah barang (tapi butuh approval jika role user)
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');

    // Laporan & Export
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

    // Riwayat User
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

    // API
    Route::get('/api/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.markRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('/api/items/search', [ItemController::class, 'search'])->name('items.search');

    // Admin only
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
        Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
        Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
        Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
        Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
        Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
        
        // Approvals (Transaksi + Item)
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
        Route::post('/approvals/{transaction}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{transaction}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
        Route::post('/approvals/item/{item}/approve', [ApprovalController::class, 'approveItem'])->name('approvals.approveItem');
        Route::post('/approvals/item/{item}/reject', [ApprovalController::class, 'rejectItem'])->name('approvals.rejectItem');

        // Category Management
        Route::resource('categories', \App\Http\Controllers\CategoryController::class)->except(['create', 'show', 'edit']);
    });

    // Super Admin only
    Route::middleware(['role:superadmin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
