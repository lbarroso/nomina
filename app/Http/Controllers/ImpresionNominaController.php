<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Calendar;
use App\Models\Company;
use App\Models\Firma;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\NominaConcept;
use Illuminate\Support\Facades\DB;
use PDF;

class ImpresionNominaController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function impresionTabla(Request $request)
    {
        $user = Auth::user();
        
        $calendar = Calendar::where('almcnt', $user->almcnt)
		->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first(); 
        
        $year = !empty($request->year) ? $request->year : $user->currentYear;

        $semana = !empty($request->semana) ? $request->semana : $calendar->semana;

        //dd($year.'/'.$semana);
        
        // listar empleados
        $nominaconcepts = NominaConcept::select('nomina_concepts.expediente', 'employees.rfc', 'employees.curp', 'employees.nss', 'employees.nombre', 'employees.paterno', 'employees.materno','salaries.puesto')
        ->join('employees', function ($join) {
            $join->on('nomina_concepts.expediente', '=', 'employees.expediente')
                ->on('nomina_concepts.almcnt', '=', 'employees.almcnt');
        })  
        ->join('salaries', 'nomina_concepts.salary_id', '=', 'salaries.id')
        ->where('nomina_concepts.year', $year)
        ->where('nomina_concepts.almcnt', $calendar->almcnt)
        ->where('nomina_concepts.semana', $semana)
        ->groupBy('nomina_concepts.expediente', 'employees.rfc', 'employees.curp', 'employees.nss', 'employees.nombre', 'employees.paterno', 'employees.materno', 'salaries.puesto')
        ->orderBy('employees.curp')
        ->get();

        $ultimaSemana = Calendar::getUltimaSemanaCalendario($year, $calendar->almcnt);

        // Obtener totales
        $totalPercepciones = NominaConcept::getTotalPercepciones($year, $calendar->almcnt, $semana);                
        $totalDeducciones = NominaConcept::getTotalDeducciones($year, $calendar->almcnt, $semana);            
        
        // Generar PDF
        return view('reports.impresion-table', compact('nominaconcepts','calendar', 'totalPercepciones', 'totalDeducciones', 'ultimaSemana', 'semana', 'year'));

    }

    public function pdf(Request $request)
    {
        $user = Auth::user();

        date_default_timezone_set('America/Mexico_City');
        $fecha  = date('d-m-Y\TH:i:s');

        $company = Company::find($user->almcnt);
        $firma = Firma::find($user->almcnt);

        $calendar = Calendar::where('almcnt', $user->almcnt)
		->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first();
        
        $year = !empty($request->year) ? $request->year : $user->currentYear;
        $semana = !empty($request->semana) ? $request->semana : $calendar->semana;

        // Listar empleados y cálculos de percepciones y deducciones
        $nominaconcepts = NominaConcept::select(
            'nomina_concepts.expediente', 
            'employees.nombre', 
            'employees.paterno', 
            'employees.materno',
            'salaries.puesto',
            DB::raw('SUM(CASE WHEN tipo = "percepcion" THEN nomina_concepts.monto ELSE 0 END) AS percepcion'),
            DB::raw('SUM(CASE WHEN tipo = "deduccion" THEN nomina_concepts.monto ELSE 0 END) AS deduccion')
        )
      
        ->join('employees', function ($join) {
            $join->on('nomina_concepts.expediente', '=', 'employees.expediente')
                ->on('nomina_concepts.almcnt', '=', 'employees.almcnt');
        })        
        ->join('salaries', 'nomina_concepts.salary_id', '=', 'salaries.id')
        ->where('nomina_concepts.year', $year)
        ->where('nomina_concepts.almcnt', $user->almcnt)
        ->where('nomina_concepts.semana', $semana)
        ->groupBy('nomina_concepts.expediente', 'employees.nombre', 'employees.paterno', 'employees.materno', 'salaries.puesto')
        ->orderBy('salaries.id')
        ->orderBy('employees.paterno')
        ->orderBy('employees.materno')
        ->orderBy('employees.nombre')
        ->get();

        // Obtener número de registros
        $numeroDeRegistros = $nominaconcepts->count();            

        // Obtener totales
        $totalPercepciones = NominaConcept::getTotalPercepciones($year, $calendar->almcnt, $semana);                
        $totalDeducciones = NominaConcept::getTotalDeducciones($year, $calendar->almcnt, $semana);

        $fechaElaboracion = NominaConcept::getFechaElaboracion($year, $calendar->almcnt, $semana);
    
        $periodo = Calendar::where('almcnt', $user->almcnt)
		->where('year', $year)
        ->where('semana', $semana)
        ->first();

        // Generar PDF
        $pdf = PDF::loadview('reports.impresion-pdf', compact('periodo','fechaElaboracion','calendar', 'semana', 'nominaconcepts','totalPercepciones','totalDeducciones','numeroDeRegistros','company','firma'));

        // return $pdf->download('CatalogDigital'.$semana.'.pdf');
        return $pdf->stream('Nomina_'.$semana.'-'.$fecha.'.pdf');
    }

} // class
