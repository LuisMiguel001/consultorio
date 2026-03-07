<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $fillable = [
        'usuario_id',
        'monto_inicial',
        'monto_final',
        'fecha_apertura',
        'fecha_cierre',
        'estado'
    ];

    public function movimientos()
    {
        return $this->hasMany(MovimientoCaja::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
