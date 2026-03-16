<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Apps\NotesController;
use App\Http\Controllers\Apps\RemindersController;
use App\Http\Controllers\Apps\TodosController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;

// Redirect root
Route::get('/', fn() => redirect()->route('dashboard'));

// Auth
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

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // App subscription management
    Route::post('/apps/{slug}/subscribe', [AppController::class, 'subscribe'])->name('apps.subscribe');
    Route::delete('/apps/{slug}/unsubscribe', [AppController::class, 'unsubscribe'])->name('apps.unsubscribe');
    Route::get('/apps/{slug}/open', [AppController::class, 'open'])->name('apps.open');

    // Notes app
    Route::resource('notes', NotesController::class);
    Route::patch('/notes/{note}/toggle-pin', [NotesController::class, 'togglePin'])->name('notes.toggle-pin');

    // Reminders app
    Route::resource('reminders', RemindersController::class)->except(['show']);
    Route::patch('/reminders/{reminder}/toggle', [RemindersController::class, 'toggle'])->name('reminders.toggle');

    // Todos app
    Route::resource('todos', TodosController::class);

    // POS
    Route::get('/pos', \App\Livewire\Pos\IndexBeta::class)->name('pos');
});
