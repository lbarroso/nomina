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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->integer('almcnt')->default(0);
            $table->string('nombre')->nullable();
            $table->string('rfc')->unique();
            $table->string('calle', 65)->nullable();
            $table->string('calleNo', 65)->nullable();
            $table->string('municipio', 65)->nullable();
            $table->string('localidad', 65)->nullable();
            $table->string('estado', 65)->nullable();
            $table->string('codigoPostal', 5)->nullable();
            $table->string('regimen',65)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

/**
 * UPDATE companies set id=almcnt;
 */