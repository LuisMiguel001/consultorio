<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_mensual',
        'precio_anual',
        'max_doctores',
        'max_secretarias',
        'max_enfermeras',
        'max_pacientes',
        'max_citas',
        'max_consultas',
        'max_mensajes_whatsapp',
        'caracteristicas',
        'modulos_habilitados',
        'permite_archivar',
        'permite_recordatorios',
        'permite_whatsapp',
        'permite_reportes_avanzados',
        'permite_multiple_consultorios',
        'activo',
    ];

    protected $casts = [
        'precio_mensual' => 'decimal:2',
        'precio_anual' => 'decimal:2',
        'caracteristicas' => 'array',
        'modulos_habilitados' => 'array',
        'activo' => 'boolean',
        'permite_archivar' => 'boolean',
        'permite_recordatorios' => 'boolean',
        'permite_whatsapp' => 'boolean',
        'permite_reportes_avanzados' => 'boolean',
        'permite_multiple_consultorios' => 'boolean',
    ];

    // Módulos disponibles en el sistema
    // Módulos disponibles en el sistema (mapeados desde permisos)
    public static function modulosDisponibles()
    {
        return [
            // Gestión de Usuarios
            'ver_usuarios' => 'Ver Usuarios',
            'crear_usuarios' => 'Crear Usuarios',
            'editar_usuarios' => 'Editar Usuarios',
            'eliminar_usuarios' => 'Eliminar Usuarios',

            // Gestión de Pacientes
            'ver_pacientes' => 'Ver Pacientes',
            'crear_pacientes' => 'Crear Pacientes',
            'editar_pacientes' => 'Editar Pacientes',
            'eliminar_pacientes' => 'Eliminar Pacientes',
            'archivar_pacientes' => 'Archivar Pacientes',

            // Gestión de Citas
            'ver_citas' => 'Ver Citas',
            'crear_citas' => 'Crear Citas',
            'editar_citas' => 'Editar Citas',
            'eliminar_citas' => 'Eliminar Citas',

            // Consultas Médicas
            'ver_consultas' => 'Ver Consultas',
            'crear_consultas' => 'Crear Consultas',

            // Historial Médico
            'ver_antecedentes' => 'Ver Antecedentes',
            'crear_antecedentes' => 'Crear Antecedentes',

            // Estudios y Laboratorios
            'ver_estudios' => 'Ver Estudios',
            'crear_estudios' => 'Crear Estudios',
            'descargar_estudios' => 'Descargar Estudios',

            // Diagnósticos y Tratamientos
            'crear_diagnosticos' => 'Crear Diagnósticos',
            'crear_tratamientos' => 'Crear Tratamientos',
            'crear_procedimientos' => 'Crear Procedimientos',

            // Signos Vitales y Examen Físico
            'crear_signos_vitales' => 'Crear Signos Vitales',
            'crear_examen_fisico' => 'Crear Examen Físico',

            // Evoluciones
            'crear_evoluciones' => 'Crear Evoluciones',

            // Recetas
            'generar_recetas' => 'Generar Recetas',

            // Gestión de Caja
            'ver_caja' => 'Ver Caja',
            'abrir_caja' => 'Abrir Caja',
            'registrar_pagos' => 'Registrar Pagos',
            'registrar_egresos' => 'Registrar Egresos',
            'cerrar_caja' => 'Cerrar Caja',
            'ver_conciliacion_caja' => 'Ver Conciliación de Caja',

            // Gestión de Consultorios
            'ver_consultorios' => 'Ver Consultorios',
            'crear_consultorios' => 'Crear Consultorios',
            'editar_consultorios' => 'Editar Consultorios',
            'eliminar_consultorios' => 'Eliminar Consultorios',

            // Características Premium
            'recordatorios' => 'Recordatorios Automáticos',
            'whatsapp' => 'Mensajería WhatsApp',
            'reportes_avanzados' => 'Reportes Avanzados',
        ];
    }

    // Verificar si el plan tiene acceso a un módulo
    public function tieneModulo($modulo)
    {
        if (!$this->modulos_habilitados) {
            return false;
        }

        return in_array($modulo, $this->modulos_habilitados);
    }

    // Obtener módulos como texto
    public function getModulosTexto()
    {
        if (!$this->modulos_habilitados) {
            return 'Ninguno';
        }

        $modulosDisponibles = static::modulosDisponibles();
        $modulos = [];

        foreach ($this->modulos_habilitados as $key) {
            $modulos[] = $modulosDisponibles[$key] ?? $key;
        }

        return implode(', ', $modulos);
    }

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class);
    }
}
