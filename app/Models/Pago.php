<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'suscripcion_id',
        'consultorio_id',
        'plan_id',
        'monto',
        'estado',
        'metodo_pago',
        'referencia',
        'comprobante',
        'notas',
        'fecha_pago',
        'aprobado_por',
    ];

    protected $casts = [
        'fecha_pago' => 'datetime',
        'monto' => 'decimal:2',
    ];

    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class);
    }

    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function aprobadoPor()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }
}
