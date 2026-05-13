<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Inmobiliaria\InmobiliariaController;

Route::get('/', [LandingController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::middleware(['auth', 'verified'])->prefix('inmobiliaria')->group(function () {
    Route::get('dashboard', [InmobiliariaController::class, 'dashboard'])->name('inmobiliaria.dashboard');
});

require __DIR__.'/settings.php';
