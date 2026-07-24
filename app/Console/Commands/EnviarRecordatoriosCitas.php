<?php

namespace App\Console\Commands;

use App\Jobs\EnviarRecordatorioCita;
use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EnviarRecordatoriosCitas extends Command
{
    protected $signature   = 'citas:recordatorios {--horas=24}';
    protected $description = 'Envía recordatorios WhatsApp de citas próximas';

    public function handle(): void
    {
        $horas = (int) $this->option('horas');
        $desde = Carbon::now();
        $hasta = Carbon::now()->addHours($horas);

        $citas = Cita::with('paciente')
            ->where('estado_cita', 'Programada')
            ->where('recordatorio_enviado', false)
            ->whereBetween('fecha', [$desde->toDateString(), $hasta->toDateString()])
            ->whereHas('paciente', fn($q) =>
                $q->whereNotNull('telefono')->whereNull('deleted_at')
            )
            ->get()
            ->filter(function ($c) use ($desde, $hasta) {
                $fechaSolo = Carbon::parse($c->fecha)->format('Y-m-d');
                return Carbon::parse($fechaSolo . ' ' . $c->hora)->between($desde, $hasta);
            });

        if ($citas->isEmpty()) {
            $this->info('Sin citas pendientes de recordatorio.');
            return;
        }

        foreach ($citas as $cita) {
            EnviarRecordatorioCita::dispatch($cita);
            $this->line("✓ Cita #{$cita->id} — {$cita->paciente->nombre} {$cita->paciente->apellido}");
        }

        $this->info("✅ {$citas->count()} recordatorio(s) despachados.");
    }
}
