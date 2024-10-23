<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Nomina;
use Carbon\Carbon;
use App\Models\NominaConcept;
use Illuminate\Support\Facades\Auth;
use PDF;


class ReciboNominaController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function pdf(Request $request)
    {
        date_default_timezone_set('America/Mexico_City');
        $fecha  = date('d-m-Y\TH:i:s');
        $nomina_id = $request->id ? (int)$request->id : 0;
        $user = Auth::user();
        
        $nomina = Nomina::find($nomina_id);

        // Verificar si la nómina ya está cerrada
        if ($nomina->status == 'abierta') {
            return false;
        }        
        
        $company = Company::find($user->almcnt);

        $nominaconcepts = NominaConcept::select('nomina_concepts.expediente', 'employees.rfc', 'employees.curp', 'employees.nss', 'employees.nombre', 'employees.paterno', 'employees.materno','salaries.puesto', 'employees.fechaIngreso')
        ->join('employees', function($join) {
            $join->on('nomina_concepts.expediente', '=', 'employees.expediente')
                 ->on('nomina_concepts.almcnt', '=', 'employees.almcnt');
        })
        ->join('salaries', 'nomina_concepts.salary_id', '=', 'salaries.id')
        ->where('nomina_concepts.nomina_id', $nomina_id)
        ->groupBy('nomina_concepts.expediente')
        ->orderBy('employees.curp')
        ->get();

       // Obtener totales
       $totalPercepciones = NominaConcept::getTotalNominaPercepciones($nomina_id);
       $totalDeducciones = NominaConcept::getTotalNominaDeducciones($nomina_id);

        // Generar PDF
        $pdf = PDF::loadview('reports.recibo-nomina-pdf', compact('company','nominaconcepts', 'nomina', 'totalPercepciones', 'totalDeducciones'));

        return $pdf->stream('Recibos_Nomina_'.$nomina_id.'-'.$fecha.'.pdf');
    }

    
} // class
