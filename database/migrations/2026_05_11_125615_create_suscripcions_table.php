<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suscripcions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultorio_id')->constrained('consultorios')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('plans')->onDelete('cascade');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', ['activa', 'cancelada', 'expirada', 'pendiente'])->default('activa');
            $table->enum('periodo', ['mensual', 'anual'])->default('mensual');
            $table->decimal('monto_pagado', 10, 2)->nullable();
            $table->date('proximo_pago')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suscripcions');
    }
};
