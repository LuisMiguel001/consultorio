<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planes', function (Blueprint $table) {  // Cambiado de 'plans' a 'planes'
            $table->id();
            $table->string('nombre')->unique(); // Agregar unique aquí también
            $table->text('descripcion')->nullable();
            $table->decimal('precio_mensual', 10, 2);
            $table->decimal('precio_anual', 10, 2)->nullable();
            $table->integer('max_doctores')->nullable(); // Cambiar default a nullable
            $table->integer('max_secretarias')->nullable();
            $table->integer('max_enfermeras')->nullable();
            $table->integer('max_pacientes')->nullable();
            $table->integer('max_citas')->nullable();
            $table->integer('max_consultas')->nullable();
            $table->integer('max_mensajes_whatsapp')->default(0);
            $table->json('modulos_habilitados')->nullable();
            $table->boolean('permite_archivar')->default(false);
            $table->boolean('permite_recordatorios')->default(false);
            $table->boolean('permite_whatsapp')->default(false);
            $table->boolean('permite_reportes_avanzados')->default(false);
            $table->boolean('permite_multiple_consultorios')->default(false);
            $table->json('caracteristicas')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planes');
    }
};
