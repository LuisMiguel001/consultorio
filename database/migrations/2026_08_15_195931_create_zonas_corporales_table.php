<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zonas_corporales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('grupo', 50)->nullable(); // Cabeza, Tronco, Miembro superior, etc. (para agrupar en el select)
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Catálogo base — igual para todos los consultorios
        $zonas = [
            ['grupo' => 'Cabeza y cuello', 'nombre' => 'Cuero cabelludo'],
            ['grupo' => 'Cabeza y cuello', 'nombre' => 'Rostro'],
            ['grupo' => 'Cabeza y cuello', 'nombre' => 'Cuello'],
            ['grupo' => 'Cabeza y cuello', 'nombre' => 'Oreja derecha'],
            ['grupo' => 'Cabeza y cuello', 'nombre' => 'Oreja izquierda'],
            ['grupo' => 'Tronco', 'nombre' => 'Tórax anterior'],
            ['grupo' => 'Tronco', 'nombre' => 'Espalda alta'],
            ['grupo' => 'Tronco', 'nombre' => 'Espalda baja / lumbar'],
            ['grupo' => 'Tronco', 'nombre' => 'Abdomen'],
            ['grupo' => 'Tronco', 'nombre' => 'Glúteos'],
            ['grupo' => 'Miembro superior', 'nombre' => 'Hombro derecho'],
            ['grupo' => 'Miembro superior', 'nombre' => 'Hombro izquierdo'],
            ['grupo' => 'Miembro superior', 'nombre' => 'Brazo derecho'],
            ['grupo' => 'Miembro superior', 'nombre' => 'Brazo izquierdo'],
            ['grupo' => 'Miembro superior', 'nombre' => 'Antebrazo derecho'],
            ['grupo' => 'Miembro superior', 'nombre' => 'Antebrazo izquierdo'],
            ['grupo' => 'Miembro superior', 'nombre' => 'Mano derecha'],
            ['grupo' => 'Miembro superior', 'nombre' => 'Mano izquierda'],
            ['grupo' => 'Miembro inferior', 'nombre' => 'Muslo derecho'],
            ['grupo' => 'Miembro inferior', 'nombre' => 'Muslo izquierdo'],
            ['grupo' => 'Miembro inferior', 'nombre' => 'Pierna derecha'],
            ['grupo' => 'Miembro inferior', 'nombre' => 'Pierna izquierda'],
            ['grupo' => 'Miembro inferior', 'nombre' => 'Pie derecho'],
            ['grupo' => 'Miembro inferior', 'nombre' => 'Pie izquierdo'],
            ['grupo' => 'Otra', 'nombre' => 'Genital / perianal'],
            ['grupo' => 'Otra', 'nombre' => 'Múltiples zonas / generalizado'],
            ['grupo' => 'Otra', 'nombre' => 'Otra (especificar en descripción)'],
        ];

        foreach ($zonas as $i => $z) {
            DB::table('zonas_corporales')->insert([
                'nombre' => $z['nombre'],
                'grupo' => $z['grupo'],
                'orden' => $i,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('zonas_corporales');
    }
};
