<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TurnosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\DisponibilidadController;
use App\Http\Controllers\AppointmentController;
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

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/turnos', [TurnosController::class, 'index'])->name('turnos.indexTurnos');
    Route::get('/empresa', [EmpresaController::class, 'index'])->name('empresa.indexEmpresa');
    Route::post('/add-collaborator', [EmpresaController::class, 'addCollaborator'])->name('addCollaborator');
    Route::get('/enterprise/{id}/collaborators', [EmpresaController::class, 'getCollaborators'])->name('get.collaborators');
    Route::post('/delete-collaborator', [EmpresaController::class, 'deleteCollaborator'])->name('deleteCollaborator');
    Route::get('/disponibilidad', [DisponibilidadController::class, 'index'])->name('disponibilidad.indexDisponibilidad');
    Route::get('/mi-disponibilidad/crear', [DisponibilidadController::class, 'create'])->name('disponibilidad.create');
    Route::post('/mi-disponibilidad', [DisponibilidadController::class, 'store'])->name('disponibilidad.store');
    Route::delete('/disponibilidad/{id}', [DisponibilidadController::class, 'destroy'])->name('disponibilidad.destroy');
    Route::get('/mis-turnos', [AppointmentController::class, 'index'])->name('turnos.index');
    Route::post('/servicio/guardar', [ServiceController::class, 'guardar'])->name('servicios.guardar');

Route::put('/servicios/actualizar', [ServiceController::class, 'actualizar'])->name('servicios.actualizar');

Route::get('/servicios', [ServiceController::class, 'listarEmpresasConServicios'])->name('servicios.index');

Route::post('/empresa/guardar', [EmpresaController::class, 'guardar'])->name('empresa.guardar');
Route::get('/colaboradores/{empresa_id}', [EmpresaController::class, 'getUsuariosPorEmpresa']);
Route::get('/usuarios/no-asignados', [EmpresaController::class, 'usuariosNoAsignados'])->name('usuarios.noAsignados');
Route::delete('/empresa/{empresa}/colaborador/{usuario}', [EmpresaController::class, 'eliminar'])->name('colaborador.eliminar');


// Ruta para solicitar el turno
Route::get('/turnos/seleccion-fecha-hora/{servicio_id}/{usuario_colaborador_id}', [TurnosController::class, 'seleccionFechaHora'])->name('turnos.seleccionFechaHora');

Route::get('/turnos/seleccion/{servicio_id}/{usuario_colaborador_id}/{fecha}', [TurnosController::class, 'mostrarDisponibilidad'])->name('turnos.mostrarDisponibilidad');


Route::post('/turnos/confirmar/{servicio_id}/{usuario_colaborador_id}', [AppointmentController::class, 'store'])->name('turnos.confirmar');

Route::get('/disponibilidad/horarios/{dia}', [DisponibilidadController::class, 'horarios']);

Route::delete('/turnos/cancel/{id}', [AppointmentController::class, 'cancel'])->name('turnos.cancel');

Route::get('/solicitudes-turnos', [AppointmentController::class, 'getSolicitudes'])->name('solicitudes.turnos');
Route::patch('/solicitudes-turnos/{id}', [AppointmentController::class, 'updateStatus'])->name('solicitudes.updateStatus');

Route::get('/user/edit', [ProfileController::class, 'edit'])->name('user.edit');
Route::post('/user/update', [ProfileController::class, 'update'])->name('user.update');

});

require __DIR__.'/auth.php';
