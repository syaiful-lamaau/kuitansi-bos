<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReceiptController;

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [ReceiptController::class, 'index'])->name('home');
    Route::post('/settings', [ReceiptController::class, 'storeSetting']);
    Route::get('/receipts/list', [ReceiptController::class, 'list'])->name('receipts.list');
    Route::post('/receipts', [ReceiptController::class, 'storeReceipt']);
    Route::put('/receipts/{id}', [ReceiptController::class, 'update']);
    Route::get('/receipts/print-all', [ReceiptController::class, 'printAll'])->name('receipts.print_all');
    Route::get('/receipts/{id}/print', [ReceiptController::class, 'print']);
    Route::delete('/receipts/delete-all', [ReceiptController::class, 'destroyAll']);
    Route::delete('/receipts/{id}', [ReceiptController::class, 'destroy']);
});
