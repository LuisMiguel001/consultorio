<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antecedente extends Model
{
    protected $fillable = [
        'paciente_id',
        'user_id',
        'antecedentes_personales',
        'antecedentes_familiares',
        'antecedentes_quirurgicos',
        'alergias',
        'medicamentos_actuales',
        'habitos'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
