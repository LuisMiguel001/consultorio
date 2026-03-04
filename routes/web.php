<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\AntecedenteController;
use App\Http\Controllers\EstudioController;
use App\Http\Controllers\DiagnosticoController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\ProcedimientoController;
use App\Http\Controllers\SignoVitalController;
use App\Http\Controllers\ExamenFisicoController;
use App\Http\Controllers\EvolucionController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\UserController;

//Login
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth','role:admin|secretaria'])->group(function () {

    Route::get('/usuarios', [UserController::class,'index'])->name('usuarios.index');

    Route::get('/usuarios/create', [UserController::class,'create'])->name('usuarios.create');
    Route::post('/usuarios', [UserController::class,'store'])->name('usuarios.store');

    Route::get('/usuarios/{user}/edit', [UserController::class,'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{user}', [UserController::class,'update'])->name('usuarios.update');

});

//Módulos con autenticación
Route::middleware('auth')->group(function () {

    Route::get('/consultorio/inicio', [PacienteController::class, 'inicio'])->name('pacientes.inicio');

    // Pacientes
    Route::get('/pacientes', [PacienteController::class, 'create'])
        ->middleware('permission:crear pacientes')
        ->name('pacientes.create');
    Route::post('/pacientes', [PacienteController::class, 'store'])->name('pacientes.store');
    Route::get('/pacientes/lista', [PacienteController::class, 'lista'])->name('pacientes.lista');
    Route::get('/pacientes/{id}/edit', [PacienteController::class, 'edit'])->name('pacientes.edit');
    Route::put('/pacientes/{id}', [PacienteController::class, 'update'])->name('pacientes.update');
    Route::delete('/pacientes/{id}', [PacienteController::class, 'destroy'])->name('pacientes.destroy');

    // Citas
    Route::get('/citas', [CitaController::class, 'index'])->name('citas.index');
    Route::get('/citas/create', [CitaController::class, 'create'])->name('citas.create');
    Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');

    //Consulta
    Route::get('/pacientes/{id}/consulta', [ConsultaController::class, 'create'])->name('consultas.create');
    Route::post('/consultas', [ConsultaController::class, 'store'])->name('consultas.store');
    Route::get('/pacientes/{paciente}', [PacienteController::class, 'show'])->name('pacientes.show');
    Route::get('/consultas/{consulta}', [ConsultaController::class, 'show'])->name('consultas.show');

    //Antecedentes
    Route::middleware('auth')->group(function () {
        Route::get('/pacientes/{id}/antecedentes', [AntecedenteController::class, 'index']);
        Route::post('/pacientes/{id}/antecedentes', [AntecedenteController::class, 'store'])->name('antecedentes.store');
    });

    // Estudios
    Route::get('/consultas/{consulta}/estudios', [EstudioController::class, 'index'])->name('estudios.index');
    Route::post('/consultas/{consulta}/estudios', [EstudioController::class, 'store'])->name('estudios.store');
    Route::get('/estudios/{estudio}/descargar', [EstudioController::class, 'descargarArchivo'])->name('estudios.descargar');

    //Diagnósticos
    Route::post('/consultas/{consulta}/diagnosticos', [DiagnosticoController::class, 'store'])->name('diagnosticos.store');

    //Tratamientos
    Route::post('/consultas/{consulta}/tratamientos', [TratamientoController::class, 'store'])->name('tratamientos.store');

    //Procedimientos
    Route::post('/consultas/{consulta}/procedimientos', [ProcedimientoController::class, 'store'])->name('procedimientos.store');

    // Signos Vitales
    Route::post('/consultas/{consulta}/signos-vitales', [SignoVitalController::class, 'store'])->name('signos-vitales.store');

    //Examen Físico
    Route::post('/consultas/{consulta}/examen-fisico', [ExamenFisicoController::class, 'store'])->name('examen-fisico.store');

    // Evolución
    Route::post('/consultas/{consulta}/evoluciones', [EvolucionController::class, 'store'])->name('evoluciones.store');

    //Receta
    Route::get('/consultas/{consulta}/receta/pdf', [RecetaController::class, 'generar'])->name('receta.pdf');
});
