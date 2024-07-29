<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ConceptController;
use App\Http\Controllers\CalcularNominaController;
use App\Http\Controllers\NominaConceptController;
use App\Http\Controllers\ImpresionNominaController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// integracion de salarios
Route::get('/home', [HomeController::class, 'integracionSalaries'])->name('home');
Route::get('employee/{id}/edit', [HomeController::class, 'editiVariablesSdi'])->name('employee.edit');
Route::post('variables/update', [HomeController::class, 'updatevariablessdi'])->name('variables.update');

// empleados
Route::apiResource('employees',EmployeeController::class)->middleware('auth');
Route::get('salaries/all',[EmployeeController::class,'salaries'])->name('employees.salaries');
Route::get('terminate/{id}',[EmployeeController::class,'showTerminateForm']);
Route::get('activate/{id}',[EmployeeController::class,'activate']);
Route::post('employees/terminate', [EmployeeController::class, 'terminate'])->name('employees.terminate');
Route::get('employee/create', [EmployeeController::class, 'create'])->name('employee.create');

// salarios
Route::get('salaries', [SalaryController::class, 'index'])->name('salaries.index');

// calendario de pago
Route::get('calendars', [CalendarController::class, 'index'])->name('calendars.index');

// conceptos de nomina
Route::get('concepts', [ConceptController::class, 'index'])->name('concepts.index');
Route::get('/nomina_concepts/create', [NominaConceptController::class, 'create'])->name('nomina_concepts.create');
Route::post('/nomina_concepts', [NominaConceptController::class, 'store'])->name('nomina_concepts.store');
Route::delete('/nomina_concepts/{id}', [NominaConceptController::class, 'destroy'])->name('nomina_concepts.destroy');

// calcular nomina
Route::get('/calcular-formulas', [CalcularNominaController::class, 'calcularformulas'])->name('calcular.formulas');
Route::post('/calcular-nomina', [CalcularNominaController::class, 'calcularNomina'])->name('calcular.nomina');
Route::get('/calculo-success', [CalcularNominaController::class, 'calculosuccess'])->name('calculo.success');
Route::get('/reset-form', [CalcularNominaController::class, 'resetform'])->name('reset.form');
Route::post('/reset-movements', [CalcularNominaController::class, 'resetMovements'])->name('reset.movements');

// reportes
Route::get('/impresion-tabla', [ImpresionNominaController::class, 'impresiontabla'])->name('impresion.tabla');
Route::post('/impresion-tabla', [ImpresionNominaController::class, 'impresiontabla'])->name('impresion.tabla');
Route::get('/impresion-pdf', [ImpresionNominaController::class, 'pdf'])->name('impresion.pdf');
