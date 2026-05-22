<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect()->route('ppmp.select');
})->name('home');

// PPMP ordering flow (public — no auth required)
Route::prefix('ppmp')->name('ppmp.')->group(function () {
    Volt::route('/', 'ppmp.select-office')->name('select');
    Volt::route('/{office}', 'ppmp.orders')->name('orders');
});

// Printable reports (public)
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/ppmp/{office}', [ReportController::class, 'myPpmp'])->name('my-ppmp');
    Route::get('/group/{group}', [ReportController::class, 'groupPpmp'])->name('group-ppmp');
    Route::get('/summary/{group}', [ReportController::class, 'orderSummary'])->name('order-summary');
});

// Admin (requires auth)
Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Volt::route('admin/supplies', 'admin.supplies')->name('admin.supplies');
});

require __DIR__.'/auth.php';
