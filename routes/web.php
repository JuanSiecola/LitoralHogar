<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Inmobiliaria\InmobiliariaController;
use App\Http\Controllers\Propiedad\PropiedadController;
use App\Http\Controllers\AgenteController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\LocalidadController;
use App\Http\Controllers\GeocodeController;
use App\Http\Controllers\PropiedadPublicaController;
use App\Http\Controllers\ComparadorController;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\ConsultaController;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
Route::get('/propiedades', [ClienteController::class, 'redirigirPropiedades'])
    ->middleware('auth')
    ->name('propiedades');

Route::middleware(['auth', 'verified'])->group(function () {
    /* Route::inertia('dashboard', 'Dashboard')->name('dashboard'); */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/consultas', [ConsultaController::class, 'store'])->name('consultas.store');
    Route::post('/consultas/{consulta}/mensajes', [ConsultaController::class, 'enviarMensaje'])->name('consultas.mensajes.store');
    Route::post('/consultas/{consulta}/leido', [ConsultaController::class, 'marcarLeido'])->name('consultas.leido');
});

Route::middleware(['auth', 'verified', 'role:inmobiliaria'])->prefix('inmobiliaria')->name('inmobiliaria.')->group(function () {
    Route::get('/dashboard', [InmobiliariaController::class, 'dashboard'])->name('dashboard');
    Route::get('/propiedades', [PropiedadController::class, 'index'])->name('propiedades');
    Route::get('/propiedades/create', [PropiedadController::class, 'create'])->name('propiedades.create');
    Route::post('/propiedades', [PropiedadController::class, 'store'])->name('propiedades.store');
    Route::get('/propiedades/{propiedad}/edit', [PropiedadController::class, 'edit'])->name('propiedades.edit');
    Route::patch('/propiedades/{propiedad}', [PropiedadController::class, 'update'])->name('propiedades.update');
    Route::delete('/propiedades/{propiedad}', [PropiedadController::class, 'destroy'])->name('propiedades.destroy');
    Route::get('/perfil', [InmobiliariaController::class, 'perfil'])->name('perfil');
    Route::get('/consultas', [InmobiliariaController::class, 'consultasRecibidas'])->name('consultas');
    Route::get('/estadisticas', [EstadisticasController::class, 'inmobiliaria'])->name('estadisticas');
    });

Route::middleware(['auth', 'role:cliente'])->prefix('cliente')->name('cliente.')->group(function () {
    Route::get('/dashboard', [ClienteController::class, 'dashboard'])->name('dashboard');
    Route::get('/favoritos', [ClienteController::class, 'favoritos'])->name('favoritos');
    Route::get('/consultas', [ClienteController::class, 'consultas'])->name('consultas');
    Route::get('/propiedades', [ClienteController::class, 'propiedades'])->name('propiedades');
    Route::post('/favoritos/{propiedad}', [ClienteController::class, 'agregarFavorito'])->name('favoritos.agregar');
    Route::delete('/favoritos/{propiedad}', [ClienteController::class, 'quitarFavorito'])->name('favoritos.quitar');
    Route::get('/perfil', [ClienteController::class, 'perfil'])->name('perfil');
});

Route::middleware(['auth', 'role:agente'])->prefix('agente')->name('agente.')->group(function () {
    Route::get('/dashboard', [AgenteController::class, 'dashboard'])->name('dashboard');
    
    // Propiedades CRUD
    Route::get('/propiedades', [AgenteController::class, 'propiedades'])->name('propiedades');
    Route::get('/propiedades/crear', [AgenteController::class, 'create'])->name('propiedades.create');
    Route::post('/propiedades', [AgenteController::class, 'store'])->name('propiedades.store');
    Route::get('/propiedades/{propiedad}/editar', [AgenteController::class, 'edit'])->name('propiedades.edit');
    Route::patch('/propiedades/{propiedad}', [AgenteController::class, 'update'])->name('propiedades.update');
    Route::delete('/propiedades/{propiedad}', [AgenteController::class, 'destroy'])->name('propiedades.destroy');
    
    // Consultas
    Route::get('/consultas', [AgenteController::class, 'consultasRecibidas'])->name('consultas');
    Route::get('/perfil', [AgenteController::class, 'perfil'])->name('perfil');

    // Estadísticas
    Route::get('/estadisticas', [EstadisticasController::class, 'agente'])->name('estadisticas');
});

Route::post('/contact', [LandingController::class, 'sendContact'])->name('contact.send');

Route::get('/propiedades/comparar', [ComparadorController::class, 'comparar'])->name('propiedades.comparar');

Route::get('/departamentos/{departamento}/localidades', [LocalidadController::class, 'porDepartamento'])->name('departamentos.localidades.index');
Route::get('/propiedades/{propiedad}', [PropiedadPublicaController::class, 'show'])->name('propiedades.show');
Route::get('/geocode', [GeocodeController::class, 'buscar'])->name('geocode');
require __DIR__ . '/settings.php';