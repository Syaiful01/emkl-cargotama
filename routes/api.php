<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\InvoiceController;

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('permission:dashboard.view')->name('dashboard');

    // Customer Routes
    Route::apiResource('customers', CustomerController::class);

    // Shipment Routes
    Route::get('/shipments', [ShipmentController::class, 'index'])->middleware('permission:shipment.view')->name('shipments.index');
    Route::post('/shipments', [ShipmentController::class, 'store'])->middleware('permission:shipment.create')->name('shipments.store');
    Route::get('/shipments/{id}', [ShipmentController::class, 'show'])->middleware('permission:shipment.view')->name('shipments.show');
    Route::patch('/shipments/{id}/status', [ShipmentController::class, 'updateStatus'])->middleware('permission:shipment.edit')->name('shipments.update-status');

    // Document Routes
    Route::post('/documents', [DocumentController::class, 'store'])->middleware('permission:document.upload')->name('documents.store');
    Route::get('/documents/{id}', [DocumentController::class, 'show'])->middleware('permission:document.view')->name('documents.show');
    Route::get('/documents/{id}/preview', [DocumentController::class, 'preview'])->middleware('permission:document.view')->name('documents.preview');
    Route::post('/documents/{id}/parse', [DocumentController::class, 'parse'])->middleware('permission:document.upload')->name('documents.parse');
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->middleware('permission:document.destroy');

    // Invoice Routes
    Route::apiResource('invoices', InvoiceController::class);
    Route::post('/invoices/{id}/finalize', [InvoiceController::class, 'finalize'])->middleware('permission:invoice.create')->name('invoices.finalize');
    Route::get('/invoices/{id}/pdf', [InvoiceController::class, 'downloadPdf'])->middleware('permission:invoice.pdf')->name('invoices.pdf');
});
