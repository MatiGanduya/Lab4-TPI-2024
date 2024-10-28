<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TurnosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\DisponibilidadController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::post('/servicio/guardar', [ServiceController::class, 'guardar'])->name('servicios.guardar');

Route::put('/servicios/actualizar', [ServiceController::class, 'actualizar'])->name('servicios.actualizar');

Route::get('/servicios', [ServiceController::class, 'listarEmpresasConServicios'])->name('servicios.index');

Route::post('/empresa/guardar', [EmpresaController::class, 'guardar'])->name('empresa.guardar');

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/turnos', [TurnosController::class, 'index'])->name('turnos.indexTurnos');
    Route::get('/empresa', [EmpresaController::class, 'index'])->name('empresa.indexEmpresa');
    Route::get('/disponibilidad', [DisponibilidadController::class, 'index'])->name('disponibilidad.indexDisponibilidad');
    Route::get('/mi-disponibilidad/crear', [DisponibilidadController::class, 'create'])->name('disponibilidad.create');
    Route::post('/mi-disponibilidad', [DisponibilidadController::class, 'store'])->name('disponibilidad.store');
    Route::delete('/disponibilidad/{id}', [DisponibilidadController::class, 'destroy'])->name('disponibilidad.destroy');

});

require __DIR__.'/auth.php';
