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
        Schema::create('cuenta_pacientes', function (Blueprint $table) {

            $table->id();

            $table->foreignId('consultorio_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('paciente_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('consulta_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->decimal('total', 10, 2)
                ->default(0);

            $table->enum('estado', [
                'pendiente',
                'pagado',
                'parcial'
            ])->default('pendiente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuenta_pacientes');
    }
};
