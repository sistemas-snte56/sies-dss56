<?php

use App\Livewire\Cargos;
use App\Livewire\Regiones;
use App\Livewire\Dashboard;
use App\Livewire\Delegaciones;
use App\Livewire\CentrosTrabajo;
use App\Livewire\NivelesAcademicos;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí definimos las rutas principales del SIES-DSS56.
| El acceso al dashboard y demás módulos requerirá autenticación.
|
*/

// Página pública inicial (opcional)
Route::get('/', function () {
    return view('welcome');
});

// Grupo de rutas protegidas (solo usuarios verificados)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard principal Livewire
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/regiones', Regiones::class)->name('regiones');
    Route::get('/delegaciones', Delegaciones::class)->name('delegaciones');
    Route::get('/centros-de-trabajo', CentrosTrabajo::class)->name('centros-de-trabajo');
    Route::get('/cargos', Cargos::class)->name('cargos');
    Route::get('/niveles-academicos', NivelesAcademicos::class)->name('niveles-academicos');

});
