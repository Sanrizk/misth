<?php
// semangat
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
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialUsageController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;

// Public routes
Route::get('/', [LandingController::class, 'index'])->name('landing');

use App\Http\Controllers\RegisterController;

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Customer registration
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
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
        Route::resource('users', \App\Http\Controllers\UserController::class)->except(['show', 'index']);
    });

    // Admin & Petani: view only
    Route::middleware(['role:admin,petani'])->group(function () {
        Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [\App\Http\Controllers\UserController::class, 'show'])->name('users.show');
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

        Route::resource('suppliers', SupplierController::class)->except(['create', 'edit', 'show']);
        Route::resource('purchases', PurchaseController::class)->except(['create', 'edit', 'show']);
        Route::patch('purchases/{purchase}/status', [PurchaseController::class, 'updateStatus'])->name('purchases.updateStatus');

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [\App\Http\Controllers\ReportController::class, 'index'])->name('index');
            Route::get('/plantings', [\App\Http\Controllers\ReportController::class, 'plantingReport'])->name('plantings');
            Route::get('/harvests', [\App\Http\Controllers\ReportController::class, 'harvestReport'])->name('harvests');
            Route::get('/transactions', [\App\Http\Controllers\ReportController::class, 'transactionReport'])->name('transactions');
            Route::get('/materials', [\App\Http\Controllers\ReportController::class, 'materialReport'])->name('materials');
        });

        // (Scanner endpoints moved to TransactionController)
        Route::post('transactions/scanner/find', [TransactionController::class, 'findForScanner'])->name('transactions.scanner.find');
        Route::patch('transactions/scanner/confirm/{transaction}', [TransactionController::class, 'confirmForScanner'])->name('transactions.scanner.confirm');
    });

    // Products — accessible by all roles
    Route::resource('products', ProductController::class)->only(['index', 'show', 'edit', 'update']);

    // Transactions — accessible by all roles
    Route::resource('transactions', TransactionController::class);
    Route::patch('transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');

});

use App\Http\Controllers\Customer\StoreController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController;

// Public Storefront (Bisa diakses guest dan semua role)
Route::prefix('store')->name('store.')->group(function () {
    Route::get('/', [StoreController::class, 'index'])->name('index');
    Route::get('/product/{id}', [StoreController::class, 'show'])->name('product.show');
});

// Authenticated & Customer Only
Route::middleware(['auth', 'role:customer'])->prefix('store')->name('store.')->group(function () {
    // Keranjang
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{transaction}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Riwayat Order
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{transaction}', [OrderController::class, 'show'])->name('orders.show');
});
