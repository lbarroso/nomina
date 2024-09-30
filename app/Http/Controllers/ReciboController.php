<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\Company;
use App\Models\Firma;
use Carbon\Carbon;
use App\Models\NominaConcept;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PDF;


class ReciboController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reciboTabla(Request $request)
    {

        $user = Auth::user();
        
        $calendar = Calendar::where('almcnt', $user->almcnt)
		->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first(); 
        
        $year = !empty($request->year) ? $request->year : $user->currentYear;
        $semana = !empty($request->semana) ? $request->semana : $calendar->semana;
        $company = Company::find($user->almcnt);

       // listar empleados
       /*
       $nominaconcepts = NominaConcept::select('nomina_concepts.expediente', 'employees.rfc', 'employees.curp', 'employees.nss', 'employees.nombre', 'employees.paterno', 'employees.materno','salaries.puesto', 'employees.fechaIngreso')
       ->join('employees', 'nomina_concepts.expediente', '=', 'employees.expediente')
       ->join('salaries', 'nomina_concepts.salary_id', '=', 'salaries.id')
       ->where('nomina_concepts.year', $year)
       ->where('nomina_concepts.almcnt', $calendar->almcnt)
       ->where('nomina_concepts.semana', $semana)
       ->groupBy('nomina_concepts.expediente', 'employees.rfc', 'employees.curp', 'employees.nss', 'employees.nombre', 'employees.paterno', 'employees.materno', 'salaries.puesto', 'employees.fechaIngreso')
       ->orderBy('employees.curp')
       ->get();
        */
       $nominaconcepts = NominaConcept::select('nomina_concepts.expediente', 'employees.rfc', 'employees.curp', 'employees.nss', 'employees.nombre', 'employees.paterno', 'employees.materno','salaries.puesto', 'employees.fechaIngreso')
       ->join('employees', function($join) {
           $join->on('nomina_concepts.expediente', '=', 'employees.expediente')
                ->on('nomina_concepts.almcnt', '=', 'employees.almcnt');
       })
       ->join('salaries', 'nomina_concepts.salary_id', '=', 'salaries.id')
       ->where('nomina_concepts.year', $year)
       ->where('nomina_concepts.almcnt', $calendar->almcnt)
       ->where('nomina_concepts.semana', $semana)
       ->groupBy('nomina_concepts.expediente', 'employees.rfc', 'employees.curp', 'employees.nss', 'employees.nombre', 'employees.paterno', 'employees.materno', 'salaries.puesto', 'employees.fechaIngreso')
       ->orderBy('employees.curp')
       ->get();
   
        
        $ultimaSemana = Calendar::getUltimaSemanaCalendario($year, $calendar->almcnt);
        $fechaElaboracion = NominaConcept::getFechaElaboracion($year, $calendar->almcnt, $semana);

        // Obtener totales
        $totalPercepciones = NominaConcept::getTotalPercepciones($year, $calendar->almcnt, $semana);                
        $totalDeducciones = NominaConcept::getTotalDeducciones($year, $calendar->almcnt, $semana);

        $periodo = Calendar::where('almcnt', $user->almcnt)
        ->where('year', $year)
        ->where('semana', $semana)
        ->first();

        // Generar tabla
        return view('reports.recibo-table', compact('periodo','company','nominaconcepts', 'calendar', 'fechaElaboracion', 'totalPercepciones', 'totalDeducciones', 'ultimaSemana', 'semana', 'year'));

    }        

    public function pdf(Request $request)
    {
        date_default_timezone_set('America/Mexico_City');
        $fecha  = date('d-m-Y\TH:i:s');
                
        $user = Auth::user();
        
        $calendar = Calendar::where('almcnt', $user->almcnt)
		->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first(); 
        
        $year = !empty($request->year) ? $request->year : $user->currentYear;
        $semana = !empty($request->semana) ? $request->semana : $calendar->semana;
        $company = Company::find($user->almcnt);

       // listar empleados
       /*
       $nominaconcepts = NominaConcept::select('nomina_concepts.expediente', 'employees.rfc', 'employees.curp', 'employees.nss', 'employees.nombre', 'employees.paterno', 'employees.materno','salaries.puesto', 'employees.fechaIngreso')
       ->join('employees', 'nomina_concepts.expediente', '=', 'employees.expediente')
       ->join('salaries', 'nomina_concepts.salary_id', '=', 'salaries.id')
       ->where('nomina_concepts.year', $year)
       ->where('nomina_concepts.almcnt', $calendar->almcnt)
       ->where('nomina_concepts.semana', $semana)
       ->groupBy('nomina_concepts.expediente', 'employees.rfc', 'employees.curp', 'employees.nss', 'employees.nombre', 'employees.paterno', 'employees.materno', 'salaries.puesto', 'employees.fechaIngreso')
       ->orderBy('employees.curp')
       ->get();
        */
        $nominaconcepts = NominaConcept::select('nomina_concepts.expediente', 'employees.rfc', 'employees.curp', 'employees.nss', 'employees.nombre', 'employees.paterno', 'employees.materno','salaries.puesto', 'employees.fechaIngreso')
        ->join('employees', function($join) {
            $join->on('nomina_concepts.expediente', '=', 'employees.expediente')
                 ->on('nomina_concepts.almcnt', '=', 'employees.almcnt');
        })
        ->join('salaries', 'nomina_concepts.salary_id', '=', 'salaries.id')
        ->where('nomina_concepts.year', $year)
        ->where('nomina_concepts.almcnt', $calendar->almcnt)
        ->where('nomina_concepts.semana', $semana)
        ->groupBy('nomina_concepts.expediente', 'employees.rfc', 'employees.curp', 'employees.nss', 'employees.nombre', 'employees.paterno', 'employees.materno', 'salaries.puesto', 'employees.fechaIngreso')
        ->orderBy('employees.curp')
        ->get();        

       $fechaElaboracion = NominaConcept::getFechaElaboracion($year, $calendar->almcnt, $semana);

       // Obtener totales
       $totalPercepciones = NominaConcept::getTotalPercepciones($year, $calendar->almcnt, $semana);                
       $totalDeducciones = NominaConcept::getTotalDeducciones($year, $calendar->almcnt, $semana);

       $periodo = Calendar::where('almcnt', $user->almcnt)
       ->where('year', $year)
       ->where('semana', $semana)
       ->first();

        // Generar PDF
        $pdf = PDF::loadview('reports.recibo-pdf', compact('periodo', 'company','nominaconcepts', 'calendar', 'fechaElaboracion', 'totalPercepciones', 'totalDeducciones', 'semana', 'year'));

        return $pdf->stream('Recibos_'.$semana.'-'.$fecha.'.pdf');

    }

} // class
