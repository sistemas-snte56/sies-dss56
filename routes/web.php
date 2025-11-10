<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;

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

    // Aquí irán más rutas del sistema (Regiones, Delegaciones, etc.)
});
