<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LoyaltyPointController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\OrderController; // added
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return view('welcome');
});

use Illuminate\Support\Facades\Auth;

Route::get('/dashboard', function () {
    $user = Auth::user();
    if (! $user || ! in_array($user->user_type, ['admin','staff'])) {
        abort(403);
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/me', [ProfileController::class, 'show'])->name('profile.show');
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

    // Menu Items routes (Public read, Admin/Staff write)
    Route::get('/menu-items', [MenuItemController::class, 'index'])->name('menu-items.index');
    Route::get('/menu-items/{menuItem}', [MenuItemController::class, 'show'])->name('menu-items.show');

    // Admin & Staff only
    Route::get('/menu-items/create', [MenuItemController::class, 'create'])->name('menu-items.create');
    Route::post('/menu-items', [MenuItemController::class, 'store'])->name('menu-items.store');
    Route::get('/menu-items/{menuItem}/edit', [MenuItemController::class, 'edit'])->name('menu-items.edit');
    Route::patch('/menu-items/{menuItem}', [MenuItemController::class, 'update'])->name('menu-items.update');
    Route::delete('/menu-items/{menuItem}', [MenuItemController::class, 'destroy'])->name('menu-items.destroy');

    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/history', [ScheduleController::class, 'history'])->name('schedules.history');
    Route::post('/schedules', [ScheduleController::class, 'assign'])->name('schedules.assign'); // admin only
    Route::post('/schedules/toggle-holiday', [ScheduleController::class, 'toggleHoliday'])->name('schedules.toggleHoliday'); // admin only
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy'); // admin only

    // Reservations (ensure these exist)
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::patch('/reservations/{reservation}/complete', [ReservationController::class, 'complete'])->name('reservations.complete');
    Route::get('/reservations/history', [ReservationController::class, 'history'])->name('reservations.history');

    // Table-level status toggles (admin/cashier)
    Route::patch('/tables/{table}/maintenance', [TableController::class, 'setMaintenance'])->name('tables.maintenance');
    Route::patch('/tables/{table}/available', [TableController::class, 'setAvailable'])->name('tables.available');

    // New routes to manage maintenance notice / delete affected reservations
    Route::post('/tables/maintenance/delete-affected', [TableController::class, 'deleteAffectedReservations'])->name('tables.maintenance.delete');
    Route::post('/tables/maintenance/clear-notice', [TableController::class, 'clearMaintenanceNotice'])->name('tables.maintenance.clear');

    // Grid view for tables
    Route::get('/tables', [TableController::class, 'index'])->name('tables.grid');

    // History reservasi (read-only)
    Route::get('/reservations/history', [ReservationController::class, 'history'])->name('reservations.history');

    // Rename Table (edit kategori & nomor) admin/cashier
    Route::patch('/tables/{table}/rename', [TableController::class, 'rename'])->name('tables.rename'); // admin/cashier

    // Orders management (Admin & Cashier)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show'); // returns JSON for popover
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // Customer order tracking (customer only)
    Route::get('/my-orders', [OrderController::class, 'track'])->name('orders.track');
    Route::get('/my-orders/{order}', [OrderController::class, 'trackShow'])->name('orders.track.show');
    Route::post('/my-orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.track.complete');
    // Keranjang
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    // Route untuk melihat keranjang
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    // Route untuk memproses checkout
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

require __DIR__ . '/auth.php';
