<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procedimiento_fotos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('consulta_dermatologica_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('etapa', ['antes', 'durante', 'despues']);

            $table->string('ruta');           // path en storage
            $table->string('descripcion')->nullable();
            $table->unsignedInteger('orden')->default(0); // para varias fotos en la misma etapa

            $table->foreignId('subido_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['consulta_dermatologica_id', 'etapa']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procedimiento_fotos');
    }
};
