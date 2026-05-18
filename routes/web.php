<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;

Route::get('/', [LandingController::class, 'index'])->name('home');
 
Route::middleware(['auth', 'verified'])->group(function () {
    /* Route::inertia('dashboard', 'Dashboard')->name('dashboard'); */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


Route::post('/contact', [LandingController::class, 'sendContact'])->name('contact.send');

require __DIR__.'/settings.php';
