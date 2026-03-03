<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('signos_vitales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consulta_id')->constrained()->onDelete('cascade');

            $table->integer('presion_sistolica')->nullable();
            $table->integer('presion_diastolica')->nullable();
            $table->integer('frecuencia_cardiaca')->nullable();
            $table->integer('frecuencia_respiratoria')->nullable();
            $table->decimal('temperatura', 4, 1)->nullable();
            $table->decimal('peso', 5, 2)->nullable();
            $table->decimal('talla', 4, 2)->nullable();
            $table->decimal('imc', 4, 2)->nullable();
            $table->integer('saturacion_oxigeno')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signo_vitals');
    }
};
