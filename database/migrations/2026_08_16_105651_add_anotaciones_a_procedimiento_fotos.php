<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('procedimiento_fotos', function (Blueprint $table) {
            // Datos crudos del canvas (formas, texto, posiciones) para poder re-editar la anotación después
            $table->json('anotaciones')->nullable()->after('descripcion');

            // Imagen ya "aplanada" con las anotaciones dibujadas encima, lista para mostrar sin canvas
            $table->string('ruta_anotada')->nullable()->after('anotaciones');
        });
    }

    public function down(): void
    {
        Schema::table('procedimiento_fotos', function (Blueprint $table) {
            $table->dropColumn(['anotaciones', 'ruta_anotada']);
        });
    }
};
