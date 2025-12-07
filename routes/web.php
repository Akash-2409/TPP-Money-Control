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

Route::get('/', function () {
    return view('welcome');
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
    Route::resource('incomes', IncomeController::class)->only(['index','create','store','destroy']);
    Route::resource('expenses', ExpenseController::class)->only(['index','create','store','destroy']);
    Route::resource('products', ProductController::class);
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::delete('/transactions/{entry}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/production', [ProductionController::class, 'index'])->name('production.index');
    Route::post('/production', [ProductionController::class, 'store'])->name('production.store');
    // Route::resource('productions', ProductionController::class)->only(['index', 'store', 'destroy']);
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');

    Route::post('/inventory/adjust', [InventoryController::class, 'adjust'])
         ->name('inventory.adjust');


    Route::post('/worker-transaction', 
        [WorkerTransactionController::class, 'store']
    )->name('worker.transaction.store');

    Route::get('/workers/monthly-report', 
        [WorkerTransactionController::class, 'monthlyReport']
    )->name('workers.monthly');

    Route::resource('workers', WorkerController::class);


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


require __DIR__.'/auth.php';
