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
use App\Http\Controllers\StockController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

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

    // Profile (semua role)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Dashboard (semua role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 1. ADMIN ONLY: Mengelola User & Mengelola Jenis Tanaman
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('plant-types', PlantTypeController::class)->except(['create', 'edit', 'show']);
    });

    // 2. ADMIN & PERSEDIAAN: Mengelola Bahan Baku, Supplier, & Pembelian Bahan Baku
    Route::middleware(['role:admin,persediaan'])->group(function () {
        Route::resource('suppliers', SupplierController::class)->except(['create', 'edit', 'show']);
        Route::resource('purchases', PurchaseController::class)->except(['create', 'edit']);
        Route::patch('purchases/{purchase}/status', [PurchaseController::class, 'updateStatus'])->name('purchases.updateStatus');
        Route::resource('materials', MaterialController::class)->except(['show']);
    });

    // 3. ADMIN & PETANI: Mengelola Penanaman, Panen, Perawatan, Pengecekan Air
    Route::middleware(['role:admin,petani'])->group(function () {
        Route::post('plantings', [PlantingController::class, 'store'])->name('plantings.store');
        Route::put('plantings/{planting}', [PlantingController::class, 'update'])->name('plantings.update');
        Route::delete('plantings/{planting}', [PlantingController::class, 'destroy'])->name('plantings.destroy');
        Route::patch('plantings/{planting}/status', [PlantingController::class, 'updateStatus'])->name('plantings.updateStatus');

        Route::post('maintenance-logs', [MaintenanceLogController::class, 'store'])->name('maintenance-logs.store');
        Route::delete('maintenance-logs/{maintenanceLog}', [MaintenanceLogController::class, 'destroy'])->name('maintenance-logs.destroy');

        Route::post('water-quality-logs', [WaterQualityLogController::class, 'store'])->name('water-quality-logs.store');
        Route::delete('water-quality-logs/{waterQualityLog}', [WaterQualityLogController::class, 'destroy'])->name('water-quality-logs.destroy');

        Route::post('harvests', [HarvestController::class, 'store'])->name('harvests.store');
        Route::delete('harvests/{harvest}', [HarvestController::class, 'destroy'])->name('harvests.destroy');

        Route::post('material-usages', [MaterialUsageController::class, 'store'])->name('material-usages.store');
        Route::delete('material-usages/{materialUsage}', [MaterialUsageController::class, 'destroy'])->name('material-usages.destroy');
    });

    // Read Penanaman & Panen (Admin, Petani, & Pimpinan untuk "Melihat Penanaman & Panen")
    Route::middleware(['role:admin,petani,pimpinan'])->group(function () {
        Route::get('plantings', [PlantingController::class, 'index'])->name('plantings.index');
        Route::get('harvests', [HarvestController::class, 'index'])->name('harvests.index');
    });

    // 4. ADMIN, PERSEDIAAN, & PIMPINAN: Melihat & Mengelola Stok
    Route::middleware(['role:admin,persediaan,pimpinan'])->group(function () {
        Route::prefix('stocks')->name('stocks.')->group(function () {
            Route::get('/', [StockController::class, 'index'])->name('index');
            Route::get('/summary', [StockController::class, 'summary'])->name('summary');
            Route::get('/mutations', [StockController::class, 'mutations'])->name('mutations');
            Route::get('/opname', [StockController::class, 'opname'])->name('opname');
            // Write Stok Opname (hanya Admin & Persediaan)
            Route::middleware(['role:admin,persediaan'])->group(function () {
                Route::post('/opname', [StockController::class, 'storeOpname'])->name('opname.store');
                Route::put('/opname/{stockAdjustment}', [StockController::class, 'updateOpname'])->name('opname.update');
                Route::delete('/opname/{stockAdjustment}', [StockController::class, 'destroyOpname'])->name('opname.destroy');
            });
        });

        // (Scanner endpoints moved to TransactionController)
        Route::post('transactions/scanner/find', [TransactionController::class, 'findForScanner'])->name('transactions.scanner.find');
        Route::patch('transactions/scanner/confirm/{transaction}', [TransactionController::class, 'confirmForScanner'])->name('transactions.scanner.confirm');
    });

    // 5. ADMIN, KEUANGAN, & PIMPINAN: Keuangan (Kas)
    // Pimpinan: Melihat Kas (Read-only); Admin & Keuangan: Mengelola Keuangan (Read & Write)
    Route::middleware(['role:admin,keuangan,pimpinan'])->group(function () {
        Route::prefix('finance')->name('finance.')->group(function () {
            Route::get('/', [CashFlowController::class, 'index'])->name('index');
            Route::middleware(['role:admin,keuangan'])->group(function () {
                Route::post('/', [CashFlowController::class, 'store'])->name('store');
                Route::put('/{cashFlow}', [CashFlowController::class, 'update'])->name('update');
                Route::delete('/{cashFlow}', [CashFlowController::class, 'destroy'])->name('destroy');
            });
        });
    });

    // 6. LAPORAN: Melihat Laporan (Admin & Pimpinan)
    Route::middleware(['role:admin,pimpinan'])->group(function () {
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/plantings', [ReportController::class, 'plantingReport'])->name('plantings');
            Route::get('/plantings/pdf', [ReportController::class, 'plantingPdf'])->name('plantings.pdf');
            Route::get('/harvests', [ReportController::class, 'harvestReport'])->name('harvests');
            Route::get('/harvests/pdf', [ReportController::class, 'harvestPdf'])->name('harvests.pdf');
            Route::get('/materials', [ReportController::class, 'materialReport'])->name('materials');
            Route::get('/materials/pdf', [ReportController::class, 'materialPdf'])->name('materials.pdf');
        });
    });

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
    Route::patch('/orders/{transaction}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});
