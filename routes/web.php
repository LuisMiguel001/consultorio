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
use Illuminate\Http\Request;
use App\Models\Paciente;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'nocache'])->group(function () {

    // Perfil del usuario
    Route::middleware('auth')->group(function () {

        Route::get('/perfil', [UserController::class, 'perfil'])->name('perfil');
        Route::put('/perfil/update', [UserController::class, 'updatePerfil'])
            ->name('perfil.update');
        Route::put('/perfil/password', [UserController::class, 'updatePassword'])
            ->name('perfil.password');
    });

    /*Usuarios*/
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


    /*Inicio*/
    Route::middleware('permission:ver pacientes')->group(function () {
        Route::get('/consultorio/inicio', [PacienteController::class, 'inicio'])->name('pacientes.inicio');
    });

    /*Pacientes*/
    Route::middleware('permission:crear pacientes')->group(function () {
        Route::get('/pacientes', [PacienteController::class, 'create'])->name('pacientes.create');
        Route::post('/pacientes', [PacienteController::class, 'store'])->name('pacientes.store');
    });

    Route::middleware('permission:ver pacientes')->group(function () {
        Route::get('/pacientes/lista', [PacienteController::class, 'lista'])->name('pacientes.lista');
        Route::get('/pacientes/archivados', [PacienteController::class, 'archivados'])->name('pacientes.archivados');
        Route::get('/pacientes/{paciente}', [PacienteController::class, 'show'])->name('pacientes.show');
    });

    Route::middleware('permission:editar pacientes')->group(function () {
        Route::get('/pacientes/{id}/edit', [PacienteController::class, 'edit'])->name('pacientes.edit');
        Route::put('/pacientes/{id}', [PacienteController::class, 'update'])->name('pacientes.update');
        Route::post('/pacientes/{id}/restaurar', [PacienteController::class, 'restaurar'])->name('pacientes.restaurar');
    });

    Route::middleware('permission:eliminar pacientes')->group(function () {
        Route::delete('/pacientes/{id}', [PacienteController::class, 'destroy'])->name('pacientes.destroy');
    });

    /*Citas*/
    Route::middleware('permission:ver citas')->group(function () {
        Route::get('/citas', [CitaController::class, 'index'])->name('citas.index');
    });

    Route::middleware('permission:crear citas')->group(function () {
        Route::get('/citas/create', [CitaController::class, 'create'])->name('citas.create');
        Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
    });

    Route::middleware('permission:editar citas')->group(function () {
        Route::get('/citas/{id}/editar', [CitaController::class, 'edit'])->name('citas.edit');
        Route::put('/citas/{id}', [CitaController::class, 'update'])->name('citas.update');
    });

    Route::middleware('permission:eliminar citas')->group(function () {
        Route::delete('/citas/{cita}', [CitaController::class, 'destroy'])->name('citas.destroy');
    });

    Route::post('/citas/{id}/realizar', [CitaController::class, 'realizar'])->name('citas.realizar');

    Route::get('/buscar-pacientes', function (Request $request) {

        $buscar = $request->buscar;

        return Paciente::where(DB::raw("CONCAT(nombre,' ',apellido)"), 'ILIKE', "%$buscar%")
            ->orWhere('nombre', 'ILIKE', "%$buscar%")
            ->orWhere('apellido', 'ILIKE', "%$buscar%")
            ->orWhere('cedula', 'ILIKE', "%$buscar%")
            ->limit(10)
            ->get();
    });

    /*Consulta*/

    Route::middleware('permission:crear consultas')->group(function () {
        Route::get('/pacientes/{id}/consulta', [ConsultaController::class, 'create'])->name('consultas.create');
        Route::post('/consultas', [ConsultaController::class, 'store'])->name('consultas.store');
    });

    Route::middleware('permission:ver consultas')->group(function () {
        Route::get('/consultas/{consulta}', [ConsultaController::class, 'show'])->name('consultas.show');
    });


    /*ANTECEDENTES*/

    Route::middleware('permission:crear antecedentes')->group(function () {
        Route::post('/pacientes/{id}/antecedentes', [AntecedenteController::class, 'store'])->name('antecedentes.store');
    });

    Route::middleware('permission:ver antecedentes')->group(function () {
        Route::get('/pacientes/{id}/antecedentes', [AntecedenteController::class, 'index']);
    });


    /*Estudios*/

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

    /*Diagnosticos*/

    Route::middleware('permission:crear diagnosticos')->group(function () {
        Route::post(
            '/consultas/{consulta}/diagnosticos',
            [DiagnosticoController::class, 'store']
        )->name('diagnosticos.store');
    });


    /*tratamientos*/

    Route::middleware('permission:crear tratamientos')->group(function () {
        Route::post(
            '/consultas/{consulta}/tratamientos',
            [TratamientoController::class, 'store']
        )->name('tratamientos.store');
    });


    /*Procedimientos*/

    Route::middleware('permission:crear procedimientos')->group(function () {
        Route::post(
            '/consultas/{consulta}/procedimientos',
            [ProcedimientoController::class, 'store']
        )->name('procedimientos.store');
    });

    /*Signos Vitales*/

    Route::middleware('permission:crear signos vitales')->group(function () {
        Route::post(
            '/consultas/{consulta}/signos-vitales',
            [SignoVitalController::class, 'store']
        )->name('signos-vitales.store');
    });

    /*Examen Fisico*/

    Route::middleware('permission:crear examen fisico')->group(function () {
        Route::post(
            '/consultas/{consulta}/examen-fisico',
            [ExamenFisicoController::class, 'store']
        )->name('examen-fisico.store');
    });


    /*Evoluciones*/
    Route::middleware('permission:crear evoluciones')->group(function () {
        Route::post(
            '/consultas/{consulta}/evoluciones',
            [EvolucionController::class, 'store']
        )->name('evoluciones.store');
    });


    /*Recetas*/
    Route::middleware('permission:generar recetas')->group(function () {
        Route::get(
            '/consultas/{consulta}/receta/pdf',
            [RecetaController::class, 'generar']
        )->name('receta.pdf');
    });
});
