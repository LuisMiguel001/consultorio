<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Opción 1: Si la tabla se llama 'subscriptions'
        if (Schema::hasTable('suscripcions')) {
            Schema::rename('suscripcions', 'suscripciones');
        }
    }

    public function down(): void
    {
        Schema::rename('suscripciones', 'subscriptions');
    }
};
