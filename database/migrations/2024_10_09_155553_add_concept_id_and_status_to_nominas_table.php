<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('nominas', function (Blueprint $table) {
            // Agregar columna para la relación con concepts
            $table->unsignedBigInteger('concept_id')->nullable();

            // Agregar columna status con valores 'abierta' y 'cerrada'
            $table->enum('status', ['abierta', 'cerrada'])->default('abierta');

            // Definir la relación con la tabla concepts
            $table->foreign('concept_id')->references('id')->on('concepts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nominas', function (Blueprint $table) {
            $table->dropForeign(['concept_id']); // Eliminar relación
            $table->dropColumn('concept_id'); // Eliminar columna
            $table->dropColumn('status'); // Eliminar columna status
        });
    }
};
