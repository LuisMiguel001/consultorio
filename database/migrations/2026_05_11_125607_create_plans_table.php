<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio_mensual', 10, 2);
            $table->decimal('precio_anual', 10, 2)->nullable();
            $table->integer('max_doctores')->default(1); // Cantidad de doctores permitidos
            $table->integer('max_secretarias')->default(1); // Cantidad de secretarias
            $table->integer('max_enfermeras')->default(0); // Cantidad de enfermeras
            $table->integer('max_pacientes')->nullable(); // null = ilimitado
            $table->json('caracteristicas')->nullable(); // Array de características
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
