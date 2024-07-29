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
        Schema::create('subsidies', function (Blueprint $table) {
            $table->id();
            $table->float('limiteInferior',7,2)->unsigned()->default(0);
            $table->float('limiteSuperior',7,2)->unsigned()->default(0);
            $table->float('cuotaFija',7,2)->unsigned()->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subsidies');
    }
};
