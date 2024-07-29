<?php

use App\Models\Salary;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->integer('almcnt')->default(0);
            $table->integer('expediente')->default(0);
            $table->string('nombre',50)->nullable();
            $table->string('paterno',50)->nullable();
            $table->string('materno',50)->nullable();
            $table->string('nss',50)->nullable();
            $table->string('rfc',13)->unique()->nullable();
            $table->string('curp',50)->nullable();            
            $table->string('email',50)->nullable();;
            $table->date('fechaNacimiento')->nullable();
            $table->date('fechaIngreso')->nullable();
            $table->date('fechaTermino')->nullable();
            $table->smallInteger('status')->default(1);
            $table->enum('genero', ['H', 'M'])->default('H');
            $table->float('infonavit',7,2)->unsigned()->default(0);
            $table->float('valeMensual',7,2)->unsigned()->default(0);
            $table->float('valeNavideno',7,2)->unsigned()->default(0);
            $table->float('variableOtro',7,2)->unsigned()->default(0);
            $table->string('calle',65)->nullable();
            $table->string('calleNoInt',65)->nullable();
            $table->string('calleNoExt',65)->nullable();
            $table->string('localidad',65)->nullable();
            $table->string('municipio',65)->nullable();
            $table->string('estado',50)->nullable();
            $table->string('telefono',10)->nullable();
            $table->string('codigoPostal',65)->nullable();            
            $table->string('regimen',45)->nullable();
            $table->string('tipoContrato',45)->default('DEFINITIVO');
            $table->string('tipoJornada',45)->default('COMPLETA 8 HORAS');
            $table->string('periodoPago',45)->default('Semanal');

            // unico compuesto
            $table->unique(["almcnt", "expediente"], 'empleado_unico');
            
            // relacion salarios muchos a uno
            $table->foreignIdFor(Salary::class)->constrained();              

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }

}
