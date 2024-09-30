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
        Schema::create('plantilla_nominas', function (Blueprint $table) {
            $table->string('CURP', 18);
            $table->integer('DiasPagados');
            $table->string('FechaDePago');
            $table->decimal('ConceptoSueldo', 10, 2);
            $table->decimal('ConceptoIMSS', 10, 2);
            $table->decimal('SDI_Indemnizacion', 10, 2);
            $table->decimal('ConceptoISR', 10, 2);
            $table->decimal('Concepto_P_Dominical', 10, 2);
            $table->decimal('Concepto_Sub_Emp', 10, 2);
            $table->decimal('Subsidio_tabla', 10, 2);
            $table->decimal('ConceptoInfonavit', 10, 2);
            $table->decimal('Concepto_Pension_Alim', 10, 2);
            $table->decimal('Concepto_Desc_Faltas', 10, 2);
            $table->decimal('Concepto_Desc_Inc', 10, 2);
            $table->decimal('Concepto_P_Vacacional', 10, 2);
            $table->decimal('Exento_P_Vacacional', 10, 2);
            $table->decimal('ConceptoRetroactivo', 10, 2);
            $table->decimal('Concepto_Aguinaldo', 10, 2);
            $table->decimal('Exento_Aguinaldo', 10, 2);
            $table->decimal('Concepto_Premios', 10, 2);
            $table->decimal('Concepto_Productividad', 10, 2);
            $table->decimal('Concepto_ayuda_lentes', 10, 2);
            $table->decimal('Exento_ayuda_lentes', 10, 2);
            $table->decimal('Concepto_apoyo_dental', 10, 2);
            $table->decimal('Exento_apoyo_dental', 10, 2);
            $table->decimal('Concepto_separacion', 10, 2);
            $table->decimal('Exento_separacion', 10, 2);            
            $table->smallInteger('almcnt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantilla_nominas');
    }
};
