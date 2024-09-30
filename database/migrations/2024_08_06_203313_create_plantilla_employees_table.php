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
        Schema::create('plantilla_employees', function (Blueprint $table) {
            $table->string('RFC', 13);
            $table->string('CURP', 18);
            $table->string('NoEmpleado', 10);
            $table->string('Nombres', 50);
            $table->string('ApellidoPaterno', 50);
            $table->string('ApellidoMaterno', 50);
            $table->string('PeriodicidadPago', 20);
            $table->string('Pais', 50)->default('México');
            $table->string('Email', 100);
            $table->string('TipoRegimen', 20);
            $table->boolean('esAsimilado')->default(false);
            $table->string('NSS', 11);
            $table->string('Puesto', 50);
            $table->string('FechaInicioRelLab',65);
            $table->string('TipoContrato', 20);
            $table->string('TipoJornada', 20);
            $table->decimal('SDI', 8, 2);
            $table->string('Departamento', 50);
            $table->string('Estado', 50);
            $table->string('CodigoPostal', 5);
            $table->string('Calle', 100);
            $table->string('NoExterior', 10);
            $table->string('NoInterior', 10)->nullable();
            $table->string('Localidad', 50);
            $table->string('Colonia', 50);
            $table->string('Municipio', 50);
            $table->string('Telefono', 15);
            $table->string('Clabe', 18);
            $table->string('Banco', 50);
            $table->text('Observaciones')->nullable();
            $table->string('Categoria', 50);
            $table->string('ZonaSalario', 20);
            $table->decimal('SueldoDiario', 8, 2);

            $table->string('TipoIngreso', 25)->nullable();
            $table->string('PorcIngPropios', 25)->nullable();
            $table->string('Sindicalizado', 25)->nullable();
            $table->string('CuentaBancaria', 45)->nullable();

            $table->smallInteger('almcnt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantilla_employees');
    }
};
