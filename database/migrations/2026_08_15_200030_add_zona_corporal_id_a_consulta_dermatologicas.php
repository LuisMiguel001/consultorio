<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consulta_dermatologicas', function (Blueprint $table) {
            $table->foreignId('zona_corporal_id')
                ->nullable()
                ->after('lesion_localizacion')
                ->constrained('zonas_corporales')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('consulta_dermatologicas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('zona_corporal_id');
        });
    }
};
