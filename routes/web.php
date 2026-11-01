<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantTypeController;
use App\Http\Controllers\PlantingController;
use App\Http\Controllers\MaintenanceLogController;
use App\Http\Controllers\WaterQualityLogController;
use App\Http\Controllers\HarvestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialUsageController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;

// Public root redirect
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin: full CRUD
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);
    });

    // Farm Management — accessible by admin & petani
    Route::middleware(['role:admin,petani'])->group(function () {
        Route::resource('plant-types', PlantTypeController::class)->except(['create', 'edit', 'show']);
        Route::resource('plantings', PlantingController::class)->except(['create', 'edit', 'show']);
        Route::patch('plantings/{planting}/status', [PlantingController::class, 'updateStatus'])->name('plantings.updateStatus');
        Route::post('maintenance-logs', [MaintenanceLogController::class, 'store'])->name('maintenance-logs.store');
        Route::delete('maintenance-logs/{maintenanceLog}', [MaintenanceLogController::class, 'destroy'])->name('maintenance-logs.destroy');

        Route::post('water-quality-logs', [WaterQualityLogController::class, 'store'])->name('water-quality-logs.store');
        Route::delete('water-quality-logs/{waterQualityLog}', [WaterQualityLogController::class, 'destroy'])->name('water-quality-logs.destroy');

        Route::post('harvests', [HarvestController::class, 'store'])->name('harvests.store');
        Route::delete('harvests/{harvest}', [HarvestController::class, 'destroy'])->name('harvests.destroy');
        Route::resource('materials', MaterialController::class)->except(['show']);
        Route::post('material-usages', [MaterialUsageController::class, 'store'])->name('material-usages.store');
        Route::delete('material-usages/{materialUsage}', [MaterialUsageController::class, 'destroy'])->name('material-usages.destroy');

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [\App\Http\Controllers\ReportController::class, 'index'])->name('index');
            Route::get('/plantings', [\App\Http\Controllers\ReportController::class, 'plantingReport'])->name('plantings');
            Route::get('/plantings/pdf', [\App\Http\Controllers\ReportController::class, 'plantingPdf'])->name('plantings.pdf');
            Route::get('/harvests', [\App\Http\Controllers\ReportController::class, 'harvestReport'])->name('harvests');
            Route::get('/harvests/pdf', [\App\Http\Controllers\ReportController::class, 'harvestPdf'])->name('harvests.pdf');
            Route::get('/materials', [\App\Http\Controllers\ReportController::class, 'materialReport'])->name('materials');
            Route::get('/materials/pdf', [\App\Http\Controllers\ReportController::class, 'materialPdf'])->name('materials.pdf');
        });

        // Stock Management (Cek Stok, Mutasi Stok, Stok Opname)
        Route::prefix('stocks')->name('stocks.')->group(function () {
            Route::get('/', [\App\Http\Controllers\StockController::class, 'index'])->name('index');
            Route::get('/summary', [\App\Http\Controllers\StockController::class, 'summary'])->name('summary');
            Route::get('/mutations', [\App\Http\Controllers\StockController::class, 'mutations'])->name('mutations');
            Route::get('/opname', [\App\Http\Controllers\StockController::class, 'opname'])->name('opname');
            Route::post('/opname', [\App\Http\Controllers\StockController::class, 'storeOpname'])->name('opname.store');
            Route::put('/opname/{stockAdjustment}', [\App\Http\Controllers\StockController::class, 'updateOpname'])->name('opname.update');
            Route::delete('/opname/{stockAdjustment}', [\App\Http\Controllers\StockController::class, 'destroyOpname'])->name('opname.destroy');
        });

        // Finance / Kas
        Route::prefix('finance')->name('finance.')->group(function () {
            Route::get('/', [\App\Http\Controllers\CashFlowController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\CashFlowController::class, 'store'])->name('store');
            Route::put('/{cashFlow}', [\App\Http\Controllers\CashFlowController::class, 'update'])->name('update');
            Route::delete('/{cashFlow}', [\App\Http\Controllers\CashFlowController::class, 'destroy'])->name('destroy');
        });
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('suppliers', SupplierController::class)->except(['create', 'edit', 'show']);
        Route::resource('purchases', PurchaseController::class)->except(['create', 'edit']);
        Route::patch('purchases/{purchase}/status', [PurchaseController::class, 'updateStatus'])->name('purchases.updateStatus');
        
    });

});
