<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uso_recursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultorio_id')->constrained()->onDelete('cascade');
            $table->foreignId('suscripcion_id')->constrained('suscripcions')->onDelete('cascade');

            // Contadores actuales
            $table->integer('pacientes_registrados')->default(0);
            $table->integer('citas_creadas')->default(0);
            $table->integer('consultas_creadas')->default(0);
            $table->integer('mensajes_whatsapp_enviados')->default(0);

            // Fecha de reset (para contadores mensuales)
            $table->date('periodo_inicio');
            $table->date('periodo_fin');

            $table->timestamps();

            // Índice único para evitar duplicados
            $table->unique(['consultorio_id', 'suscripcion_id', 'periodo_inicio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uso_recursos');
    }
};
