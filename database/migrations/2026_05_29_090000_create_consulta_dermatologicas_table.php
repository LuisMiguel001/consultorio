<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consulta_dermatologicas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('consulta_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | CLÍNICO — evaluación de la piel / lesión
            |--------------------------------------------------------------------------
            */
            $table->string('fototipo_fitzpatrick')->nullable(); // I, II, III, IV, V, VI
            $table->string('tipo_piel')->nullable(); // seca, grasa, mixta, sensible, normal

            $table->string('motivo_dermatologico')->nullable();

            $table->string('lesion_tipo')->nullable(); // mácula, pápula, nódulo, placa, vesícula, ampolla, pústula, quiste, tumor
            $table->string('lesion_localizacion')->nullable();
            $table->string('lesion_tamano')->nullable(); // ej. "1.5 cm"
            $table->string('lesion_color')->nullable();
            $table->string('lesion_bordes')->nullable(); // definidos, difusos, irregulares
            $table->string('lesion_superficie')->nullable(); // lisa, escamosa, ulcerada, verrugosa
            $table->string('tiempo_evolucion')->nullable(); // ej. "3 semanas"

            $table->boolean('prurito')->default(false);
            $table->boolean('dolor')->default(false);
            $table->boolean('ardor')->default(false);
            $table->boolean('sangrado')->default(false);
            $table->text('sintomas_asociados_otros')->nullable();

            $table->boolean('dermatoscopia_realizada')->default(false);
            $table->text('hallazgos_dermatoscopia')->nullable();

            $table->boolean('biopsia_realizada')->default(false);
            $table->text('resultado_biopsia')->nullable();

            $table->text('antecedentes_dermatologicos')->nullable(); // acné, psoriasis, dermatitis, cáncer de piel previo, etc.
            $table->text('alergias_cutaneas')->nullable();
            $table->string('exposicion_solar')->nullable(); // baja, moderada, alta

            $table->string('diagnostico_dermatologico')->nullable();
            $table->text('tratamiento_topico')->nullable();
            $table->text('tratamiento_sistemico')->nullable();

            /*
            |--------------------------------------------------------------------------
            | ESTÉTICO — procedimientos cosméticos
            |--------------------------------------------------------------------------
            */
            $table->boolean('es_procedimiento_estetico')->default(false);

            $table->string('procedimiento_estetico')->nullable();
            // botox, rellenos (ác. hialurónico), peeling químico, láser, mesoterapia,
            // hilos tensores, microneedling, radiofrecuencia, hidratación facial, etc.

            $table->string('zona_tratada')->nullable(); // frente, entrecejo, patas de gallo, pómulos, labios, cuello, etc.
            $table->string('producto_utilizado')->nullable(); // marca/tipo (toxina botulínica X, ácido hialurónico Y)
            $table->string('cantidad_aplicada')->nullable(); // ej. "20 unidades" o "1.5 ml"
            $table->string('tecnica_aplicacion')->nullable();

            $table->text('efectos_secundarios_esperados')->nullable();
            $table->boolean('consentimiento_informado')->default(false);

            $table->date('fecha_proxima_sesion')->nullable();
            $table->text('recomendaciones_post_procedimiento')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consulta_dermatologicas');
    }
};
