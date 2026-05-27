<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            // Límites cuantitativos
            $table->integer('max_citas')->nullable()->after('max_pacientes');
            $table->integer('max_consultas')->nullable()->after('max_citas');
            $table->integer('max_mensajes_whatsapp')->nullable()->after('max_consultas');

            // Módulos habilitados (JSON con array de módulos)
            $table->json('modulos_habilitados')->nullable()->after('caracteristicas');

            // Características adicionales específicas
            $table->boolean('permite_archivar')->default(true)->after('modulos_habilitados');
            $table->boolean('permite_recordatorios')->default(false)->after('permite_archivar');
            $table->boolean('permite_whatsapp')->default(false)->after('permite_recordatorios');
            $table->boolean('permite_reportes_avanzados')->default(false)->after('permite_whatsapp');
            $table->boolean('permite_multiple_consultorios')->default(false)->after('permite_reportes_avanzados');
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'max_citas',
                'max_consultas',
                'max_mensajes_whatsapp',
                'modulos_habilitados',
                'permite_archivar',
                'permite_recordatorios',
                'permite_whatsapp',
                'permite_reportes_avanzados',
                'permite_multiple_consultorios',
            ]);
        });
    }
};
