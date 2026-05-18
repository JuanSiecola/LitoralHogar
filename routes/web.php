<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Inmobiliaria\InmobiliariaController;
use App\Http\Controllers\Propiedad\PropiedadController;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/propiedades', [ClienteController::class, 'redirigirPropiedades'])
    ->middleware('auth')
    ->name('propiedades');

 
Route::middleware(['auth', 'verified'])->group(function () {
    /* Route::inertia('dashboard', 'Dashboard')->name('dashboard'); */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


Route::middleware(['auth', 'role:cliente'])->prefix('cliente')->name('cliente.')->group(function () {
    Route::get('/dashboard', [ClienteController::class, 'dashboard'])->name('dashboard');
    Route::get('/favoritos', [ClienteController::class, 'favoritos'])->name('favoritos');
    Route::get('/consultas', [ClienteController::class, 'consultas'])->name('consultas');
    Route::get('/propiedades', [ClienteController::class, 'propiedades'])->name('propiedades'); 
    Route::delete('/favoritos/{propiedad}', [ClienteController::class, 'quitarFavorito'])->name('favoritos.quitar');
});

Route::middleware(['auth', 'verified', 'role:inmobiliaria'])->prefix('inmobiliaria')->name('inmobiliaria.')->group(function () {
    Route::get('/dashboard', [InmobiliariaController::class, 'dashboard'])->name('dashboard');
    Route::resource('propiedades', PropiedadController::class);
});

Route::post('/contact', [LandingController::class, 'sendContact'])->name('contact.send');

require __DIR__.'/settings.php';
