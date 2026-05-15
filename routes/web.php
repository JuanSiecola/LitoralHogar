<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Inmobiliaria\InmobiliariaController;
use \App\Http\Controllers\Propiedad\PropiedadController;

Route::get('/', [LandingController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::middleware(['auth', 'verified'])->prefix('inmobiliaria')->name('inmobiliaria.')->group(function () {
    Route::get('dashboard', [InmobiliariaController::class, 'dashboard'])->name('dashboard');
    Route::resource('propiedades', PropiedadController::class);
});

require __DIR__.'/settings.php';
