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

class ImpresionNominaPdfController extends Controller
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
        ->where('nomina_concepts.nomina_id', $nomina_id)
        ->groupBy('nomina_concepts.expediente', 'employees.nombre', 'employees.paterno', 'employees.materno', 'salaries.puesto')
        ->orderBy('salaries.id')
        ->orderBy('employees.paterno')
        ->orderBy('employees.materno')
        ->orderBy('employees.nombre')
        ->get();

        // Obtener número de registros
        $numeroDeRegistros = $nominaconcepts->count();            
    
        // Obtener totales
        $totalPercepciones = NominaConcept::getTotalNominaPercepciones($nomina_id);
        $totalDeducciones = NominaConcept::getTotalNominaDeducciones($nomina_id);

        // Generar PDF
        $pdf = PDF::loadview('reports.impresion-nomina-pdf', compact('nomina', 'nominaconcepts','totalPercepciones','totalDeducciones','numeroDeRegistros','company','firma'));

        return $pdf->stream('Nomina_'.$fecha.'.pdf');
    }   


} // class
