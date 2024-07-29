<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Calendar;
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

        //dd($semana);
        // listar empleados
        $nominaconcepts = NominaConcept::select('nomina_concepts.expediente', 'employees.rfc', 'employees.curp', 'employees.nss', 'employees.nombre', 'employees.paterno', 'employees.materno','salaries.puesto')
        ->join('employees', 'nomina_concepts.expediente', '=', 'employees.expediente')
        ->join('salaries', 'nomina_concepts.salary_id', '=', 'salaries.id')
        ->where('nomina_concepts.year', $year)
        ->where('nomina_concepts.almcnt', $calendar->almcnt)
        ->where('nomina_concepts.semana', $semana)
        ->groupBy('nomina_concepts.expediente', 'employees.rfc', 'employees.curp', 'employees.nss', 'employees.nombre', 'employees.paterno', 'employees.materno', 'salaries.puesto')
        ->orderBy('employees.curp')
        ->get();

        $ultimaSemana = Calendar::getUltimaSemanaCalendario($year, $calendar->almcnt);

        $totalPercepciones = NominaConcept::getTotalPercepciones($year, $calendar->almcnt, $semana);
        
        $totalDeducciones = NominaConcept::getTotalDeducciones($year, $calendar->almcnt, $semana);

        return view('reports.impresion-table', compact('nominaconcepts','calendar', 'totalPercepciones', 'totalDeducciones', 'ultimaSemana', 'semana'));

    }

    public function pdf(Request $request)
    {
        
        $pdf = PDF::loadview('reports.impresion-pdf');
        return $pdf->download('nomina.pdf');
    }

} // class
