<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;

// Redirect root to dashboard
Route::get('/', fn() => redirect()->route('dashboard'));

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Pages
Route::get('/page-1', [PageController::class, 'page1'])->name('page1');
Route::get('/page-2', [PageController::class, 'page2'])->name('page2');

// Auth routes (provided by Laravel Breeze / Fortify, or manual)
// Uncomment after installing an auth scaffolding:
// require __DIR__.'/auth.php';
