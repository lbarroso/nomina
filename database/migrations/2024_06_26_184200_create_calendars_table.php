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
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            $table->integer('almcnt')->default(0);
            $table->smallInteger('semana')->default(0);
            $table->smallInteger('mes')->default(0);
            $table->smallInteger('year')->default(0);
            $table->date('fechaInicio')->nullable(); 
            $table->date('fechaFin')->nullable();
            $table->smallInteger('diasPagados')->default(7); 
            $table->smallInteger('status')->default(0);
            $table->smallInteger('puntero')->default(0);
            $table->smallInteger('bimestre')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendars');
    }
};
