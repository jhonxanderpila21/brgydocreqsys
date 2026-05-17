<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\ResidentProfileController;
use App\Http\Controllers\ResidentNotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard.index')
        : redirect()->route('login');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Resident routes
    Route::get('resident/profile', [ResidentProfileController::class, 'show'])->name('resident.profile');
    Route::post('resident/profile', [ResidentProfileController::class, 'update'])->name('resident.profile.update');
    Route::get('resident/notifications', [ResidentNotificationController::class, 'index'])->name('resident.notifications');
    Route::post('document-requests', [DocumentRequestController::class, 'store'])->name('document-requests.store');
    Route::get('document-requests/{document_request}', [DocumentRequestController::class, 'show'])->name('document-requests.show');

    Route::middleware('role:admin|staff|resident')->group(function () {
        Route::resource('document-requests', DocumentRequestController::class);

        Route::resource('residents', ResidentController::class)->except(['show']);
        Route::resource('households', HouseholdController::class)->except(['show']);
        Route::resource('document-requests', DocumentRequestController::class)->except(['show', 'store']);
        Route::post('document-requests/{document_request}/pay', [DocumentRequestController::class, 'pay'])->name('document-requests.pay');
        Route::get('document-requests/{document_request}/receipt', [DocumentRequestController::class, 'receipt'])->name('document-requests.receipt');

        Route::get('reports', [ReportsController::class, 'dashboard'])->name('reports.dashboard');
        Route::get('reports/export-requests', [ReportsController::class, 'exportRequests'])->name('reports.export-requests');
        Route::get('reports/export-residents', [ReportsController::class, 'exportResidents'])->name('reports.export-residents');
    });

    Route::middleware('role:admin')->group(function () {
        Route::resource('document-types', DocumentTypeController::class)->except(['show']);
    });
});
