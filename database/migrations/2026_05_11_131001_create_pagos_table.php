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
        Schema::create('pagos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('suscripcion_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('consultorio_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('plan_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->decimal('monto', 10, 2);

            $table->enum('estado', [
                'pendiente',
                'aprobado',
                'rechazado',
            ])->default('pendiente');

            $table->enum('metodo_pago', [
                'transferencia',
                'efectivo',
                'paypal',
                'stripe',
            ])->default('transferencia');

            $table->string('referencia')->nullable();

            $table->string('comprobante')->nullable();

            $table->text('notas')->nullable();

            $table->timestamp('fecha_pago')->nullable();

            $table->foreignId('aprobado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
