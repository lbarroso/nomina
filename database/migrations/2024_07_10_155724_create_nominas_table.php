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
            $table->id();
            $table->integer('almcnt')->default(0);
            $table->smallInteger('semana')->default(0);
            $table->smallInteger('mes')->default(0);
            $table->smallInteger('year')->default(0);            
            $table->text('motivo')->nullable();
            $table->enum('periodicidad', ['semanal', 'especial', 'extraordinaria', 'extemporanea']);
            $table->enum('tipo_nomina', ['regular', 'extraordinaria', 'extemporanea'])->default('regular');
            $table->date('fechaInicio')->nullable(); 
            $table->date('fechaFin')->nullable();            
            $table->date('fechaPago')->nullable();
            $table->smallInteger('diasPagados')->default(7);
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
