<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'fecha_consulta',
        'tipo_consulta',
        'motivo_consulta',
        'enfermedad_actual',
        'plan',
        'observaciones'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }


    public function estudios()
    {
        return $this->hasMany(Estudio::class);
    }

    public function diagnosticos()
    {
        return $this->hasMany(Diagnostico::class);
    }

    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class);
    }

    public function procedimientos()
    {
        return $this->hasMany(Procedimiento::class);
    }

    public function signoVital()
    {
        return $this->hasOne(SignoVital::class);
    }

    public function examenFisico()
    {
        return $this->hasOne(ExamenFisico::class);
    }
}
