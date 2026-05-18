<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AgenteController;

Route::get('/', [LandingController::class, 'index'])->name('home');
 
Route::middleware(['auth', 'verified'])->group(function () {
    /* Route::inertia('dashboard', 'Dashboard')->name('dashboard'); */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


Route::middleware(['auth', 'role:cliente'])->prefix('cliente')->name('cliente.')->group(function () {
    Route::get('/dashboard', [ClienteController::class, 'dashboard'])->name('dashboard');
    Route::get('/favoritos', [ClienteController::class, 'favoritos'])->name('favoritos');
    Route::get('/consultas', [ClienteController::class, 'consultas'])->name('consultas');
    Route::delete('/favoritos/{propiedad}', [ClienteController::class, 'quitarFavorito'])->name('favoritos.quitar');
});

Route::middleware(['auth', 'role:agente'])->prefix('agente')->name('agente.')->group(function () {
    Route::get('/dashboard', [AgenteController::class, 'dashboard'])->name('dashboard');
    Route::get('/propiedades', [AgenteController::class, 'propiedades'])->name('propiedades');
    Route::get('/consultas', [AgenteController::class, 'consultasRecibidas'])->name('consultas');
    Route::post('/consultas/{consulta}/responder', [AgenteController::class, 'responderConsulta'])->name('consultas.responder');
});

Route::post('/contact', [LandingController::class, 'sendContact'])->name('contact.send');



require __DIR__.'/settings.php';
