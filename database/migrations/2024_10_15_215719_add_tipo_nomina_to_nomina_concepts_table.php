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
        Schema::table('nomina_concepts', function (Blueprint $table) {
            $table->enum('tipo_nomina', ['regular', 'extraordinaria', 'aguinaldo'])->default('regular')->after('calculo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nomina_concepts', function (Blueprint $table) {
            $table->dropColumn('tipo_nomina');
        });
    }
};
