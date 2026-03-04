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

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'nocache'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | USUARIOS
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:ver usuarios')->group(function () {
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    });

    Route::middleware('permission:crear usuarios')->group(function () {
        Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    });

    Route::middleware('permission:editar usuarios')->group(function () {
        Route::get('/usuarios/{user}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('usuarios.update');
    });

    Route::middleware('permission:eliminar usuarios')->group(function () {
        Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('usuarios.destroy');
    });


    /*
    |--------------------------------------------------------------------------
    | INICIO
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:ver pacientes')->group(function () {
        Route::get('/consultorio/inicio', [PacienteController::class, 'inicio'])->name('pacientes.inicio');
    });


    /*
    |--------------------------------------------------------------------------
    | PACIENTES
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:crear pacientes')->group(function () {
        Route::get('/pacientes', [PacienteController::class, 'create'])->name('pacientes.create');
        Route::post('/pacientes', [PacienteController::class, 'store'])->name('pacientes.store');
    });

    Route::middleware('permission:ver pacientes')->group(function () {
        Route::get('/pacientes/lista', [PacienteController::class, 'lista'])->name('pacientes.lista');
        Route::get('/pacientes/{paciente}', [PacienteController::class, 'show'])->name('pacientes.show');
    });

    Route::middleware('permission:editar pacientes')->group(function () {
        Route::get('/pacientes/{id}/edit', [PacienteController::class, 'edit'])->name('pacientes.edit');
        Route::put('/pacientes/{id}', [PacienteController::class, 'update'])->name('pacientes.update');
    });

    Route::middleware('permission:eliminar pacientes')->group(function () {
        Route::delete('/pacientes/{id}', [PacienteController::class, 'destroy'])->name('pacientes.destroy');
    });


    /*
    |--------------------------------------------------------------------------
    | CITAS
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:ver citas')->group(function () {
        Route::get('/citas', [CitaController::class, 'index'])->name('citas.index');
    });

    Route::middleware('permission:crear citas')->group(function () {
        Route::get('/citas/create', [CitaController::class, 'create'])->name('citas.create');
        Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
    });

    Route::middleware('permission:editar citas')->group(function () {
        Route::put('/citas/{cita}', [CitaController::class, 'update'])->name('citas.update');
    });

    Route::middleware('permission:eliminar citas')->group(function () {
        Route::delete('/citas/{cita}', [CitaController::class, 'destroy'])->name('citas.destroy');
    });


    /*
    |--------------------------------------------------------------------------
    | CONSULTAS
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:crear consultas')->group(function () {
        Route::get('/pacientes/{id}/consulta', [ConsultaController::class, 'create'])->name('consultas.create');
        Route::post('/consultas', [ConsultaController::class, 'store'])->name('consultas.store');
    });

    Route::middleware('permission:ver consultas')->group(function () {
        Route::get('/consultas/{consulta}', [ConsultaController::class, 'show'])->name('consultas.show');
    });


    /*
    |--------------------------------------------------------------------------
    | ANTECEDENTES
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:crear antecedentes')->group(function () {
        Route::post('/pacientes/{id}/antecedentes', [AntecedenteController::class, 'store'])->name('antecedentes.store');
    });

    Route::middleware('permission:ver antecedentes')->group(function () {
        Route::get('/pacientes/{id}/antecedentes', [AntecedenteController::class, 'index']);
    });


    /*
|--------------------------------------------------------------------------
| ESTUDIOS
|--------------------------------------------------------------------------
*/

    Route::middleware('permission:ver estudios')->group(function () {
        Route::get(
            '/consultas/{consulta}/estudios',
            [EstudioController::class, 'index']
        )->name('estudios.index');
    });

    Route::middleware('permission:crear estudios')->group(function () {
        Route::post(
            '/consultas/{consulta}/estudios',
            [EstudioController::class, 'store']
        )->name('estudios.store');
    });

    Route::middleware('permission:descargar estudios')->group(function () {
        Route::get(
            '/estudios/{estudio}/descargar',
            [EstudioController::class, 'descargarArchivo']
        )->name('estudios.descargar');
    });


    /*
|--------------------------------------------------------------------------
| DIAGNOSTICOS
|--------------------------------------------------------------------------
*/

    Route::middleware('permission:crear diagnosticos')->group(function () {
        Route::post(
            '/consultas/{consulta}/diagnosticos',
            [DiagnosticoController::class, 'store']
        )->name('diagnosticos.store');
    });


    /*
|--------------------------------------------------------------------------
| TRATAMIENTOS
|--------------------------------------------------------------------------
*/

    Route::middleware('permission:crear tratamientos')->group(function () {
        Route::post(
            '/consultas/{consulta}/tratamientos',
            [TratamientoController::class, 'store']
        )->name('tratamientos.store');
    });


    /*
|--------------------------------------------------------------------------
| PROCEDIMIENTOS
|--------------------------------------------------------------------------
*/

    Route::middleware('permission:crear procedimientos')->group(function () {
        Route::post(
            '/consultas/{consulta}/procedimientos',
            [ProcedimientoController::class, 'store']
        )->name('procedimientos.store');
    });


    /*
|--------------------------------------------------------------------------
| SIGNOS VITALES
|--------------------------------------------------------------------------
*/

    Route::middleware('permission:crear signos vitales')->group(function () {
        Route::post(
            '/consultas/{consulta}/signos-vitales',
            [SignoVitalController::class, 'store']
        )->name('signos-vitales.store');
    });


    /*
|--------------------------------------------------------------------------
| EXAMEN FISICO
|--------------------------------------------------------------------------
*/

    Route::middleware('permission:crear examen fisico')->group(function () {
        Route::post(
            '/consultas/{consulta}/examen-fisico',
            [ExamenFisicoController::class, 'store']
        )->name('examen-fisico.store');
    });


    /*
|--------------------------------------------------------------------------
| EVOLUCIONES
|--------------------------------------------------------------------------
*/

    Route::middleware('permission:crear evoluciones')->group(function () {
        Route::post(
            '/consultas/{consulta}/evoluciones',
            [EvolucionController::class, 'store']
        )->name('evoluciones.store');
    });


    /*
|--------------------------------------------------------------------------
| RECETA
|--------------------------------------------------------------------------
*/

    Route::middleware('permission:generar recetas')->group(function () {
        Route::get(
            '/consultas/{consulta}/receta/pdf',
            [RecetaController::class, 'generar']
        )->name('receta.pdf');
    });
});
