<?php

use App\Http\Controllers\Manager\CashierController;
use App\Http\Controllers\Owner\BranchController;
use App\Http\Controllers\Owner\CategoryController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\ManagerController;
use App\Http\Controllers\Owner\MonitoringController;
use App\Http\Controllers\Owner\ProductController;
use App\Http\Controllers\Owner\StockMonitoringController;
use App\Http\Controllers\Owner\StockReportController;
use App\Http\Controllers\Owner\SupplierController;
use App\Http\Controllers\Owner\TransactionMonitoringController;
use App\Http\Controllers\Owner\TransactionReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Warehouse\IncomingGoodController;
use App\Http\Controllers\Warehouse\StockController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Manager\MonitoringStockController;
use App\Http\Controllers\Manager\MonitoringTransactionController;
use App\Http\Controllers\Manager\StockController as ManagerStockController;
use App\Http\Controllers\Manager\SupervisorController;
use App\Http\Controllers\Manager\TransactionController;
use App\Http\Controllers\Manager\WarehouseController;
use App\Http\Controllers\Supervisor\DashboardController as SupervisorDashboardController;
use App\Http\Controllers\Supervisor\StockController as SupervisorStockController;
use App\Http\Controllers\Supervisor\TransactionController as SupervisorTransactionController;

Route::get('/', function () {
    if (Auth::check()) {

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('owner')) {
            return redirect()->route('owner.dashboard');
        }

        if ($user->hasRole('manager')) {
            return redirect()->route('manager.dashboard');
        }

        if ($user->hasRole('supervisor')) {
            return redirect()->route('supervisor.dashboard');
        }

        if ($user->hasRole('cashier')) {
            return redirect()->route('cashier.dashboard');
        }

        if ($user->hasRole('warehouse')) {
            return redirect()->route('warehouse.dashboard');
        }

        abort(403, 'Role user belum memiliki akses.');
    }

    return redirect()->route('login');
});


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// role owner
Route::middleware(['auth', 'role:owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {
        // dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        // kelola cabang
        Route::resource('branches', BranchController::class);
        // kelola manager
        Route::resource('managers', ManagerController::class);
        // kelola kategori
        Route::resource('categories', CategoryController::class);
        // kelola produk
        Route::resource('products', ProductController::class);
        // detail produk per cabang
        Route::get('/products/supplier/{supplier}', [ProductController::class, 'supplierProducts'])->name('products.supplier');
        // kelola supplier
        Route::resource('suppliers', SupplierController::class);
        // monitoring transaksi
        Route::get('/monitoring-transaksi', [TransactionMonitoringController::class, 'index'])->name('monitoring-transactions.index');
        Route::get('/monitoring-transaksi/{transaction}', [TransactionMonitoringController::class, 'show'])->name('monitoring-transactions.show');
        Route::get('/monitoring-transactions/pdf', [TransactionMonitoringController::class, 'pdf'])->name('monitoring-transactions.pdf');
        // monitoring stok
        Route::get('/monitoring-stok', [StockMonitoringController::class, 'index'])->name('monitoring-stocks.index');
        Route::get('/monitoring-stocks/pdf', [StockMonitoringController::class, 'pdf'])->name('monitoring-stocks.pdf');
    });

// role manager
Route::middleware(['auth', 'role:manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {
        Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
        Route::resource('supervisors', SupervisorController::class);
        Route::resource('cashiers', CashierController::class);
        Route::resource('warehouses', WarehouseController::class);
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/pdf', [TransactionController::class, 'pdf'])->name('transactions.pdf');
        Route::get('/stocks', [ManagerStockController::class, 'index'])->name('stocks.index');
        Route::get('/stocks/pdf', [ManagerStockController::class, 'pdf'])->name('stocks.pdf');
    });

// role supervisor
Route::middleware(['auth', 'role:supervisor'])
    ->prefix('supervisor')
    ->name('supervisor.')
    ->group(function () {
        Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/transactions', [SupervisorTransactionController::class, 'index'])->name('transactions.index');
        Route::get('/stocks', [SupervisorStockController::class, 'index'])->name('stocks.index');
    });

// role cashier
Route::middleware(['auth', 'role:cashier'])
    ->prefix('cashier')
    ->name('cashier.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('transactions', TransactionController::class)
            ->only([
                'index',
                'store',
                'show',
            ]);
    });

// role warehouse
Route::middleware(['auth', 'role:warehouse'])
    ->prefix('warehouse')
    ->name('warehouse.')
    ->group(function () {

        // dashboard
        Route::view('/dashboard', 'warehouse.dashboard')->name('dashboard');

        // stok barang
        Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');

        // barang masuk
        Route::resource('incoming-goods', IncomingGoodController::class)->except(['show', 'edit', 'update']);
    });

require __DIR__ . '/auth.php';
