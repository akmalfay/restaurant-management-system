<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LoyaltyPointController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StockMovementController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Staff routes
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/{user}', [StaffController::class, 'show'])->name('staff.show');

    // Admin only routes
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store'); // Tambah staff
    Route::get('/staff/{user}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::patch('/staff/{user}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{user}', [StaffController::class, 'destroy'])->name('staff.destroy');

    // Customer routes (Admin only)
    Route::get('/customers', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customers/{user}', [CustomerController::class, 'show'])->name('customer.show');
    Route::get('/customers/{user}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::patch('/customers/{user}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('/customers/{user}', [CustomerController::class, 'destroy'])->name('customer.destroy');
    Route::post('/customers/{user}/adjust-points', [CustomerController::class, 'adjustPoints'])->name('customer.adjustPoints');

    // Loyalty Points routes (Admin only)
    Route::get('/loyalty-points/{loyaltyPoint}/edit', [LoyaltyPointController::class, 'edit'])->name('loyaltyPoint.edit');
    Route::patch('/loyalty-points/{loyaltyPoint}', [LoyaltyPointController::class, 'update'])->name('loyaltyPoint.update');
    Route::delete('/loyalty-points/{loyaltyPoint}', [LoyaltyPointController::class, 'destroy'])->name('loyaltyPoint.destroy');

    // Inventory routes (Admin & Staff)
    Route::resource('inventory', InventoryController::class)->except(['create']);

    // Stock Movement routes (Admin & Staff)
    // Gunakan nama 'stock-movements' untuk konsistensi dengan view
    Route::post('/inventory/{inventory}/stock-movements', [StockMovementController::class, 'store'])
        ->name('stock-movements.store');
    Route::get('/stock-movements/{stockMovement}/edit', [StockMovementController::class, 'edit'])
        ->name('stock-movements.edit');
    Route::patch('/stock-movements/{stockMovement}', [StockMovementController::class, 'update'])
        ->name('stock-movements.update');
    Route::delete('/stock-movements/{stockMovement}', [StockMovementController::class, 'destroy'])
        ->name('stock-movements.destroy');
});

require __DIR__ . '/auth.php';
