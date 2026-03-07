<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoCaja extends Model
{
    protected $table = 'movimientos_caja';

    protected $fillable = [
        'caja_id',
        'tipo',
        'concepto',
        'monto',
        'paciente_id',
        'metodo_pago',
        'usuario_id'
    ];

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
