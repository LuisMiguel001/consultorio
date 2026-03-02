<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudio extends Model
{
    protected $fillable = [
        'paciente_id',
        'tipo_estudio',
        'fecha',
        'resultado',
        'medico',
        'archivo'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}
