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

    public function consumos()
    {
        return $this->hasMany(ConsumoPlan::class);
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

    public function consumoActual()
    {
        return $this->consumos()
            ->where('mes', now()->month)
            ->where('anio', now()->year)
            ->first();
    }

    public function usoRecursoActual()
    {
        $suscripcion = $this->suscripcionActiva;

        if (!$suscripcion) {
            return null;
        }

        return UsoRecurso::obtenerPeriodoActual($this->id, $suscripcion->id);
    }

    public function puedeRealizarAccion($accion)
    {
        $suscripcion = $this->suscripcionActiva;

        if (!$suscripcion) {
            return [
                'puede' => false,
                'mensaje' => 'No hay suscripción activa',
                'limite' => 0,
                'usado' => 0
            ];
        }

        $plan = $suscripcion->plan;
        $uso = $this->usoRecursoActual();

        if (!$uso) {
            return ['puede' => true, 'mensaje' => '', 'limite' => 'ilimitado', 'usado' => 0];
        }

        switch ($accion) {
            case 'crear_paciente':
                $limite = $plan->max_pacientes;
                $usado = $uso->pacientes_registrados;
                $tipo = 'pacientes';
                break;

            case 'crear_cita':
                $limite = $plan->max_citas;
                $usado = $uso->citas_creadas;
                $tipo = 'citas';
                break;

            case 'crear_consulta':
                $limite = $plan->max_consultas;
                $usado = $uso->consultas_creadas;
                $tipo = 'consultas';
                break;

            case 'enviar_whatsapp':
                $limite = $plan->max_mensajes_whatsapp;
                $usado = $uso->mensajes_whatsapp_enviados;
                $tipo = 'mensajes WhatsApp';
                break;

            default:
                return ['puede' => true, 'mensaje' => '', 'limite' => 'ilimitado', 'usado' => 0];
        }

        // null = ilimitado
        if ($limite === null) {
            return ['puede' => true, 'mensaje' => '', 'limite' => 'ilimitado', 'usado' => $usado];
        }

        if ($usado >= $limite) {
            return [
                'puede' => false,
                'mensaje' => "Has alcanzado el límite de {$tipo} ({$usado}/{$limite}). Actualiza tu plan.",
                'limite' => $limite,
                'usado' => $usado
            ];
        }

        return ['puede' => true, 'mensaje' => '', 'limite' => $limite, 'usado' => $usado];
    }

    // Verificar si tiene acceso a un módulo
    public function tieneAccesoModulo($modulo)
    {
        $plan = $this->planActual();

        if (!$plan) {
            return false;
        }

        return $plan->tieneModulo($modulo);
    }

    public function incrementarUso($tipo)
    {
        $consumo = $this->consumos()->firstOrCreate([
            'mes' => now()->month,
            'anio' => now()->year,
        ]);

        switch ($tipo) {

            case 'consulta':
                $consumo->increment('consultas');
                break;

            case 'cita':
                $consumo->increment('citas');
                break;

            case 'paciente':
                $consumo->increment('pacientes');
                break;

            case 'whatsapp':
                $consumo->increment('mensajes_whatsapp');
                break;
        }
    }
}
