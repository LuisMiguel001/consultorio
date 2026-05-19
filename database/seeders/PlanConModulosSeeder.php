<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanConModulosSeeder extends Seeder
{
    public function run(): void
    {
        // ====================================================================
        // PLAN ESTÁNDAR - Todo incluido sin WhatsApp
        // ====================================================================
        Plan::create([
            'nombre' => 'Estándar',
            'descripcion' => 'Plan completo con todas las funcionalidades. Perfecto para consultorios que no necesitan recordatorios automáticos.',
            'precio_mensual' => 34.99,
            'precio_anual' => 349.99,

            // Límites de personal (ilimitado = null)
            'max_doctores' => null,
            'max_secretarias' => null,
            'max_enfermeras' => null,

            // Límites de recursos
            'max_pacientes' => 6000,
            'max_citas' => null, // Ilimitado
            'max_consultas' => null, // Ilimitado
            'max_mensajes_whatsapp' => 0, // Sin recordatorios WhatsApp

            // Módulos habilitados (TODOS menos administración)
            'modulos_habilitados' => [
                // Pacientes - CRUD completo + archivo
                'ver_pacientes',
                'crear_pacientes',
                'editar_pacientes',
                'eliminar_pacientes',
                'archivar_pacientes',

                // Citas - CRUD completo
                'ver_citas',
                'crear_citas',
                'editar_citas',
                'eliminar_citas',

                // Consultas - Gestión completa
                'ver_consultas',
                'crear_consultas',

                // Historial médico completo
                'ver_antecedentes',
                'crear_antecedentes',

                // Estudios y laboratorios - Completo
                'ver_estudios',
                'crear_estudios',
                'descargar_estudios',

                // Diagnósticos y tratamientos
                'crear_diagnosticos',
                'crear_tratamientos',
                'crear_procedimientos',

                // Signos vitales y examen físico
                'crear_signos_vitales',
                'crear_examen_fisico',

                // Evoluciones
                'crear_evoluciones',

                // Recetas
                'generar_recetas',

                // Caja - Gestión financiera completa
                'ver_caja',
                'abrir_caja',
                'registrar_pagos',
                'registrar_egresos',
                'cerrar_caja',
                'ver_conciliacion_caja',
            ],

            // Características especiales
            'permite_archivar' => true,
            'permite_recordatorios' => false, // NO incluye recordatorios
            'permite_whatsapp' => false, // NO incluye WhatsApp
            'permite_reportes_avanzados' => true,
            'permite_multiple_consultorios' => false,
            'activo' => true,
        ]);

        // ====================================================================
        // PLAN PLUS - Todo incluido + WhatsApp (400 mensajes/mes)
        // ====================================================================
        Plan::create([
            'nombre' => 'Plus',
            'descripcion' => 'Plan premium sin límites. Incluye 400 recordatorios automáticos de citas por WhatsApp mensualmente.',
            'precio_mensual' => 49.99,
            'precio_anual' => 499.99,

            // Sin límites de personal
            'max_doctores' => null,
            'max_secretarias' => null,
            'max_enfermeras' => null,

            // Sin límites de recursos
            'max_pacientes' => null, // ILIMITADO
            'max_citas' => null, // ILIMITADO
            'max_consultas' => null, // ILIMITADO
            'max_mensajes_whatsapp' => 400, // 400 recordatorios WhatsApp por mes

            // Módulos habilitados (TODOS menos administración)
            'modulos_habilitados' => [
                // Pacientes - CRUD completo + archivo
                'ver_pacientes',
                'crear_pacientes',
                'editar_pacientes',
                'eliminar_pacientes',
                'archivar_pacientes',

                // Citas - CRUD completo
                'ver_citas',
                'crear_citas',
                'editar_citas',
                'eliminar_citas',

                // Consultas - Gestión completa
                'ver_consultas',
                'crear_consultas',

                // Historial médico completo
                'ver_antecedentes',
                'crear_antecedentes',

                // Estudios y laboratorios - Completo
                'ver_estudios',
                'crear_estudios',
                'descargar_estudios',

                // Diagnósticos y tratamientos
                'crear_diagnosticos',
                'crear_tratamientos',
                'crear_procedimientos',

                // Signos vitales y examen físico
                'crear_signos_vitales',
                'crear_examen_fisico',

                // Evoluciones
                'crear_evoluciones',

                // Recetas
                'generar_recetas',

                // Caja - Gestión financiera completa
                'ver_caja',
                'abrir_caja',
                'registrar_pagos',
                'registrar_egresos',
                'cerrar_caja',
                'ver_conciliacion_caja',

                // Características premium
                'recordatorios',
                'whatsapp',
            ],

            // Todas las características premium
            'permite_archivar' => true,
            'permite_recordatorios' => true, // ✅ INCLUYE recordatorios
            'permite_whatsapp' => true, // ✅ INCLUYE WhatsApp
            'permite_reportes_avanzados' => true,
            'permite_multiple_consultorios' => false,
            'activo' => true,
        ]);
    }
}
