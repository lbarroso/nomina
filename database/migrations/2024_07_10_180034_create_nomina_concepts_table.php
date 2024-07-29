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
        Schema::create('nomina_concepts', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('year')->default(0);
            $table->integer('almcnt')->default(0);
            $table->date('fecha')->nullable();
            $table->smallInteger('semana')->default(0);
            $table->smallInteger('diasPagados')->default(0); 
            $table->unsignedBigInteger('salary_id')->index();
            $table->integer('expediente')->default(0);
            $table->unsignedBigInteger('concept_id')->index();
            $table->decimal('monto', total: 8, places: 2)->default(0);
            $table->string('tipo')->nullable();
            $table->smallInteger('diasTrabajados')->default(0);
            $table->smallInteger('calculo')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nomina_concepts');
    }
};
