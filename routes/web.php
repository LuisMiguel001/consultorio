<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\AntecedenteController;
use App\Http\Controllers\EstudioController;
//Login
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Módulos con autenticación
Route::middleware('auth')->group(function () {

    Route::get('/consultorio/inicio', [PacienteController::class, 'inicio'])->name('pacientes.inicio');

    // Pacientes
    Route::get('/pacientes', [PacienteController::class, 'create'])->name('pacientes.create');
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
    Route::get('/pacientes/{id}', [PacienteController::class, 'show'])->name('pacientes.show');
    Route::get('/consultas/{consulta}',[ConsultaController::class, 'show'] )->name('consultas.show');

    //Antecedentes
    Route::middleware('auth')->group(function () {
        Route::get('/pacientes/{id}/antecedentes', [AntecedenteController::class, 'index']);
        Route::post('/pacientes/{id}/antecedentes', [AntecedenteController::class, 'store'])->name('antecedentes.store');
    });

    // Estudios
    Route::get('/consultas/{consulta}/estudios', [EstudioController::class, 'index'] )->name('estudios.index');
    Route::post('/consultas/{consulta}/estudios', [EstudioController::class, 'store'] )->name('estudios.store');
    Route::get('/estudios/{estudio}/descargar', [EstudioController::class, 'descargarArchivo'])->name('estudios.descargar');
});
