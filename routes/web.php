<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\VariantController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ProfileController;


use App\Http\Controllers\IndonesiaController;

Route::prefix('api')->group(function () {
    Route::get('/cities/{province}', [IndonesiaController::class, 'cities']);
    Route::get('/districts/{city}', [IndonesiaController::class, 'districts']);
    Route::get('/villages/{district}', [IndonesiaController::class, 'villages']);
});

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'prosesLogin'])->name('proseslogin');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:Admin'])->group(function () {

    Route::get('/admindashboard', [DashboardController::class, 'admindashboard'])->name('dashboard.admin');
    Route::get('/dashboard/top-products/{branchId}', [DashboardController::class, 'getTopProductsByBranch']);
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('variant', VariantController::class);
    Route::get('/variant/print/{id}', [VariantController::class, 'printBarcode'])->name('variant.print');
    Route::get('/report/products/{branchId}', [DashboardController::class, 'getByBranch']);

    Route::resource('branch', BranchController::class);
});

Route::middleware(['auth', 'role:Cashier'])->group(function () {
    Route::get('/cashierdashboard', [DashboardController::class, 'cashierdashboard'])->name('dashboard.cashier');
    Route::resource('sales', SalesController::class);
    Route::get('/sales/sales/{id}', [SalesController::class, 'generatePDFBySalesId'])->name('sales.generatid');
});

Route::middleware(['auth', 'role:Cabang'])->group(function () {
    Route::get('/branchdashboard', [DashboardController::class, 'branchdashboard'])->name('dashboard.branch');
});

Route::resource('stocks', StockController::class);
Route::resource('users', UserController::class);
Route::resource('customers', CustomerController::class);

Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
Route::get('/requests/create', [RequestController::class, 'create'])->name('requests.create');
Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');
Route::post('/requests/{id}/update-status', [RequestController::class, 'updateStatus'])->name('requests.updateStatus');
Route::post('/requests/{id}/kirim', [RequestController::class, 'kirim'])->name('requests.kirim');
Route::post('/requests/{id}/diterima', [RequestController::class, 'diterima'])->name('requests.diterima');
Route::get('/report', [ReportController::class, 'index'])->name('report.index');
Route::post('/report/generate', [ReportController::class, 'generatePDF'])->name('report.generate');
Route::get('/report/filter', [ReportController::class, 'filter'])->name('report.filter');
Route::post('/sales/find-by-barcode', [SalesController::class, 'findProductByBarcode'])->name('sales.find-by-barcode');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');