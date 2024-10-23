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
        Schema::create('nominas', function (Blueprint $table) {
            $table->id(); // ID único para la nómina
            $table->integer('almcnt')->default(0); // Almacén/Centro
            $table->smallInteger('semana')->default(0); // Semana del año
            $table->smallInteger('mes')->default(0); // Mes
            $table->smallInteger('year')->default(0); // Año
            $table->text('motivo')->nullable(); // Motivo de la nómina extraordinaria/extemporánea
            $table->enum('periodicidad', ['semanal', 'especial']); // Tipo de nómina
            $table->enum('tipo_nomina', ['regular', 'extraordinaria', 'aguinaldo'])->default('regular'); // Diferenciador de nóminas
            $table->date('fechaInicio')->nullable(); // Fecha de inicio del periodo
            $table->date('fechaFin')->nullable(); // Fecha de fin del periodo
            $table->date('fechaPago')->nullable(); // Fecha de pago
            $table->smallInteger('diasPagados')->default(7); // Días pagados
            $table->timestamps(); // Timestamps (created_at y updated_at)
			// Agregar índice único para evitar duplicados
			$table->unique(['year', 'almcnt', 'semana']);			
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nominas');
    }
};
