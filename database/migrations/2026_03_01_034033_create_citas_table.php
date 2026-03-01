<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('paciente_id')
                  ->constrained('pacientes')
                  ->onDelete('cascade');

            $table->foreignId('doctor_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->date('fecha');
            $table->time('hora');
            $table->integer('duracion_minutos');
            $table->string('tipo_consulta');
            $table->text('notas_previas')->nullable();
            $table->string('estado_cita')->default('Pendiente');

            $table->boolean('recordatorio_enviado')->default(false);
            $table->timestamp('fecha_envio_recordatorio')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
