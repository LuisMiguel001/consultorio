<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultaGinecologica extends Model
{
    protected $fillable = [
        'consulta_id',
        'fecha_ultima_menstruacion',
        'ciclo_menstrual',
        'gestas',
        'partos',
        'abortos',
        'cesareas',
        'embarazo_actual',
        'semanas_gestacion',
        'metodo_anticonceptivo',
        'vida_sexual',
        'examen_pelvico',
        'mamas',
    ];

    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }
}
