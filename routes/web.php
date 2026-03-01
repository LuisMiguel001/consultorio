<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;

Route::get('/', function () {
    return redirect()->route('pacientes.create');
});

Route::get('/pacientes', [PacienteController::class, 'create'])->name('pacientes.create');
Route::post('/pacientes', [PacienteController::class, 'store'])->name('pacientes.store');
Route::get('/pacientes/lista', [PacienteController::class, 'lista'])->name('pacientes.lista');
Route::get('/consultorio/inicio', [PacienteController::class, 'inicio'])->name('pacientes.inicio');

// Nuevas rutas para editar y eliminar
Route::get('/pacientes/{id}/edit', [PacienteController::class, 'edit'])->name('pacientes.edit');
Route::put('/pacientes/{id}', [PacienteController::class, 'update'])->name('pacientes.update');
Route::delete('/pacientes/{id}', [PacienteController::class, 'destroy'])->name('pacientes.destroy');
