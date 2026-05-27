<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumoPlan extends Model
{
    protected $table = 'consumos_planes';

    protected $fillable = [

        'consultorio_id',

        'pacientes',
        'citas',
        'consultas',
        'mensajes_whatsapp',

        'extra_consultas',
        'extra_citas',
        'extra_whatsapp',

        'monto_extras',

        'mes',
        'anio',
    ];

    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class);
    }
}
