<?php
// database/migrations/[timestamp]_create_citas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');

            // Información básica de la cita
            $table->date('fecha');
            $table->time('hora');
            $table->integer('duracion_minutos')->default(30);

            // Campos específicos de cardiología
            $table->string('servicio_especifico')->nullable();
            $table->string('tipo_consulta')->nullable();
            $table->string('prioridad')->default('Normal');

            // Notas y observaciones
            $table->text('notas_previas')->nullable();
            $table->text('motivo_consulta')->nullable();

            // Indicaciones
            $table->boolean('requiere_ayuno')->default(false);
            $table->boolean('estudios_previos')->default(false);

            // Estado de la cita
            $table->string('estado_cita')->default('Programada');
            $table->boolean('recordatorio_enviado')->default(false);
            $table->timestamp('fecha_envio_recordatorio')->nullable();

            $table->timestamps();

            // Índices para búsquedas rápidas
            $table->index(['fecha', 'hora']);
            $table->index('estado_cita');
            $table->index('servicio_especifico');
        });
    }

    public function down()
    {
        Schema::dropIfExists('citas');
    }
};
