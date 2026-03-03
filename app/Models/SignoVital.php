<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignoVital extends Model
{
    protected $table = 'signos_vitales';
    protected $fillable = [
        'consulta_id',
        'presion_sistolica',
        'presion_diastolica',
        'frecuencia_cardiaca',
        'frecuencia_respiratoria',
        'temperatura',
        'peso',
        'talla',
        'imc',
        'saturacion_oxigeno'
    ];

    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }
}
