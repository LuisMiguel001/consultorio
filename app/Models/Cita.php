<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'fecha',
        'hora',
        'duracion_minutos',
        'tipo_consulta',
        'servicio_especifico', // NUEVO: Para los servicios que ofreces
        'notas_previas',
        'estado_cita',
        'recordatorio_enviado',
        'fecha_envio_recordatorio',
        'prioridad', // NUEVO: Urgente, Preferente, Normal
        'motivo_consulta', // NUEVO: Descripción breve
        'requiere_ayuno', // NUEVO: Para ciertos procedimientos
        'estudios_previos', // NUEVO: Si debe traer estudios
    ];

    protected $casts = [
        'fecha' => 'date',
        'recordatorio_enviado' => 'boolean',
        'requiere_ayuno' => 'boolean',
        'estudios_previos' => 'boolean',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
