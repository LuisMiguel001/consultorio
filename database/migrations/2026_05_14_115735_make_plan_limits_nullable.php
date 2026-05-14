<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {

            $table->integer('max_doctores')
                ->nullable()
                ->change();

            $table->integer('max_secretarias')
                ->nullable()
                ->change();

            $table->integer('max_enfermeras')
                ->nullable()
                ->change();

            $table->integer('max_pacientes')
                ->nullable()
                ->change();

            $table->integer('max_citas')
                ->nullable()
                ->change();

            $table->integer('max_consultas')
                ->nullable()
                ->change();

            $table->integer('max_mensajes_whatsapp')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        //
    }
};
