<?php

namespace App\Jobs;

use App\Models\Cita;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnviarRecordatorioCita implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public Cita $cita) {}

    public function handle(WhatsAppService $whatsapp): void
    {
        // ✅ Después — solo cancela si ya fue enviado o está Cancelada/Realizada
        if ($this->cita->recordatorio_enviado || in_array($this->cita->estado_cita, ['Cancelada', 'Realizada'])) {
            return;
        }

        $p = $this->cita->paciente;
        $doctor = \App\Models\User::find($this->cita->doctor_id);

        $meses = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre'
        ];
        $dias  = [0 => 'domingo', 1 => 'lunes', 2 => 'martes', 3 => 'miércoles', 4 => 'jueves', 5 => 'viernes', 6 => 'sábado'];

        $fc    = Carbon::parse($this->cita->fecha);
        $fecha = $dias[$fc->dayOfWeek] . ' ' . $fc->day . ' de ' . $meses[$fc->month] . ' de ' . $fc->year;
        $hora  = Carbon::parse($this->cita->hora)->format('h:i A');

        $esSandbox = config('services.twilio.whatsapp_from') === 'whatsapp:+14155238886';

        // ── Variable {{6}} paciente — indicaciones ───────────
        $indicaciones = [];
        if ($this->cita->requiere_ayuno)   $indicaciones[] = "⚠️ Debe asistir en ayunas (8-12 horas)";
        if ($this->cita->estudios_previos) $indicaciones[] = "📋 Traiga sus estudios previos";
        if ($this->cita->notas_previas)    $indicaciones[] = "📝 Nota: {$this->cita->notas_previas}";
        $var6Paciente = !empty($indicaciones) ? implode("\n", $indicaciones) : '';

        // ── Variable {{7}} doctor — indicaciones ─────────────
        $indDoc = [];
        if ($this->cita->requiere_ayuno)   $indDoc[] = "⚠️ Paciente en ayunas";
        if ($this->cita->estudios_previos) $indDoc[] = "📋 Trae estudios previos";
        if ($this->cita->notas_previas)    $indDoc[] = "📝 Notas: {$this->cita->notas_previas}";
        $var7Doctor = !empty($indDoc) ? implode("\n", $indDoc) : '';

        $enviado = false;

        // ── Envío al PACIENTE ─────────────────────────────────
        if ($p->telefono) {
            if ($esSandbox) {
                // Sandbox — mensaje libre
                $msg  = "👋 Hola *{$p->nombre} {$p->apellido}*,\n\n";
                $msg .= "Le recordamos su cita con el Dr. *{$doctor->name}*:\n\n";
                $msg .= "📅 *Fecha:* {$fecha}\n🕐 *Hora:* {$hora}\n";
                if ($this->cita->servicio_especifico) $msg .= "🩺 *Servicio:* {$this->cita->servicio_especifico}\n";
                if ($var6Paciente) $msg .= "\n{$var6Paciente}\n";
                $msg .= "\nPara reprogramar contáctenos.\n_Mensaje automático — no responder._";
                $enviado = $whatsapp->enviar($p->telefono, $msg);
            } else {
                // Producción — plantilla aprobada
                $enviado = $whatsapp->enviarConPlantilla(
                    $p->telefono,
                    config('services.twilio.template_paciente'),
                    [
                        '1' => "{$p->nombre} {$p->apellido}",
                        '2' => $doctor->name,
                        '3' => $fecha,
                        '4' => $hora,
                        '5' => $this->cita->servicio_especifico ?? 'No especificado',
                        '6' => $var6Paciente ?: 'Sin indicaciones especiales',
                    ]
                );
            }
        }

        // ── Envío al DOCTOR ───────────────────────────────────
        if ($doctor && $doctor->telefono) {
            if ($esSandbox) {
                $msg  = "🗓️ *Recordatorio de cita*\n\n";
                $msg .= "📅 *Fecha:* {$fecha}\n🕐 *Hora:* {$hora}\n";
                $msg .= "⚡ *Prioridad:* {$this->cita->prioridad}\n";
                $msg .= "👤 *Paciente:* {$p->nombre} {$p->apellido}\n";
                if ($p->telefono) $msg .= "📞 *Teléfono:* {$p->telefono}\n";
                if ($this->cita->servicio_especifico) $msg .= "🩺 *Servicio:* {$this->cita->servicio_especifico}\n";
                if ($var7Doctor) $msg .= "\n{$var7Doctor}\n";
                $msg .= "\n_Sistema de gestión de citas._";
                $whatsapp->enviar($doctor->telefono, $msg);
            } else {
                $whatsapp->enviarConPlantilla(
                    $doctor->telefono,
                    config('services.twilio.template_doctor'),
                    [
                        '1' => $fecha,
                        '2' => $hora,
                        '3' => $this->cita->prioridad,
                        '4' => "{$p->nombre} {$p->apellido}",
                        '5' => $p->telefono ?? 'No registrado',
                        '6' => $this->cita->servicio_especifico ?? 'No especificado',
                        '7' => $var7Doctor ?: 'Sin indicaciones',
                    ]
                );
            }
        }

        if ($enviado)
            $this->cita->update(['recordatorio_enviado' => true]);
    }
}
