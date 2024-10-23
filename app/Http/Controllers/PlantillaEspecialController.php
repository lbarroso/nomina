<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlantillaNomina;
use App\Models\NominaConcept;
use App\Models\Employee;
use App\Models\Nomina;
use Excel;
use App\Exports\PlantillaNominasExport;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PlantillaEspecialController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }    

   /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        $user = Auth::user();
        $nomina_id = (int) $request->id;

        // Validación básica
        $nomina = Nomina::find($nomina_id);
        if (!$nomina) {
            return redirect()->back()->withErrors('Nómina no encontrada.');
        }
        
        $UMA =  env('UMA'); // Accede a la variable UMA desde .ENV
        
        // Vaciar la tabla plantilla_nominas para el almcnt actual
        PlantillaNomina::where('almcnt', $user->almcnt)->delete();

        // listar empleados
        $results = NominaConcept::select('employees.curp', 'nomina_concepts.expediente', 'nomina_concepts.fecha')
        ->join('employees', function ($join) {
            $join->on('nomina_concepts.expediente', '=', 'employees.expediente')
                ->on('nomina_concepts.almcnt', '=', 'employees.almcnt');
        })
        ->where('nomina_concepts.nomina_id', $nomina_id)
        ->groupBy('nomina_concepts.expediente')
        ->orderBy('employees.curp')
        ->get(); 

        // Crear un nuevo registro en la tabla plantilla_nominas para cada resultado
        foreach ($results as $result) {

			 // obtener SDI
			$SDI = 0;

            // Obtener montos
            $sueldo = 0;
            $pagoDomingo = 0;
            $diaFestivo = 0;
            $diasNoIncluidos = 0;
            $percepcionOtros = 0;
            $sueldo += ($pagoDomingo + $diaFestivo + $diasNoIncluidos + $percepcionOtros);

            $imss = 0;
            $isr = NominaConcept::getMontoEspecial($nomina_id, $result->expediente, 58);
            $primaDominical = 0;
            $subsidio = 0;
            if($subsidio <=0) $subsidio = 0;
            $infonavit = 0;
            $pension = 0;
            $incapacidad = 0;

            $desctoFaltas = 0;           
            $sinGoceSueldo = 0;
            $desctoFaltas += 0;
           
            $primaVacacional = 0;
            $exentoPrimaVacacional = ($primaVacacional > 0) ? $UMA * 15 : 0;			
			if($exentoPrimaVacacional > $primaVacacional) $exentoPrimaVacacional = $primaVacacional;
            
            $aguinaldo = NominaConcept::getMontoEspecial($nomina_id, $result->expediente, 18);
            $exentoAguinaldo = ($aguinaldo > 0) ? $UMA * 30 : 0;
			if($exentoAguinaldo > $aguinaldo) $exentoAguinaldo = $aguinaldo;
            
            $retroactivo = NominaConcept::getMontoEspecial($nomina_id, $result->expediente, 34);
            $premios = NominaConcept::getMontoEspecial($nomina_id, $result->expediente, 16);
            $productividad = NominaConcept::getMontoEspecial($nomina_id, $result->expediente, 102);
            $apoyoLentes = NominaConcept::getMontoEspecial($nomina_id, $result->expediente, 100);
            $apoyoDental = NominaConcept::getMontoEspecial($nomina_id, $result->expediente, 103);
            $separacion = NominaConcept::getMontoEspecial($nomina_id, $result->expediente, 22);

            PlantillaNomina::create([
                'CURP' => $result->curp,
                'DiasPagados' => $nomina->diasPagados,
                'FechaDePago' => $nomina->fechaPago,
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
                'almcnt' => $user->almcnt,
            ]);
        }

        // Export the plantillaEspecial_nominas table to an Excel file.
        return Excel::download(new PlantillaNominasExport, 'PlantillaEspecial_Nomina-'.$nomina_id.'.xlsx');

    } // EndFuntion

} // class
