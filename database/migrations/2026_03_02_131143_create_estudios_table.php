<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('consulta_id')
                ->constrained('consultas')
                ->onDelete('cascade');

            $table->string('tipo_estudio');

            $table->enum('estado', ['indicado', 'realizado'])
                ->default('indicado');

            $table->date('fecha_estudio')->nullable();

            $table->text('resultado')->nullable();

            $table->string('archivo')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudios');
    }
};
