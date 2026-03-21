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
        if ($this->cita->recordatorio_enviado || $this->cita->estado_cita !== 'Programada') {
            return;
        }

        $p = $this->cita->paciente;
        $doctor = \App\Models\User::find($this->cita->doctor_id);

        // ── Fecha en español sin depender de locale ──────────
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
        $dias = [
            0 => 'domingo',
            1 => 'lunes',
            2 => 'martes',
            3 => 'miércoles',
            4 => 'jueves',
            5 => 'viernes',
            6 => 'sábado'
        ];
        $fc    = Carbon::parse($this->cita->fecha);
        $fecha = $dias[$fc->dayOfWeek] . ' ' . $fc->day . ' de ' . $meses[$fc->month] . ' de ' . $fc->year;
        $hora  = Carbon::parse($this->cita->hora)->format('h:i A');

        // ── Mensaje al PACIENTE ──────────────────────────────
        $msgPaciente  = "👋 Hola *{$p->nombre} {$p->apellido}*,\n\n";
        $msgPaciente .= "Le recordamos su cita médica con el Dr. *{$doctor->name}*:\n\n";
        $msgPaciente .= "📅 *Fecha:* {$fecha}\n";
        $msgPaciente .= "🕐 *Hora:* {$hora}\n";

        if ($this->cita->servicio_especifico)
            $msgPaciente .= "🩺 *Servicio:* {$this->cita->servicio_especifico}\n";

        // Indicaciones especiales
        $indicaciones = [];
        if ($this->cita->requiere_ayuno)         $indicaciones[] = "⚠️ Debe asistir *en ayunas* (8-12 horas)";
        if ($this->cita->estudios_previos)       $indicaciones[] = "📋 Traiga sus *estudios previos*";

        if (!empty($indicaciones)) {
            $msgPaciente .= "\n📢 *Indicaciones importantes:*\n";
            foreach ($indicaciones as $ind) {
                $msgPaciente .= "  • {$ind}\n";
            }
        }

        if ($this->cita->notas_previas)
            $msgPaciente .= "\n📝 *Notas del médico:*\n_{$this->cita->notas_previas}_\n";

        $msgPaciente .= "\nPara reprogramar o cancelar, por favor contáctenos.\n";
        $msgPaciente .= "_Mensaje automático — no responder._";

        // ── Mensaje al DOCTOR ────────────────────────────────
        $msgDoctor  = "🗓️ *Recordatorio de cita*\n\n";

        $msgDoctor .= "📅 *Fecha:* {$fecha}\n";
        $msgDoctor .= "🕐 *Hora:* {$hora}\n";
        $msgDoctor .= "⚡ *Prioridad:* {$this->cita->prioridad}\n";

        $msgDoctor .= "👤 *Paciente:* {$p->nombre} {$p->apellido}\n";
        if ($p->cedula)   $msgDoctor .= "🪪 *Cédula:* {$p->cedula}\n";
        if ($p->telefono) $msgDoctor .= "📞 *Teléfono:* {$p->telefono}\n";

        if ($this->cita->servicio_especifico)
            $msgDoctor .= "🩺 *Servicio:* {$this->cita->servicio_especifico}\n";

        if ($this->cita->motivo_consulta)
            $msgDoctor .= "📌 *Motivo:* {$this->cita->motivo_consulta}\n";

        if ($this->cita->tipo_consulta)
            $msgDoctor .= "🤒 *Síntoma:* {$this->cita->tipo_consulta}\n";

        $indDoc = [];
        if ($this->cita->requiere_ayuno)         $indDoc[] = "⚠️ Paciente en ayunas";
        if ($this->cita->estudios_previos)       $indDoc[] = "📋 Trae estudios previos";

        if (!empty($indDoc)) {
            $msgDoctor .= "\n📋 *Indicaciones:*\n";
            foreach ($indDoc as $ind) {
                $msgDoctor .= "  • {$ind}\n";
            }
        }

        if ($this->cita->notas_previas)
            $msgDoctor .= "\n📝 *Notas:* {$this->cita->notas_previas}\n";

        $msgDoctor .= "\n_Sistema de gestión de citas._";

        // ── Envíos ───────────────────────────────────────────
        $enviado = false;

        if ($p->telefono)
            $enviado = $whatsapp->enviar($p->telefono, $msgPaciente);

        if ($doctor && $doctor->telefono)
            $whatsapp->enviar($doctor->telefono, $msgDoctor);

        if ($enviado)
            $this->cita->update(['recordatorio_enviado' => true]);
    }
}
