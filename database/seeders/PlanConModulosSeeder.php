<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanConModulosSeeder extends Seeder
{
    public function run(): void
    {
        // ====================================================================
        // PLAN BÁSICO - Funcionalidades esenciales
        // ====================================================================
        Plan::create([
            'nombre' => 'Básico',
            'descripcion' => 'Plan inicial para consultorios pequeños. Gestión básica de pacientes y citas.',
            'precio_mensual' => 29.99,
            'precio_anual' => 299.99,

            // Límites de personal
            'max_doctores' => 1,
            'max_secretarias' => 1,
            'max_enfermeras' => 0,

            // Límites de recursos
            'max_pacientes' => 500,
            'max_citas' => 1000,
            'max_consultas' => 800,
            'max_mensajes_whatsapp' => null, // No incluido

            // Módulos habilitados (solo lo básico)
            'modulos_habilitados' => [
                // Pacientes - Solo lectura y creación
                'ver_pacientes',
                'crear_pacientes',
                'editar_pacientes',

                // Citas - Gestión básica
                'ver_citas',
                'crear_citas',

                // Consultas - Básico
                'ver_consultas',
                'crear_consultas',

                // Historial médico básico
                'ver_antecedentes',
                'crear_antecedentes',

                // Recetas
                'generar_recetas',
            ],

            // Características especiales
            'permite_archivar' => false,
            'permite_recordatorios' => false,
            'permite_whatsapp' => false,
            'permite_reportes_avanzados' => false,
            'permite_multiple_consultorios' => false,
            'activo' => true,
        ]);

        // ====================================================================
        // PLAN ESTÁNDAR - Para consultorios en crecimiento
        // ====================================================================
        Plan::create([
            'nombre' => 'Estándar',
            'descripcion' => 'Perfecto para consultorios en crecimiento. Incluye gestión completa, estudios y archivo de pacientes.',
            'precio_mensual' => 59.99,
            'precio_anual' => 599.99,

            // Límites de personal
            'max_doctores' => 3,
            'max_secretarias' => 2,
            'max_enfermeras' => 1,

            // Límites de recursos
            'max_pacientes' => 800,
            'max_citas' => 1500,
            'max_consultas' => 1200,
            'max_mensajes_whatsapp' => null, // No incluido

            // Módulos habilitados (más completo)
            'modulos_habilitados' => [
                // Usuarios
                'ver_usuarios',
                'crear_usuarios',

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

                // Estudios y laboratorios
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
            ],

            // Características especiales
            'permite_archivar' => true,
            'permite_recordatorios' => false,
            'permite_whatsapp' => false,
            'permite_reportes_avanzados' => false,
            'permite_multiple_consultorios' => false,
            'activo' => true,
        ]);

        // ====================================================================
        // PLAN PREMIUM - Todo incluido con WhatsApp y Caja
        // ====================================================================
        Plan::create([
            'nombre' => 'Premium',
            'descripcion' => 'Plan completo con todas las funcionalidades. Incluye gestión de caja, WhatsApp y reportes avanzados.',
            'precio_mensual' => 99.99,
            'precio_anual' => 999.99,

            // Límites de personal (ilimitado = null)
            'max_doctores' => null,
            'max_secretarias' => null,
            'max_enfermeras' => null,

            // Límites de recursos
            'max_pacientes' => 2000,
            'max_citas' => 4000,
            'max_consultas' => 3000,
            'max_mensajes_whatsapp' => 200, // 200 mensajes WhatsApp por mes

            // Módulos habilitados (TODO INCLUIDO)
            'modulos_habilitados' => [
                // Usuarios - CRUD completo
                'ver_usuarios',
                'crear_usuarios',
                'editar_usuarios',
                'eliminar_usuarios',

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

                // Consultorios - CRUD completo
                'ver_consultorios',
                'crear_consultorios',
                'editar_consultorios',
                'eliminar_consultorios',

                // Características premium
                'recordatorios',
                'whatsapp',
                'reportes_avanzados',
            ],

            // Características especiales
            'permite_archivar' => true,
            'permite_recordatorios' => true,
            'permite_whatsapp' => true,
            'permite_reportes_avanzados' => true,
            'permite_multiple_consultorios' => true,
            'activo' => true,
        ]);

        // ====================================================================
        // PLAN ENTERPRISE (OPCIONAL) - Para grandes organizaciones
        // ====================================================================
        Plan::create([
            'nombre' => 'Enterprise',
            'descripcion' => 'Plan corporativo sin límites. Ideal para redes de consultorios y hospitales.',
            'precio_mensual' => 199.99,
            'precio_anual' => 1999.99,

            // Sin límites
            'max_doctores' => null,
            'max_secretarias' => null,
            'max_enfermeras' => null,
            'max_pacientes' => null, // Ilimitado
            'max_citas' => null, // Ilimitado
            'max_consultas' => null, // Ilimitado
            'max_mensajes_whatsapp' => 1000, // 1000 mensajes por mes

            // TODOS los módulos habilitados
            'modulos_habilitados' => [
                // Usuarios
                'ver_usuarios',
                'crear_usuarios',
                'editar_usuarios',
                'eliminar_usuarios',

                // Pacientes
                'ver_pacientes',
                'crear_pacientes',
                'editar_pacientes',
                'eliminar_pacientes',
                'archivar_pacientes',

                // Citas
                'ver_citas',
                'crear_citas',
                'editar_citas',
                'eliminar_citas',

                // Consultas
                'ver_consultas',
                'crear_consultas',

                // Antecedentes
                'ver_antecedentes',
                'crear_antecedentes',

                // Estudios
                'ver_estudios',
                'crear_estudios',
                'descargar_estudios',

                // Diagnósticos y tratamientos
                'crear_diagnosticos',
                'crear_tratamientos',
                'crear_procedimientos',

                // Signos vitales
                'crear_signos_vitales',
                'crear_examen_fisico',

                // Evoluciones
                'crear_evoluciones',

                // Recetas
                'generar_recetas',

                // Caja
                'ver_caja',
                'abrir_caja',
                'registrar_pagos',
                'registrar_egresos',
                'cerrar_caja',
                'ver_conciliacion_caja',

                // Consultorios
                'ver_consultorios',
                'crear_consultorios',
                'editar_consultorios',
                'eliminar_consultorios',

                // Premium
                'recordatorios',
                'whatsapp',
                'reportes_avanzados',
            ],

            // Todas las características
            'permite_archivar' => true,
            'permite_recordatorios' => true,
            'permite_whatsapp' => true,
            'permite_reportes_avanzados' => true,
            'permite_multiple_consultorios' => true,
            'activo' => true,
        ]);
    }
}
