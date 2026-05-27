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
        Schema::table('examen_fisicos', function (Blueprint $table) {
            $table->text('genitales_externos')->nullable();
            $table->text('especuloscopia')->nullable();
            $table->text('tacto_vaginal')->nullable();
            $table->string('flujo_vaginal')->nullable();
            $table->string('dolor_pelvico')->nullable();
            $table->text('hallazgos_gineco')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('examen_fisico', function (Blueprint $table) {
            //
        });
    }
};
