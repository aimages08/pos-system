<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ==================================================
// AUTH ROUTES
// ==================================================
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==================================================
// PROTECTED ROUTES
// ==================================================
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])
            ->middleware('permission:categories.view')->name('index');
        Route::get('/create', [CategoryController::class, 'create'])
            ->middleware('permission:categories.manage')->name('create');
        Route::post('/', [CategoryController::class, 'store'])
            ->middleware('permission:categories.manage')->name('store');
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])
            ->middleware('permission:categories.manage')->name('edit');
        Route::put('/{id}', [CategoryController::class, 'update'])
            ->middleware('permission:categories.manage')->name('update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])
            ->middleware('permission:categories.manage')->name('destroy');
    });

    // Products
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])
            ->middleware('permission:products.view')->name('index');
        Route::get('/create', [ProductController::class, 'create'])
            ->middleware('permission:products.manage')->name('create');
        Route::post('/', [ProductController::class, 'store'])
            ->middleware('permission:products.manage')->name('store');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])
            ->middleware('permission:products.manage')->name('edit');
        Route::put('/{id}', [ProductController::class, 'update'])
            ->middleware('permission:products.manage')->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])
            ->middleware('permission:products.manage')->name('destroy');
    });

    // Customers
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])
            ->middleware('permission:customers.view')->name('index');
        Route::get('/create', [CustomerController::class, 'create'])
            ->middleware('permission:customers.manage')->name('create');
        Route::post('/', [CustomerController::class, 'store'])
            ->middleware('permission:customers.manage')->name('store');
        Route::get('/{id}/edit', [CustomerController::class, 'edit'])
            ->middleware('permission:customers.manage')->name('edit');
        Route::put('/{id}', [CustomerController::class, 'update'])
            ->middleware('permission:customers.manage')->name('update');
        Route::delete('/{id}', [CustomerController::class, 'destroy'])
            ->middleware('permission:customers.manage')->name('destroy');
    });

    // Suppliers
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])
            ->middleware('permission:suppliers.view')->name('index');
        Route::get('/create', [SupplierController::class, 'create'])
            ->middleware('permission:suppliers.manage')->name('create');
        Route::post('/', [SupplierController::class, 'store'])
            ->middleware('permission:suppliers.manage')->name('store');
        Route::get('/{id}/edit', [SupplierController::class, 'edit'])
            ->middleware('permission:suppliers.manage')->name('edit');
        Route::put('/{id}', [SupplierController::class, 'update'])
            ->middleware('permission:suppliers.manage')->name('update');
        Route::delete('/{id}', [SupplierController::class, 'destroy'])
            ->middleware('permission:suppliers.manage')->name('destroy');
    });

    // Purchases
    Route::prefix('purchases')->name('purchases.')->group(function () {
        Route::get('/', [PurchaseController::class, 'index'])
            ->middleware('permission:purchases.view')->name('index');
        Route::get('/create', [PurchaseController::class, 'create'])
            ->middleware('permission:purchases.manage')->name('create');
        Route::post('/', [PurchaseController::class, 'store'])
            ->middleware('permission:purchases.manage')->name('store');
        Route::get('/{id}/edit', [PurchaseController::class, 'edit'])
            ->middleware('permission:purchases.manage')->name('edit');
        Route::put('/{id}', [PurchaseController::class, 'update'])
            ->middleware('permission:purchases.manage')->name('update');
        Route::delete('/{id}', [PurchaseController::class, 'destroy'])
            ->middleware('permission:purchases.manage')->name('destroy');
    });

    // Sales
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [SaleController::class, 'index'])
            ->middleware('permission:sales.view')->name('index');
        Route::get('/create', [SaleController::class, 'create'])
            ->middleware('permission:sales.create')->name('create');
        Route::post('/', [SaleController::class, 'store'])
            ->middleware('permission:sales.create')->name('store');
        Route::get('/{id}', [SaleController::class, 'show'])
            ->middleware('permission:sales.view')->name('show');
        Route::get('/{id}/receipt', [SaleController::class, 'receipt'])
            ->middleware('permission:sales.view')->name('receipt');
        Route::delete('/{id}', [SaleController::class, 'destroy'])
            ->middleware('permission:sales.delete')->name('destroy');
        Route::get('/{id}/receipt', [SaleController::class, 'receipt'])
            ->middleware('permission:sales.view')->name('receipt');
    });

    // Customer Payments
    Route::prefix('customer-payments')->name('customer-payments.')->group(function () {
        Route::get('/', [CustomerPaymentController::class, 'index'])
            ->middleware('permission:customer-payments.view')->name('index');
        Route::get('/create', [CustomerPaymentController::class, 'create'])
            ->middleware('permission:customer-payments.manage')->name('create');
        Route::post('/', [CustomerPaymentController::class, 'store'])
            ->middleware('permission:customer-payments.manage')->name('store');
        Route::delete('/{id}', [CustomerPaymentController::class, 'destroy'])
            ->middleware('permission:customer-payments.manage')->name('destroy');
        Route::get('/unpaid-sales/{customerId}', [CustomerPaymentController::class, 'unpaidSales'])
            ->middleware('permission:customer-payments.manage')->name('unpaid-sales');
    });

    // Expenses
    Route::prefix('expenses')->name('expenses.')->group(function () {
        Route::get('/', [ExpenseController::class, 'index'])
            ->middleware('permission:expenses.view')->name('index');
        Route::get('/create', [ExpenseController::class, 'create'])
            ->middleware('permission:expenses.manage')->name('create');
        Route::post('/', [ExpenseController::class, 'store'])
            ->middleware('permission:expenses.manage')->name('store');
        Route::get('/{id}/edit', [ExpenseController::class, 'edit'])
            ->middleware('permission:expenses.manage')->name('edit');
        Route::put('/{id}', [ExpenseController::class, 'update'])
            ->middleware('permission:expenses.manage')->name('update');
        Route::delete('/{id}', [ExpenseController::class, 'destroy'])
            ->middleware('permission:expenses.manage')->name('destroy');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])
            ->middleware('permission:reports.view')->name('index');
        Route::get('/sales', [ReportController::class, 'sales'])
            ->middleware('permission:reports.view')->name('sales');
        Route::get('/purchases', [ReportController::class, 'purchases'])
            ->middleware('permission:reports.view')->name('purchases');
        Route::get('/stock', [ReportController::class, 'stock'])
            ->middleware('permission:reports.view')->name('stock');
        Route::get('/profit', [ReportController::class, 'profit'])
            ->middleware('permission:reports.view')->name('profit');
    });

    // Profile / Change Password
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])
        ->name('profile.password.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');

    // ==================================================
    // Users — permission based
    // ==================================================
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])
            ->middleware('permission:users.view')->name('index');
        Route::get('/create', [UserController::class, 'create'])
            ->middleware('permission:users.manage')->name('create');
        Route::post('/', [UserController::class, 'store'])
            ->middleware('permission:users.manage')->name('store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])
            ->middleware('permission:users.manage')->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])
            ->middleware('permission:users.manage')->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])
            ->middleware('permission:users.manage')->name('destroy');
    });

    // ==================================================
    // Roles — permission based
    // ==================================================
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])
            ->middleware('permission:roles.view')->name('index');
        Route::get('/create', [RoleController::class, 'create'])
            ->middleware('permission:roles.manage')->name('create');
        Route::post('/', [RoleController::class, 'store'])
            ->middleware('permission:roles.manage')->name('store');
        Route::get('/{id}/edit', [RoleController::class, 'edit'])
            ->middleware('permission:roles.manage')->name('edit');
        Route::put('/{id}', [RoleController::class, 'update'])
            ->middleware('permission:roles.manage')->name('update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])
            ->middleware('permission:roles.manage')->name('destroy');
    });

    // ==================================================
    // Settings — permission based
    // ==================================================
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])
            ->middleware('permission:settings.view')->name('index');
        Route::put('/', [SettingController::class, 'update'])
            ->middleware('permission:settings.manage')->name('update');
    });

    // Barcode lookup (API)
    Route::get('/products/check-barcode/{code}', [ProductController::class, 'checkBarcode'])
        ->middleware('permission:products.manage')
        ->name('products.check-barcode');

    // Backups
    Route::prefix('backups')->name('backups.')->group(function () {
        Route::get('/', [BackupController::class, 'index'])
            ->middleware('permission:backup.view')->name('index');
        Route::post('/create', [BackupController::class, 'create'])
            ->middleware('permission:backup.manage')->name('create');
        Route::get('/download/{filename}', [BackupController::class, 'download'])
            ->middleware('permission:backup.view')->name('download');
        Route::delete('/{filename}', [BackupController::class, 'destroy'])
            ->middleware('permission:backup.manage')->name('destroy');
        Route::post('/restore', [BackupController::class, 'restore'])
            ->middleware('permission:backup.manage')->name('restore');
    });

    Route::post('/cleanup', [BackupController::class, 'cleanup'])
        ->middleware('permission:backup.manage')->name('cleanup');

    Route::get('/udhaar', [ReportController::class, 'udhaar'])
        ->middleware('permission:reports.view')->name('udhaar');
    Route::get('/udhaar/{id}', [ReportController::class, 'udhaarDetail'])
        ->middleware('permission:reports.view')->name('udhaar.detail');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

}); // end auth group
