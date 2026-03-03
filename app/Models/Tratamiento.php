<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    protected $fillable = [
        'consulta_id',
        'medicamento',
        'dosis',
        'frecuencia',
        'duracion',
        'via_administracion',
        'indicaciones'
    ];

    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }
}
