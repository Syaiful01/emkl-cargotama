<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('customers', CustomerController::class);
    Route::resource('shipments', ShipmentController::class);
    Route::patch('shipments/{id}/status', [ShipmentController::class, 'updateStatus'])->name('shipments.update-status');
    Route::resource('invoices', InvoiceController::class);
    Route::get('invoices/{id}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    Route::resource('documents', DocumentController::class);

    // Placeholder for other modules
    Route::get('/receivables', function() { return view('receivables.index'); })->name('receivables.index');
    Route::get('/notifications', function() { return view('notifications.index'); })->name('notifications.index');
    Route::get('/reports', function() { return view('reports.index'); })->name('reports.index');
    Route::get('/settings', function() { return view('settings.index'); })->name('settings.index');
    Route::get('/users', function() { return view('users.index'); })->name('users.index');
});
