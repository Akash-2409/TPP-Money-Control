<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ledger\IncomeController;
use App\Http\Controllers\Ledger\ExpenseController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\ProductionController;
// use App\Http\Controllers\Workers\WorkerController;
use App\Http\Controllers\Workers\WorkerAdvanceController;
use App\Http\Controllers\Reports\WorkerReportController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\WorkerTransactionController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProductReportController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MaterialController;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () {
    return view('auth.login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function(){
    Route::resource('incomes', IncomeController::class)->only(['index','create','store','destroy'])->middleware('can:manage sales');
    Route::resource('expenses', ExpenseController::class)->only(['index','create','store','destroy'])->middleware('can:manage purchases');
    Route::resource('products', ProductController::class)->middleware('can:manage inventory');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index')->middleware('can:manage sales');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store')->middleware('can:manage sales');
    Route::get('/transactions/{entry}/edit', [TransactionController::class, 'edit'])->name('transactions.edit')->middleware('can:manage sales');
    Route::put('/transactions/{entry}', [TransactionController::class, 'update'])->name('transactions.update')->middleware('can:manage sales');
    Route::delete('/transactions/{entry}', [TransactionController::class, 'destroy'])->name('transactions.destroy')->middleware('can:manage sales');
    Route::get('/production', [ProductionController::class, 'index'])->name('production.index')->middleware('can:manage inventory');
    Route::post('/production', [ProductionController::class, 'store'])->name('production.store')->middleware('can:manage inventory');
    
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index')->middleware('can:manage inventory');
    Route::post('/inventory/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust')->middleware('can:manage inventory');

    Route::post('/worker-transaction', [WorkerTransactionController::class, 'store'])->name('worker.transaction.store');
    Route::get('/workers/monthly-report', [WorkerTransactionController::class, 'monthlyReport'])->name('workers.monthly');
    Route::resource('workers', WorkerController::class);

    Route::resource('sales', SaleController::class)->only(['index','create','store'])->middleware('can:manage sales');
    Route::get('/sales/{sale}/invoice', [SaleController::class, 'invoice'])->name('sales.invoice')->middleware('can:manage sales');

    Route::get('/report/product-monthly', [ProductReportController::class, 'monthly'])->name('report.product.monthly')->middleware('can:view reports');
    Route::get('/report/stock-ledger', [ProductReportController::class, 'stockLedger'])->name('report.stock.ledger')->middleware('can:view reports');
    Route::get('/report/stock-ledger/export', [ProductReportController::class, 'exportStockLedger'])->name('report.stock.export')->middleware('can:view reports');

    Route::resource('purchases', PurchaseController::class)->only(['index','create','store'])->middleware('can:manage purchases');
    Route::resource('materials', MaterialController::class)->only(['index','create','store'])->middleware('can:manage inventory');
    
    Route::resource('parties', App\Http\Controllers\PartyController::class)->middleware('role_or_permission:superadmin');
    Route::get('/api/parties/{party}/details', [App\Http\Controllers\PartyController::class, 'getPartyDetails'])->name('parties.details')->middleware('role_or_permission:superadmin');

    // User & Rights Management
    Route::resource('users', App\Http\Controllers\UserController::class)
         ->except(['show', 'destroy'])
         ->middleware('role:superadmin');

});

// Route::resource('workers', WorkerController::class);

Route::get('worker-advances', [WorkerAdvanceController::class, 'index'])->name('worker-advances.index');
Route::get('worker-advances/create', [WorkerAdvanceController::class, 'create'])->name('worker-advances.create');
Route::post('worker-advances', [WorkerAdvanceController::class, 'store'])->name('worker-advances.store');
Route::delete('worker-advances/{workerTransaction}', [WorkerAdvanceController::class, 'destroy'])->name('worker-advances.destroy');

Route::get('reports/workers/monthly', [WorkerReportController::class, 'monthly'])
    ->name('reports.workers.monthly');

Route::resource('categories', CategoryController::class);

Route::view('/test', 'test');

Route::get('/test-pdf', function () {
    $pdf = Pdf::loadHTML('<h1>PDF Working</h1>');
    return $pdf->download('test.pdf');
});


require __DIR__.'/auth.php';
