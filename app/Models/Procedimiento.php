<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procedimiento extends Model
{
    protected $fillable = [
        'consulta_id',
        'nombre',
        'tipo',
        'fecha',
        'descripcion',
        'resultado',
        'complicaciones',
        'estado'
    ];

    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }
}
