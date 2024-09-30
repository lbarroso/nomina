<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Calendar;
use Illuminate\Support\Facades\Auth;
use App\Models\NominaConcept;

class AcumuladoNominaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // formulario
    public function index()
    {

        $user = Auth::user();
        
        $calendar = Calendar::where('almcnt', $user->almcnt)
		->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first(); 

        $ultimaSemanaCalendario = Calendar::getUltimaSemanaCalendario($calendar->year, $calendar->almcnt);

        $ultimaSemanaCerrada = Calendar::getUltimaSemanaCerrada($calendar->year, $calendar->almcnt);

        // Generar
        return view('reports.acumulado-form', compact('calendar','ultimaSemanaCalendario','ultimaSemanaCerrada'));

    }


    // resultados nominas acumulados
    public function acumulado(Request $request)
    {

        // Validar los datos del formulario
        $request->validate([
            'year' => 'required|integer',
            'semanaInicio' => 'required|integer',
            'semanaFin' => 'required|integer|gte:semanaInicio',
        ], [
            'semanaFin.gte' => 'La semana final debe ser mayor o igual a la semana inicial.',
        ]);

        $user = Auth::user();
        
        $calendar = Calendar::where('almcnt', $user->almcnt)
		->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first(); 

        $year = $request->input('year');
        $semanaInicio = $request->input('semanaInicio');
        $semanaFin = $request->input('semanaFin'); 

        // acumulado empleados
        $nominaconcepts = NominaConcept::select('nomina_concepts.expediente', 'employees.rfc', 'employees.curp', 'employees.nombre', 'employees.paterno', 'employees.materno','salaries.puesto')
        ->join('employees', function ($join) {
            $join->on('nomina_concepts.expediente', '=', 'employees.expediente')
                ->on('nomina_concepts.almcnt', '=', 'employees.almcnt');
        })
        ->join('salaries', 'nomina_concepts.salary_id', '=', 'salaries.id')
        ->where('nomina_concepts.year', $year)
        ->where('nomina_concepts.almcnt', $calendar->almcnt)
        ->whereBetween('nomina_concepts.semana', [$semanaInicio, $semanaFin])
        ->groupBy('nomina_concepts.expediente', 'employees.rfc', 'employees.curp', 'employees.nombre', 'employees.paterno', 'employees.materno', 'salaries.puesto')
        ->orderBy('employees.curp')
        ->get();		
		
		// acumulado conceptos
		$concepts = NominaConcept::select('nomina_concepts.concept_id', 'concepts.concepto', 'nomina_concepts.tipo')
			->join('concepts', 'nomina_concepts.concept_id', '=', 'concepts.id')
			->where('nomina_concepts.year', $year)
			->where('nomina_concepts.almcnt', $calendar->almcnt)
			->whereIn('nomina_concepts.tipo', ['percepcion', 'deduccion'])
			->whereBetween('nomina_concepts.semana', [$semanaInicio, $semanaFin])
			->groupBy('nomina_concepts.concept_id', 'concepts.concepto', 'nomina_concepts.tipo')
			->orderBy('nomina_concepts.tipo', 'DESC')
			->get();		

        // Generar table
        return view('reports.acumulado-results', compact('nominaconcepts','concepts', 'semanaInicio', 'semanaFin', 'year','calendar'));

    }    

} // class

