<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paciente extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'fecha_nacimiento',
        'telefono',
        'email',
        'direccion',
        'sexo',
        'contactoEmergencia',
        'seguro_medico',
        'nss',
        'estado_civil',
        'doctor_id'
    ];

    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }

    public function antecedentes()
    {
        return $this->hasMany(Antecedente::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
