<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantTypeController;
use App\Http\Controllers\PlantingController;
use App\Http\Controllers\MaintenanceLogController;
use App\Http\Controllers\WaterQualityLogController;
use App\Http\Controllers\HarvestController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

// Public routes
Route::get('/', fn() => redirect()->route('login'));

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Farm Management — accessible by admin & petani
    Route::middleware(['role:admin,petani'])->group(function () {
        Route::resource('plant-types', PlantTypeController::class);
        Route::resource('plantings', PlantingController::class);
        Route::patch('plantings/{planting}/status', [PlantingController::class, 'updateStatus'])->name('plantings.updateStatus');
        Route::resource('maintenance-logs', MaintenanceLogController::class)->except(['edit', 'update', 'destroy']);
        Route::resource('water-quality-logs', WaterQualityLogController::class)->except(['edit', 'update', 'destroy']);
        Route::resource('harvests', HarvestController::class)->except(['edit', 'update']);
    });

    // Products — accessible by all roles
    Route::resource('products', ProductController::class)->only(['index', 'show', 'edit', 'update']);

    // Transactions — accessible by all roles
    Route::resource('transactions', TransactionController::class)->only(['index', 'store', 'show']);
    Route::patch('transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');

});
