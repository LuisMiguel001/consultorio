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
        'otros'
    ];

    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }
}
