<?php

use App\Http\Controllers\GarzonController;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CashBoxController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\Admin\PanelAdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CashBoxExport;

Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');

Route::get('/dashboard', function () {
    switch (auth()->user()->role) {
        case 'garzon':
            return redirect()->route('garzon.index');
        case 'admin':
            return redirect()->route('admin.panel');
        default:
            return redirect()->route('login');
    }
})->name('dashboard');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Rutas compartidas entre Garzon y Admin
    Route::middleware(['role:garzon,admin'])->group(function () {
        // Rutas de perfil
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Rutas de garzon (accesibles también para admin)
        Route::get('/garzon', [GarzonController::class, 'index'])->name('garzon.index');

        // Rutas de orders
        Route::resource('/garzon/orders', OrderController::class, ['as' => 'garzon']);
        Route::get('/garzon/orders/{order}/edit', [OrderController::class, 'edit'])->name('garzon.orders.edit');
        Route::get('/garzon/orders/create/{table}', [OrderController::class, 'create'])->name('garzon.orders.create');
        Route::patch('/garzon/orders/{order}/complete', [OrderController::class, 'complete'])->name('garzon.orders.complete');
        Route::patch('/garzon/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('garzon.orders.cancel');
        Route::get('/garzon/orders/{order}/print', [OrderController::class, 'print'])->name('garzon.orders.print');
        Route::get('/garzon/orders/{order}/printPDF', [OrderController::class, 'printPDF'])->name('garzon.orders.printPDF');

        // Rutas de products
        Route::get('/garzon/products', [ProductController::class, 'index'])->name('garzon.products.index');
        Route::get('/garzon/products/{product}', [ProductController::class, 'show'])->name('garzon.products.show');
        Route::get('/garzon/products/{product}/edit', [ProductController::class, 'edit'])->name('garzon.products.edit');
        Route::put('/garzon/products/{product}', [ProductController::class, 'update'])->name('garzon.products.update');

        // Rutas de tables
        Route::resource('/garzon/tables', TableController::class, ['as' => 'garzon']);
        Route::post('/garzon/tables/{table}/occupy', [TableController::class, 'occupy'])->name('garzon.tables.occupy');

        // Rutas de inventory
        Route::resource('/garzon/inventory', InventoryController::class, ['as' => 'garzon']);

        // Rutas para la caja
        Route::get('/garzon/cashbox', [CashBoxController::class, 'index'])->name('garzon.cashbox.index');
        Route::post('/garzon/cashbox/open', [CashBoxController::class, 'open'])->name('garzon.cashbox.open');
        Route::post('/garzon/cashbox/close', [CashBoxController::class, 'close'])->name('garzon.cashbox.close');
    });

    // Rutas específicas para Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [PanelAdminController::class, 'index'])->name('admin.panel');

        // Rutas de orders para admin
        Route::resource('/admin/orders', OrderController::class, ['as' => 'admin']);
        Route::patch('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update_status');
        Route::get('/admin/orders/create/{table}', [OrderController::class, 'create'])->name('admin.orders.create');

        // Rutas de products para admin
        Route::resource('/admin/products', ProductController::class, ['as' => 'admin']);

        // Rutas de purchases para admin
        Route::resource('/admin/purchases', PurchaseController::class, ['as' => 'admin']);
        Route::patch('/admin/purchases/{purchase}/complete', [PurchaseController::class, 'complete'])->name('admin.purchases.complete');
        Route::patch('/admin/purchases/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('admin.purchases.cancel');

        // Rutas de tables para admin
        Route::resource('/admin/tables', TableController::class, ['as' => 'admin']);
        Route::post('/admin/tables/{table}/occupy', [TableController::class, 'occupy'])->name('admin.tables.occupy');

        // Rutas de inventory para admin
        Route::resource('/admin/inventory', InventoryController::class, ['as' => 'admin']);
        Route::patch('/admin/inventory/{inventory}/adjust', [InventoryController::class, 'adjustStock'])->name('admin.inventory.adjust');

        // Rutas para los menús
        Route::resource('/admin/menus', MenuController::class, ['as' => 'admin']);

        // Rutas para la caja
        Route::get('/admin/cashbox', [CashBoxController::class, 'index'])->name('admin.cashbox.index');
        Route::post('/admin/cashbox/open', [CashBoxController::class, 'open'])->name('admin.cashbox.open');
        Route::post('/admin/cashbox/close', [CashBoxController::class, 'close'])->name('admin.cashbox.close');
        Route::get('/admin/cashbox/export', function () {
            return Excel::download(new CashBoxExport, 'total_dineros.xlsx');
        })->name('admin.cashbox.export');

        // Rutas de usuarios
        Route::resource('/admin/users', UserController::class);

        // Ruta para actualizar los datos de la empresa
        Route::get('/admin/vastago', [PanelAdminController::class, 'showCompanySettings'])->name('company.settings.show');
        Route::put('/admin/vastago', [PanelAdminController::class, 'updateCompanySettings'])->name('company.settings.update');

        // Rutas de vouchers
        Route::get('/admin/orders/{order}/vouchers/{area?}', [OrderController::class, 'printVoucher'])
            ->name('admin.orders.vouchers.print')
            ->where('area', 'kitchen|bar|grill|customer');
        Route::patch('/admin/orders/{order}/tip', [OrderController::class, 'updateTip'])
            ->name('admin.orders.update-tip');

        Route::put('/orders/{order}', [OrderController::class, 'update'])
            ->name('admin.orders.update');
    });
});



require __DIR__.'/auth.php';
