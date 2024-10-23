<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Firma;
use App\Models\Nomina;
use Carbon\Carbon;
use App\Models\NominaConcept;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PDF;

class PolizaNominaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function pdf(Request $request)
    {
        $user = Auth::user();

        date_default_timezone_set('America/Mexico_City');
        $fecha  = date('d-m-Y\TH:i:s');
        $nomina_id = $request->id ? (int)$request->id : 0;
        $company = Company::find($user->almcnt);
        $firma = Firma::find($user->almcnt);
        $nomina = Nomina::find($nomina_id);

        // Verificar si la nómina ya está cerrada
        if ($nomina->status == 'abierta') {
            return false;
        }       

        // Listar conceptos de percepciones y deducciones
        $nominaconcepts = DB::select("SELECT nomina_concepts.concept_id, concepts.concepto, SUM(nomina_concepts.monto) monto, nomina_concepts.tipo
        FROM nomina_concepts
        INNER JOIN concepts ON nomina_concepts.concept_id = concepts.id
        WHERE nomina_concepts.nomina_id ='$nomina_id' AND nomina_concepts.tipo IN('percepcion','deduccion')
        GROUP BY nomina_concepts.concept_id
        ORDER BY nomina_concepts.tipo DESC");
    
        // Obtener totales
        $totalPercepciones = NominaConcept::getTotalNominaPercepciones($nomina_id);
        $totalDeducciones = NominaConcept::getTotalNominaDeducciones($nomina_id);

        // Generar PDF
        $pdf = PDF::loadview('reports.poliza-nomina-pdf', compact('nomina', 'nominaconcepts', 'totalPercepciones', 'totalDeducciones', 'company', 'firma'));

        return $pdf->stream('PolizaNomina_'.$fecha.'.pdf');
    }   

}// class
