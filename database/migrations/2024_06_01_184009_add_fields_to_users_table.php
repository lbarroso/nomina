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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('almcnt')->default(0);
            $table->string('regnom')->nullable();  
            $table->string('uonom')->nullable();
            $table->string('almacen')->nullable();
            $table->smallInteger('currentYear')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['almcnt','regnom', 'uonom', 'almacen']);
        });
    }
};
