<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PosController::class, 'index'])->name('pos.index');
Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
Route::get('/pos/receipt/{order}', [PosController::class, 'receipt'])->name('pos.receipt');

// Auth Routes (Login, Register, Logout)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// PROTECTED ADMIN ROUTES (Requires Login)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
     Route::get('/reports/prompt-delete', [AdminController::class, 'promptDeleteSales'])->name('reports.prompt-delete');
    Route::post('/reports/delete-all', [AdminController::class, 'deleteAllSales'])->name('reports.delete-all');
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::patch('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::post('/products/{product}/toggle', [AdminController::class, 'toggleProduct'])->name('products.toggle');
});

// Admin Reports
Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');

// Admin Product Management
Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
Route::post('/admin/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
Route::patch('/admin/products/{product}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
Route::post('/admin/products/{product}/toggle', [AdminController::class, 'toggleProduct'])->name('admin.products.toggle');
