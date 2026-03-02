<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('paciente_id')
                  ->constrained('pacientes')
                  ->onDelete('cascade');

            $table->foreignId('doctor_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->date('fecha_consulta');

            $table->string('tipo_consulta');
            // Consulta / Control / Postquirurgico / Emergencia

            $table->text('motivo_consulta')->nullable();
            $table->longText('enfermedad_actual')->nullable();

            $table->text('plan')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
