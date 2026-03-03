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
        Schema::create('procedimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consulta_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->string('tipo')->nullable(); // quirúrgico, ambulatorio, etc
            $table->date('fecha');
            $table->text('descripcion')->nullable();
            $table->text('resultado')->nullable();
            $table->text('complicaciones')->nullable();
            $table->string('estado')->default('programado'); // programado, realizado, cancelado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedimientos');
    }
};
