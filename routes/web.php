<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PosController;

Route::get('/', [PosController::class, 'index'])->name('pos.index');
Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
Route::get('/pos/receipt/{order}', [PosController::class, 'receipt'])->name('pos.receipt');

