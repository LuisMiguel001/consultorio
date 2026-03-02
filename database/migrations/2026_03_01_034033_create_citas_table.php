<?php

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

            $table->date('fecha');
            $table->time('hora');
            $table->integer('duracion_minutos')->default(30);

            $table->string('servicio_especifico')->nullable();
            $table->string('tipo_consulta')->nullable();
            $table->string('prioridad')->default('Normal');

            $table->text('notas_previas')->nullable();
            $table->text('motivo_consulta')->nullable();

            $table->boolean('requiere_ayuno')->default(false);
            $table->boolean('estudios_previos')->default(false);

            $table->string('estado_cita')->default('Programada');
            $table->boolean('recordatorio_enviado')->default(false);
            $table->timestamp('fecha_envio_recordatorio')->nullable();

            $table->timestamps();

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
