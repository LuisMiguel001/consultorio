<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamenFisico extends Model
{
    protected $fillable = [
        'consulta_id',
        'estado_general',
        'cabeza_cuello',
        'cardiovascular',
        'respiratorio',
        'abdomen',
        'extremidades',
        'neurologico',
        'otros',

        //Ginecología
        'genitales_externos',
        'especuloscopia',
        'tacto_vaginal',
        'flujo_vaginal',
        'dolor_pelvico',
        'hallazgos_gineco'
    ];

    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }
}
