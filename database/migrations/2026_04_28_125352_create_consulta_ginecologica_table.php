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
        Schema::create('consulta_ginecologicas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('consulta_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🔥 CAMPOS PROPIOS DE GINECOLOGÍA
            $table->date('fecha_ultima_menstruacion')->nullable();
            $table->string('ciclo_menstrual')->nullable();
            $table->integer('gestas')->nullable();
            $table->integer('partos')->nullable();
            $table->integer('abortos')->nullable();
            $table->integer('cesareas')->nullable();

            $table->boolean('embarazo_actual')->default(false);
            $table->integer('semanas_gestacion')->nullable();

            $table->text('metodo_anticonceptivo')->nullable();
            $table->text('vida_sexual')->nullable();

            $table->text('examen_pelvico')->nullable();
            $table->text('mamas')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consulta_ginecologica');
    }
};
