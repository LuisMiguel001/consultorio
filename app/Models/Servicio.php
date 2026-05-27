<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = [
        'consultorio_id',
        'nombre',
        'precio',
        'activo'
    ];

    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class);
    }

    public function detallesCuenta()
    {
        return $this->hasMany(DetalleCuenta::class);
    }
}
