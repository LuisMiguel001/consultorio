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
        'servicio_especifico',
        'notas_previas',
        'estado_cita',
        'recordatorio_enviado',
        'fecha_envio_recordatorio',
        'prioridad',
        'motivo_consulta',
        'requiere_ayuno',
        'estudios_previos',
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
