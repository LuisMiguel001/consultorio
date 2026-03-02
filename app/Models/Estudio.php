<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudio extends Model
{
    protected $fillable = [
        'consulta_id',
        'tipo_estudio',
        'estado',
        'fecha_estudio',
        'resultado',
        'archivo'
    ];

    protected $casts = [
        'fecha_estudio' => 'date',
    ];

    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }
}
