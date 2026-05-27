<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsoRecurso extends Model
{
    protected $fillable = [
        'consultorio_id',
        'suscripcion_id',
        'pacientes_registrados',
        'citas_creadas',
        'consultas_creadas',
        'mensajes_whatsapp_enviados',
        'periodo_inicio',
        'periodo_fin',
    ];

    protected $casts = [
        'periodo_inicio' => 'date',
        'periodo_fin' => 'date',
    ];

    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class);
    }

    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class);
    }

    public static function obtenerPeriodoActual($consultorioId, $suscripcionId)
    {
        $inicio = now()->startOfMonth();
        $fin = now()->endOfMonth();

        return static::firstOrCreate(
            [
                'consultorio_id' => $consultorioId,
                'suscripcion_id' => $suscripcionId,
                'periodo_inicio' => $inicio,
            ],
            [
                'periodo_fin' => $fin,
                'pacientes_registrados' => 0,
                'citas_creadas' => 0,
                'consultas_creadas' => 0,
                'mensajes_whatsapp_enviados' => 0,
            ]
        );
    }
}
