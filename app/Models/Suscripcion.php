<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Suscripcion extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultorio_id',
        'plan_id',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'periodo',
        'proximo_pago',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'proximo_pago' => 'date',
        'monto_pagado' => 'decimal:2',
    ];

    // Relaciones
    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // Métodos auxiliares
    public function estaActiva()
    {
        return $this->estado === 'activa' && $this->fecha_fin >= now();
    }

    public function diasRestantes()
    {
        return Carbon::parse($this->fecha_fin)->diffInDays(now());
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function renovar()
    {
        $this->fecha_inicio = now();

        if ($this->periodo === 'mensual') {
            $this->fecha_fin = now()->addMonth();
            $this->monto_pagado = $this->plan->precio_mensual;
        } else {
            $this->fecha_fin = now()->addYear();
            $this->monto_pagado = $this->plan->precio_anual;
        }

        $this->proximo_pago = $this->fecha_fin;
        $this->estado = 'activa';
        $this->save();
    }

    public function tienePagoCompletado()
    {
        return $this->pagos()
            ->where('estado', 'completado')
            ->exists();
    }

    public function ultimoPagoCompletado()
    {
        return $this->pagos()
            ->where('estado', 'completado')
            ->latest('fecha_pago')
            ->first();
    }

    public function estaEnPeriodoGracia()
    {
        if ($this->estado !== 'activa') {
            return false;
        }

        $diasPasados = now()->diffInDays($this->fecha_fin, false);
        return $diasPasados < 0 && abs($diasPasados) <= 7;
    }

    public function puedeRenovar()
    {
        return in_array($this->estado, ['activa', 'expirada']) &&
            $this->diasRestantes() <= 30;
    }
}
