<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Suscripcion;
use Illuminate\Support\Facades\Mail;

class VerificarSuscripcionesExpiradas extends Command
{
    protected $signature = 'suscripciones:verificar';
    protected $description = 'Verificar y actualizar suscripciones expiradas';

    public function handle()
    {
        $this->info('Verificando suscripciones...');

        // Marcar como expiradas las que pasaron la fecha
        $expiradas = Suscripcion::where('estado', 'activa')
            ->where('fecha_fin', '<', now())
            ->get();

        foreach ($expiradas as $suscripcion) {
            $suscripcion->update(['estado' => 'expirada']);
            $this->warn("Suscripción #{$suscripcion->id} marcada como expirada");

            // Aquí podrías enviar email de notificación
            // Mail::to($suscripcion->consultorio->email)->send(new SuscripcionExpiradaMail($suscripcion));
        }

        // Alertar sobre las próximas a vencer (7 días)
        $proximasVencer = Suscripcion::where('estado', 'activa')
            ->whereBetween('fecha_fin', [now(), now()->addDays(7)])
            ->get();

        foreach ($proximasVencer as $suscripcion) {
            $dias = $suscripcion->diasRestantes();
            $this->info("Suscripción #{$suscripcion->id} vence en {$dias} días");

            // Enviar email de recordatorio
            // Mail::to($suscripcion->consultorio->email)->send(new SuscripcionProximaVencerMail($suscripcion));
        }

        $this->info('Verificación completada.');
        $this->info("Expiradas: {$expiradas->count()}");
        $this->info("Próximas a vencer: {$proximasVencer->count()}");

        return 0;
    }
}
