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

class PolizaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function polizaTabla(Request $request)
    {
        $user = Auth::user();
        
        $calendar = Calendar::where('almcnt', $user->almcnt)
		->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first(); 
        
        $year = !empty($request->year) ? $request->year : $user->currentYear;
        $semana = !empty($request->semana) ? $request->semana : $calendar->semana;

        $nominaconcepts = DB::select("SELECT nomina_concepts.concept_id, concepts.concepto, SUM(nomina_concepts.monto) monto, nomina_concepts.tipo
        FROM nomina_concepts
        INNER JOIN concepts ON nomina_concepts.concept_id = concepts.id
        WHERE nomina_concepts.year='$year' AND nomina_concepts.almcnt='$user->almcnt' AND nomina_concepts.semana='$semana' AND nomina_concepts.tipo IN('percepcion','deduccion')
        GROUP BY nomina_concepts.concept_id
        ORDER BY nomina_concepts.tipo DESC");
        
        $ultimaSemana = Calendar::getUltimaSemanaCalendario($year, $calendar->almcnt);
        $fechaElaboracion = NominaConcept::getFechaElaboracion($year, $calendar->almcnt, $semana);

        // Obtener totales
        $totalPercepciones = NominaConcept::getTotalPercepciones($year, $calendar->almcnt, $semana);                
        $totalDeducciones = NominaConcept::getTotalDeducciones($year, $calendar->almcnt, $semana);

        
        // Generar tabla
        return view('reports.poliza-table', compact('nominaconcepts', 'calendar', 'fechaElaboracion', 'totalPercepciones', 'totalDeducciones', 'ultimaSemana', 'semana', 'year'));

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

        // Listar conceptos de percepciones y deducciones
        $nominaconcepts = DB::select("SELECT nomina_concepts.concept_id, concepts.concepto, SUM(nomina_concepts.monto) monto, nomina_concepts.tipo
        FROM nomina_concepts
        INNER JOIN concepts ON nomina_concepts.concept_id = concepts.id
        WHERE nomina_concepts.year='$year' AND nomina_concepts.almcnt='$user->almcnt' AND nomina_concepts.semana='$semana' AND nomina_concepts.tipo IN('percepcion','deduccion')
        GROUP BY nomina_concepts.concept_id
        ORDER BY nomina_concepts.tipo DESC");
    
        // Obtener totales
        $totalPercepciones = NominaConcept::getTotalPercepciones($year, $calendar->almcnt, $semana);                
        $totalDeducciones = NominaConcept::getTotalDeducciones($year, $calendar->almcnt, $semana);

        $fechaElaboracion = NominaConcept::getFechaElaboracion($year, $calendar->almcnt, $semana);

        $periodo = Calendar::where('almcnt', $user->almcnt)
		->where('year', $year)
        ->where('semana', $semana)
        ->first();
        
        // Generar PDF
        $pdf = PDF::loadview('reports.poliza-pdf', compact('periodo','calendar', 'semana', 'fechaElaboracion', 'nominaconcepts', 'totalPercepciones', 'totalDeducciones', 'company', 'firma'));

        return $pdf->stream('Poliza_'.$semana.'-'.$fecha.'.pdf');
    }    

} // class
