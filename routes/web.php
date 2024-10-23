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
use App\Http\Controllers\FirmaController;
use App\Http\Controllers\PolizaController;
use App\Http\Controllers\ReciboController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PlantillaNominaController;
use App\Http\Controllers\PlantillaEmployeeController;
use App\Http\Controllers\AcumuladoNominaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\NominaController;
use App\Http\Controllers\ReciboNominaController;
use App\Http\Controllers\PolizaNominaController;
use App\Http\Controllers\ImpresionNominaPdfController;
use App\Http\Controllers\PlantillaEspecialController;

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
Route::get('employees/plantilla/pdf', [EmployeeController::class, 'plantillapdf'])->name('employees.plantillapdf');

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
Route::get('/delete-form', [CalcularNominaController::class, 'deleteform'])->name('delete.form');
Route::post('/reset-movements', [CalcularNominaController::class, 'resetMovements'])->name('reset.movements');
Route::post('/delete-movements', [CalcularNominaController::class, 'deleteMovements'])->name('delete.movements');
// cerrar nomina
Route::get('/cierre-nomina', [CalcularNominaController::class, 'cierrenomina'])->name('cierre.nomina');
Route::post('/close-nomina', [CalcularNominaController::class, 'closeNomina'])->name('close.nomina');

// reportes
Route::get('/impresion-tabla', [ImpresionNominaController::class, 'impresiontabla'])->name('impresion.tabla');
Route::post('/impresion-tabla', [ImpresionNominaController::class, 'impresiontabla'])->name('impresion.tabla');
Route::get('/impresion-pdf/{semana}', [ImpresionNominaController::class, 'pdf'])->name('impresion.pdf');
Route::get('/impresion-nomina-pdf/{id}', [ImpresionNominaPdfController::class, 'pdf'])->name('impresion.nomina.pdf');
Route::get('/poliza-tabla', [PolizaController::class, 'polizatabla'])->name('poliza.tabla');
Route::post('/poliza-tabla', [PolizaController::class, 'polizatabla'])->name('poliza.tabla');
Route::get('/poliza-pdf/{semana}', [PolizaController::class, 'pdf'])->name('poliza.pdf');
Route::get('/poliza-nomina-pdf/{id}', [PolizaNominaController::class, 'pdf'])->name('poliza.nomina.pdf');
Route::get('/recibo-tabla', [ReciboController::class, 'recibotabla'])->name('recibo.tabla');
Route::post('/recibo-tabla', [ReciboController::class, 'recibotabla'])->name('recibo.tabla');
Route::get('/recibo-pdf/{semana}', [ReciboController::class, 'pdf'])->name('recibo.pdf');
Route::get('/recibo-nomina-pdf/{id}', [ReciboNominaController::class, 'pdf'])->name('recibo.nomina.pdf');
// acumulado
Route::get('/acumualdo', [AcumuladoNominaController::class, 'index'])->name('acumulado.index');
Route::post('/acumulado/post', [AcumuladoNominaController::class, 'acumulado'])->name('acumulado.post');

// reportes Excel
Route::get('/empleados-excel', [EmployeeController::class, 'excel'])->name('empleados.excel');
Route::get('export', [ReportController::class, 'export'])->name('export');

// firmas
Route::get('/firmas', [FirmaController::class, 'create'])->name('firmas.create');
Route::post('/firmas/update', [FirmaController::class, 'update'])->name('firmas.update');

// plantillas
Route::get('/plantillas', [PlantillaNominaController::class, 'index'])->name('plantillas.index');
Route::post('/plantilla-nomina/store', [PlantillaNominaController::class, 'store'])->name('plantilla.nomina.store');
Route::get('/plantilla-nomina-especial/store/{id}', [PlantillaEspecialController::class, 'store'])->name('plantilla.nomina.especial.store');
Route::get('/plantilla-nomina/download/{semana}', [PlantillaNominaController::class, 'excel'])->name('plantilla.nomina.download');
Route::post('/plantilla-employee/store', [PlantillaEmployeeController::class, 'store'])->name('plantilla.employee.store');
Route::get('/plantilla-employee/download/{semana}', [PlantillaEmployeeController::class, 'excel'])->name('plantilla.employee.download');

// rutas para las notificaciones
Route::resource('notifications', NotificationController::class)->only(['index', 'store']);
Route::patch('notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

// Mostrar el formulario para cambiar la contraseña
Route::get('change-password', [PasswordController::class, 'changePasswordForm'])->name('password.change');
// Actualizar la contraseña
Route::put('update-password', [PasswordController::class, 'updatePassword'])->name('password.update');

// nominas especiales
Route::resource('nominas', NominaController::class)->only(['index', 'store', 'create']);
Route::get('/nominas/empleados/{id}', [NominaController::class, 'nominasEmpleados'])->name('nominas.empleados');
Route::post('/nominas/{id}/agregar-empleado', [NominaController::class, 'agregarEmpleado'])->name('nominas.agregarEmpleado');
Route::put('/nominas/editar-empleado', [NominaController::class, 'editarEmpleado'])->name('nominas.editarEmpleado');
Route::post('/nominas/aplicar-calculo/{nomina_id}', [NominaController::class, 'aplicarCalculo'])->name('nominas.aplicarCalculo');
Route::put('/nominas/cerrar/{id}', [NominaController::class, 'cerrarNomina'])->name('nominas.cerrar');
// Ruta para eliminar un empleado de la nómina
Route::delete('/nominas/empleado/{id}', [NominaController::class, 'destroyEmpleado'])->name('nominas.empleado.destroy');

