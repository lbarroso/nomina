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
        Schema::create('firmas', function (Blueprint $table) {
            $table->id();
            $table->integer('almcnt')->default(0);
            $table->string('elaboro',65)->nullable();
            $table->string('elaboroNombre',65)->nullable();
            $table->string('valido',65)->nullable();
            $table->string('validoNombre',65)->nullable();
            $table->string('autorizo',65)->nullable();
            $table->string('autorizoNombre',65)->nullable();
            $table->string('reviso',65)->nullable();
            $table->string('revisoNombre',65)->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firmas');
    }
};

/**
 * UPDATE `firmas` set id=almcnt
 */