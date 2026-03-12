<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Livewire\Auth\Login;

// Redirect root
Route::get('/', fn() => redirect()->route('dashboard'));

// Auth
use App\Livewire\Auth\Register;

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->middleware('auth')->name('logout');

// Protected pages
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/page-1', [PageController::class, 'page1'])->name('page1');
    Route::get('/page-2', [PageController::class, 'page2'])->name('page2');
});
