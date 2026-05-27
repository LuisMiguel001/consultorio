<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consumos_planes', function (Blueprint $table) {

            $table->id();

            $table->foreignId('consultorio_id')->constrained()->cascadeOnDelete();

            $table->integer('pacientes')->default(0);
            $table->integer('citas')->default(0);
            $table->integer('consultas')->default(0);
            $table->integer('mensajes_whatsapp')->default(0);

            // EXTRAS
            $table->integer('extra_consultas')->default(0);
            $table->integer('extra_citas')->default(0);
            $table->integer('extra_whatsapp')->default(0);

            // FACTURACIÓN EXTRA
            $table->decimal('monto_extras', 10, 2)->default(0);

            $table->integer('mes');
            $table->integer('anio');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consumos_planes');
    }
};
