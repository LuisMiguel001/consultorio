<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {

            $table->index('nombre');
            $table->index('apellido');
            $table->index('cedula');
            $table->index('telefono');
            $table->index('nss');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {

            $table->dropIndex(['nombre']);
            $table->dropIndex(['apellido']);
            $table->dropIndex(['cedula']);
            $table->dropIndex(['telefono']);
            $table->dropIndex(['nss']);
            $table->dropIndex(['created_at']);
        });
    }
};
