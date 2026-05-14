<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificarModuloPlan
{
    // Mapeo completo de rutas a módulos requeridos
    private $mapaModulos = [
        // Usuarios
        'usuarios.index' => 'ver_usuarios',
        'usuarios.create' => 'crear_usuarios',
        'usuarios.store' => 'crear_usuarios',
        'usuarios.edit' => 'editar_usuarios',
        'usuarios.update' => 'editar_usuarios',
        'usuarios.destroy' => 'eliminar_usuarios',

        // Pacientes
        'pacientes.lista' => 'ver_pacientes',
        'pacientes.show' => 'ver_pacientes',
        'pacientes.create' => 'crear_pacientes',
        'pacientes.store' => 'crear_pacientes',
        'pacientes.edit' => 'editar_pacientes',
        'pacientes.update' => 'editar_pacientes',
        'pacientes.destroy' => 'eliminar_pacientes',
        'pacientes.archivados' => 'archivar_pacientes',
        'pacientes.restaurar' => 'archivar_pacientes',

        // Citas
        'citas.index' => 'ver_citas',
        'citas.create' => 'crear_citas',
        'citas.store' => 'crear_citas',
        'citas.edit' => 'editar_citas',
        'citas.update' => 'editar_citas',
        'citas.destroy' => 'eliminar_citas',

        // Consultas
        'consultas.show' => 'ver_consultas',
        'consultas.create' => 'crear_consultas',
        'consultas.store' => 'crear_consultas',

        // Antecedentes
        'antecedentes.index' => 'ver_antecedentes',
        'antecedentes.store' => 'crear_antecedentes',

        // Estudios
        'estudios.index' => 'ver_estudios',
        'estudios.store' => 'crear_estudios',
        'estudios.descargar' => 'descargar_estudios',

        // Diagnósticos, Tratamientos, Procedimientos
        'diagnosticos.store' => 'crear_diagnosticos',
        'tratamientos.store' => 'crear_tratamientos',
        'procedimientos.store' => 'crear_procedimientos',

        // Signos Vitales y Examen Físico
        'signos-vitales.store' => 'crear_signos_vitales',
        'examen-fisico.store' => 'crear_examen_fisico',

        // Evoluciones
        'evoluciones.store' => 'crear_evoluciones',

        // Recetas
        'receta.pdf' => 'generar_recetas',

        // Caja (si las tienes)
        'caja.index' => 'ver_caja',
        'caja.abrir' => 'abrir_caja',
        'caja.registrar-pago' => 'registrar_pagos',
        'caja.registrar-egreso' => 'registrar_egresos',
        'caja.cerrar' => 'cerrar_caja',
        'caja.conciliacion' => 'ver_conciliacion_caja',

        // Consultorios
        'consultorios.index' => 'ver_consultorios',
        'consultorios.create' => 'crear_consultorios',
        'consultorios.store' => 'crear_consultorios',
        'consultorios.edit' => 'editar_consultorios',
        'consultorios.update' => 'editar_consultorios',
        'consultorios.destroy' => 'eliminar_consultorios',
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Admin siempre pasa
        if ($user->roles->contains('name', 'admin')) {
            return $next($request);
        }

        $consultorio = $user->consultorio;

        if (!$consultorio) {
            return redirect()->route('login')
                ->with('error', 'No tienes consultorio asignado.');
        }

        $nombreRuta = $request->route()->getName();

        // Si la ruta requiere un módulo específico
        if (isset($this->mapaModulos[$nombreRuta])) {
            $moduloRequerido = $this->mapaModulos[$nombreRuta];

            if (!$consultorio->tieneAccesoModulo($moduloRequerido)) {
                return redirect()->route('pacientes.inicio')
                    ->with('error', '🔒 Tu plan no incluye acceso a este módulo. Contacta al administrador para actualizar tu suscripción.');
            }
        }

        return $next($request);
    }
}
