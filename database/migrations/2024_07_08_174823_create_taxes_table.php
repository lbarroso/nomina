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
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->enum('periodo', ['semanal', 'quincenal','mensual'])->default('semanal');
            $table->float('limiteInferior',7,2)->unsigned()->default(0);
            $table->float('limiteSuperior',7,2)->unsigned()->default(0);
            $table->float('cuotaFija',7,2)->unsigned()->default(0);
            $table->float('porcentaje',7,2)->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};

/***
 *INSERT INTO `isrs` (`id`, `limiteInferior`, `limiteSuperior`, `cuotaFija`, `porcentaje`) VALUES
(1, 0.01, 148.40, 0.00, 1.92),
(2, 148.41, 1259.72, 2.87, 6.40),
(3, 1259.73, 2213.89, 73.99, 10.88),
(4, 2213.90, 2573.55, 177.80, 16.00),
(5, 2573.56, 3081.26, 235.34, 17.92),
(6, 3081.27, 6214.46, 326.34, 21.36),
(7, 6214.47, 9794.82, 995.54, 23.52),
(8, 9794.83, 18699.94, 1837.64, 30.00),
(9, 18699.95, 24933.30, 4509.19, 32.00),
(10, 24933.31, 74799.83, 6503.84, 34.00),
(11, 74799.84, 99999.99, 23458.47, 35.00);
 */