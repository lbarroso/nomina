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
        Schema::create('concepts', function (Blueprint $table) {
            $table->id();
            $table->string('concepto')->nullable(); // Nombre del concepto
            $table->text('descripcion')->nullable(); // Descripción del concepto
            $table->enum('tipo',['percepcion','deduccion','informativo']); //
            $table->smallInteger('visible')->default(1); // 0 = No
            $table->enum('impuesto',['SI','NO']); // causa impuesto            
            $table->smallInteger('orden')->default(0); // sat
            $table->string('tipoSAT')->nullable();
            $table->string('exento')->nullable();
            $table->string('formula')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concepts');
    }
};

/**SELECT cta_empleados.cta, percepciones.descripcion 
FROM cta_empleados
INNER JOIN percepciones ON cta_empleados.cta = percepciones.id_per
GROUP BY cta_empleados.cta */