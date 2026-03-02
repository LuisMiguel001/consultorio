<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
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
        'estado_civil'
    ];

    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }

    public function antecedentes()
    {
        return $this->hasMany(Antecedente::class);
    }
}
