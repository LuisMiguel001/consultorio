<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaPaciente extends Model
{
    protected $table = 'cuenta_pacientes';

    protected $fillable = [
        'consultorio_id',
        'paciente_id',
        'consulta_id',
        'total',
        'estado'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }

    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCuenta::class);
    }
}
