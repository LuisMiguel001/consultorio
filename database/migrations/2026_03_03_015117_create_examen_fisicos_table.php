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
        Schema::create('examen_fisicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consulta_id')->constrained()->onDelete('cascade');

            $table->text('estado_general')->nullable();
            $table->text('cabeza_cuello')->nullable();
            $table->text('cardiovascular')->nullable();
            $table->text('respiratorio')->nullable();
            $table->text('abdomen')->nullable();
            $table->text('extremidades')->nullable();
            $table->text('neurologico')->nullable();
            $table->text('otros')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examen_fisicos');
    }
};
