<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalariesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->string('puesto');
            $table->float('tab_ant')->unsigned()->default(0);
            $table->float('tab_vig')->unsigned()->default(0);
            $table->float('uma_ant')->unsigned()->default(0);
            $table->float('uma_vig')->unsigned()->default(0);
            $table->float('subsidio')->unsigned()->default(0);
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('salaries');
    }

}
