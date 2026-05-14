<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultorio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email',
        'ruc',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function doctores()
    {
        return $this->hasMany(User::class)->role('doctor');
    }

    public function secretarias()
    {
        return $this->hasMany(User::class)->role('secretaria');
    }

    public function enfermeras()
    {
        return $this->hasMany(User::class)->role('enfermera');
    }

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class);
    }

    public function suscripcionActiva()
    {
        return $this->hasOne(Suscripcion::class)
            ->where('estado', 'activa')
            ->whereDate('fecha_fin', '>=', now())
            ->latestOfMany();
    }

    public function obtenerSuscripcionActiva()
    {
        return $this->suscripcionActiva;
    }

    public function planActual()
    {
        return optional($this->suscripcionActiva)->plan;
    }

    public function tieneSuscripcionActiva()
    {
        $suscripcion = $this->suscripcionActiva;
        return $suscripcion && $suscripcion->estaActiva();
    }

    public function puedeAgregarUsuario($rol)
    {
        $suscripcion = $this->suscripcionActiva;

        if (!$suscripcion) {
            return [
                'puede' => false,
                'mensaje' => 'No hay suscripción activa'
            ];
        }

        $plan = $suscripcion->plan;
        $count = $this->usuarios()->role($rol)->count();

        switch ($rol) {
            case 'doctor':
                $limite = $plan->max_doctores;
                break;
            case 'secretaria':
                $limite = $plan->max_secretarias;
                break;
            case 'enfermera':
                $limite = $plan->max_enfermeras;
                break;
            default:
                return ['puede' => true, 'mensaje' => ''];
        }

        if ($limite === null) {
            return ['puede' => true, 'mensaje' => ''];
        }

        if ($count >= $limite) {
            return [
                'puede' => false,
                'mensaje' => "Has alcanzado el límite de {$rol}s ({$count}/{$limite}). Actualiza tu plan."
            ];
        }

        return ['puede' => true, 'mensaje' => ''];
    }

    public function estadoSuscripcion()
    {
        $suscripcion = $this->suscripcionActiva;

        if (!$suscripcion) {
            return [
                'estado' => 'sin_suscripcion',
                'clase' => 'danger',
                'mensaje' => 'Sin suscripción',
                'icono' => '❌'
            ];
        }

        if (!$suscripcion->estaActiva()) {
            return [
                'estado' => 'expirada',
                'clase' => 'danger',
                'mensaje' => 'Suscripción expirada',
                'icono' => '⚠️'
            ];
        }

        $dias = $suscripcion->diasRestantes();

        if ($dias <= 3) {
            return [
                'estado' => 'critico',
                'clase' => 'danger',
                'mensaje' => "Vence en {$dias} días",
                'icono' => '🔴',
                'dias' => $dias
            ];
        }

        if ($dias <= 7) {
            return [
                'estado' => 'proximo_vencer',
                'clase' => 'warning',
                'mensaje' => "Vence en {$dias} días",
                'icono' => '🟡',
                'dias' => $dias
            ];
        }

        return [
            'estado' => 'activa',
            'clase' => 'success',
            'mensaje' => 'Activa',
            'icono' => '✅',
            'dias' => $dias
        ];
    }
}
