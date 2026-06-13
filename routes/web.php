<?php

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

Route::get('/', function () {
    if (Auth::check()) {

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('owner')) {
            return redirect()->route('owner.dashboard');
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
        Route::get('/products/supplier/{supplier}', [ProductController::class, 'supplierProducts'])
            ->name('products.supplier');

        // kelola supplier
        Route::resource('suppliers', SupplierController::class);

        // monitoring transaksi
        Route::get('/monitoring-transaksi', [TransactionMonitoringController::class, 'index'])
            ->name('monitoring-transactions.index');
        Route::get('/monitoring-transaksi/{transaction}', [TransactionMonitoringController::class, 'show'])
            ->name('monitoring-transactions.show');

        // monitoring stok
        Route::get('/monitoring-stok', [StockMonitoringController::class, 'index'])
            ->name('monitoring-stocks.index');

        // laporan transaksi
        Route::get('/laporan-transaksi', [TransactionReportController::class, 'index'])
            ->name('reports.transactions.index');

        // print pdf laporan transaksi
        Route::get('/laporan-transaksi/pdf', [TransactionReportController::class, 'pdf'])
            ->name('reports.transactions.pdf');

        // laporan stok
        Route::get('/laporan-stok', [StockReportController::class, 'index'])
            ->name('reports.stocks.index');

        // print pdf laporan transaksi
        Route::get('/laporan-stok/pdf', [StockReportController::class, 'pdf'])
            ->name('reports.stocks.pdf');
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
