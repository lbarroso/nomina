<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlantillaNomina;
use App\Models\Calendar;
use App\Models\NominaConcept;
use App\Models\Employee;
use Excel;
use App\Exports\PlantillaNominasExport;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class PlantillaNominaController extends Controller
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

        $year = !empty($request->year) ? $request->year : $user->currentYear;

        $semana = !empty($request->semana) ? $request->semana : $calendar->semana;
        
        $ultimaSemanaCalendario = Calendar::getUltimaSemanaCalendario($year, $calendar->almcnt);

        // $ultimaSemanaCerrada = Calendar::getUltimaSemanaCerrada($year, $calendar->almcnt);
        $ultimaSemanaCerrada = $calendar->semana;

        return view('reports.plantillas', compact('year', 'semana', 'ultimaSemanaCalendario', 'ultimaSemanaCerrada'));

    }

   /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $UMA =  env('UMA'); // Accede a la variable UMA desde .ENV

        $calendar = Calendar::where('almcnt', $user->almcnt)
        ->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first(); 
        
        $year = !empty($request->year) ? $request->year : $calendar->year;
        $semana = !empty($request->semana) ? $request->semana : $calendar->semana;
        
        // Vaciar la tabla plantilla_nominas para el almcnt actual
        PlantillaNomina::where('almcnt', $user->almcnt)->delete();

        // listar empleados
        $results = NominaConcept::select('employees.curp', 'nomina_concepts.expediente', 'nomina_concepts.fecha')
        ->join('employees', function ($join) {
            $join->on('nomina_concepts.expediente', '=', 'employees.expediente')
                ->on('nomina_concepts.almcnt', '=', 'employees.almcnt');
        })
        ->where('nomina_concepts.year', $year)
        ->where('nomina_concepts.almcnt', $user->almcnt)
        ->where('nomina_concepts.semana', $semana)
        ->groupBy('nomina_concepts.expediente')
        ->orderBy('employees.curp')
        ->get();

        $fechaPago = Calendar::getFechaPago($year, $user->almcnt, $semana);  

        // Crear un nuevo registro en la tabla plantilla_nominas para cada resultado
        foreach ($results as $result) {

            $employee = Employee::where('almcnt', $user->almcnt)
            ->where('expediente', $result->expediente)
            ->first();         
            
			 // obtener SDI
			$SDI = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 3, $semana, $semana);

            // Obtener montos
            $sueldo = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 1, $semana, $semana);
            $pagoDomingo = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 7, $semana, $semana);
            $diaFestivo = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 12, $semana, $semana);
            $diasNoIncluidos = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 19, $semana, $semana);
            $percepcionOtros = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 70, $semana, $semana);
            $sueldo += ($pagoDomingo + $diaFestivo + $diasNoIncluidos + $percepcionOtros);

            $imss = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 57, $semana, $semana);
            $isr = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 58, $semana, $semana);
            $primaDominical = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 2, $semana, $semana);
            $subsidio = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 99, $semana, $semana);
            if($subsidio <=0) $subsidio = 0;
            $infonavit = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 83, $semana, $semana);
            $pension = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 101, $semana, $semana);
            $incapacidad = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 53, $semana, $semana);

            $desctoFaltas = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 52, $semana, $semana);           
            $sinGoceSueldo = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 54, $semana, $semana);
            $desctoFaltas += $sinGoceSueldo;
           
            $primaVacacional = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 9, $semana, $semana);
            $exentoPrimaVacacional = ($primaVacacional > 0) ? $UMA * 15 : 0;			
			if($exentoPrimaVacacional > $primaVacacional) $exentoPrimaVacacional = $primaVacacional;
            
            $aguinaldo = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 18, $semana, $semana);
            $exentoAguinaldo = ($aguinaldo > 0) ? $UMA * 30 : 0;
			if($exentoAguinaldo > $aguinaldo) $exentoAguinaldo = $aguinaldo;
            
            $retroactivo = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 34, $semana, $semana);
            $premios = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 16, $semana, $semana);
            $productividad = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 102, $semana, $semana);
            $apoyoLentes = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 100, $semana, $semana);
            $apoyoDental = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 103, $semana, $semana);
            $separacion = NominaConcept::getMonto($year, $calendar->almcnt, $result->expediente, 22, $semana, $semana);

            PlantillaNomina::create([
                'CURP' => $result->curp,
                'DiasPagados' => $calendar->diasPagados,
                'FechaDePago' => $fechaPago, // Puedes ajustar la fecha según sea necesario
                'ConceptoSueldo' => $sueldo,
                'ConceptoIMSS' =>  $imss,
                'SDI_Indemnizacion' => $SDI,
                'ConceptoISR' => $isr,
                'Concepto_P_Dominical' => $primaDominical,
                'Concepto_Sub_Emp' => 0,
                'Subsidio_tabla' => $subsidio,
                'ConceptoInfonavit' => $infonavit,
                'Concepto_Pension_Alim' => $pension,
                'Concepto_Desc_Faltas' => $desctoFaltas,
                'Concepto_Desc_Inc' => $incapacidad,
                'Concepto_P_Vacacional' => $primaVacacional,
                'Exento_P_Vacacional' => $exentoPrimaVacacional,
                'ConceptoRetroactivo' => $retroactivo,
                'Concepto_Aguinaldo' => $aguinaldo,
                'Exento_Aguinaldo' => $exentoAguinaldo,
                'Concepto_Premios' => $premios,
                'Concepto_Productividad' => $productividad,
                'Concepto_ayuda_lentes' => $apoyoLentes,
                'Exento_ayuda_lentes' => $apoyoLentes,
                'Concepto_apoyo_dental' => $apoyoDental,
                'Exento_apoyo_dental' => $apoyoDental,
                'Concepto_separacion' => $separacion,
                'Exento_separacion' => $separacion,                
                'almcnt' => $calendar->almcnt,
            ]);
        }

        // return response()->json(['success'=>'Registro creado exitosamente.']);

        // Redirigir o retornar una respuesta adecuada
        return redirect()->back()->with('nomina', $semana);
    }

    /**
     * Export the plantilla_nominas table to an Excel file.
     */
    public function excel(Request $request)
    {

        $semana = $request->semana ? $request->semana : 0;

        return Excel::download(new PlantillaNominasExport, 'Plantilla_Nomina-'.$semana.'.xlsx');
    }    

} // class

