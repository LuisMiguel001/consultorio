<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // NO-OP: estas columnas ya se agregaron directamente
        // en 2026_05_11_125607_create_plans_table.php (creada como "planes").
        // Esta migración quedó obsoleta y se deja vacía para no romper el historial.
    }

    public function down(): void
    {
        // NO-OP por la misma razón.
    }
};
