<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCuenta extends Model
{
    protected $table = 'detalle_cuentas';

    protected $fillable = [
        'cuenta_paciente_id',
        'servicio_id',
        'precio',
        'cantidad',
        'subtotal'
    ];

    public function cuenta()
    {
        return $this->belongsTo(CuentaPaciente::class, 'cuenta_paciente_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}
