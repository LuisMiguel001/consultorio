<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Suscripcion extends Model
{
    use HasFactory;
    protected $table = 'suscripciones';

    protected $fillable = [
        'consultorio_id',
        'plan_id',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'periodo',
        'proximo_pago',
        'monto_pagado',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'proximo_pago' => 'date',
        'monto_pagado' => 'decimal:2',
    ];

    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }

    public function estaActiva()
    {
        return $this->estado === 'activa'
            && now()->lessThanOrEqualTo($this->fecha_fin);
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

    public static function crearConPago(array $data, $comprobante = null)
    {
        $suscripcionActiva = self::where('consultorio_id', $data['consultorio_id'])
            ->where('estado', 'activa')
            ->where('fecha_fin', '>', now())
            ->exists();

        if ($suscripcionActiva) {
            throw new \Exception('Este consultorio ya tiene una suscripción activa');
        }

        $plan = Plan::findOrFail($data['plan_id']);
        $periodo = $data['periodo'] ?? 'mensual';

        $fechaInicio = now();
        $fechaFin = $periodo === 'mensual'
            ? now()->addMonth()
            : now()->addYear();

        $monto = $periodo === 'mensual'
            ? $plan->precio_mensual
            : $plan->precio_anual;

        $suscripcion = self::create([
            'consultorio_id' => $data['consultorio_id'],
            'plan_id' => $plan->id,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'proximo_pago' => $fechaFin,
            'estado' => 'pendiente',
            'periodo' => $periodo,
            'monto_pagado' => $monto,
        ]);

        // Asegurar que existe la clase Pago
        if (class_exists(\App\Models\Pago::class)) {
            $pago = \App\Models\Pago::create([
                'suscripcion_id' => $suscripcion->id,
                'consultorio_id' => $data['consultorio_id'],
                'plan_id' => $plan->id,
                'monto' => $monto,
                'estado' => 'pendiente',
                'metodo_pago' => $data['metodo_pago'] ?? 'transferencia',
                'referencia' => $data['referencia'] ?? null,
                'notas' => $data['notas'] ?? null,
                'comprobante' => $comprobante,
                'fecha_pago' => now(),
            ]);
        } else {
            $pago = null;
        }

        return [
            'suscripcion' => $suscripcion,
            'pago' => $pago
        ];
    }
}
