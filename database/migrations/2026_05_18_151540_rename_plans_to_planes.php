<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Verificar si existe la tabla 'plans' y renombrarla
        if (Schema::hasTable('plans')) {
            Schema::rename('plans', 'planes');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('planes')) {
            Schema::rename('planes', 'plans');
        }
    }
};
