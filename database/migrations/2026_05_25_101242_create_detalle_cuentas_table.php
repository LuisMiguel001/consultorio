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
        Schema::create('detalle_cuentas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('cuenta_paciente_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('servicio_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('precio', 10, 2);

            $table->integer('cantidad')
                ->default(1);

            $table->decimal('subtotal', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_cuentas');
    }
};
