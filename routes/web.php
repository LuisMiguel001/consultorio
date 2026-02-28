<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\PacienteController;

Route::post('/pacientes', [PacienteController::class, 'store'])->name('pacientes.store');

Route::view('/', 'pacientes');
