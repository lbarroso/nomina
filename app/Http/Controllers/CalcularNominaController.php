<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Calendar;
use App\Models\Salary;
use App\Services\CalcularNominaService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\NominaConcept;


class CalcularNominaController extends Controller
{
    protected $calcularNominaService;

    public function __construct(CalcularNominaService $calcularNominaService)
    {
        $this->middleware('auth');

        $this->calcularNominaService = $calcularNominaService;
    }
    
    public function calcularNomina(Request $request)
    {
        $user = Auth::user(); 

        $tabulador = $request->tabulador ?? "actual";
        $uma = $request->uma ?? "actual";	

        $calendar = Calendar::where('almcnt', $user->almcnt)
		->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first();
 
        // listado empleados
        $empleados = Employee::with('salary')
        ->where('almcnt', $user->almcnt)
        ->where('status', 1)
        ->orderBy('curp')
        ->get();     
        
        if ($empleados->isEmpty()) {
            return response()->json(['message' => 'No employees found'], 404);
        }

        // CALCULAR NOMINA 
        foreach ($empleados as $employee) {
			$UMA =  $uma == "actual" ? $employee->salary->uma_vig : $employee->salary->uma_ant;
            $sueldoMensual =  $tabulador == "actual" ? $employee->salary->tab_vig : $employee->salary->tab_ant; 
			$salarioDiario = $sueldoMensual / 30;
			$sueldoSemanal = $salarioDiario * $calendar->diasPagados; 
            
            // incapacidad
            $diasIncapacidad = NominaConcept::getIncapacidad($calendar->year, $calendar->almcnt, $employee->expediente, $calendar->semana);
            
            if($diasIncapacidad >= $calendar->diasPagados){
                $this->calcularNominaService->incapacidad($sueldoSemanal, $employee, $calendar);                
            }else{

                // calcular dias trabajados
                $faltas = NominaConcept::getFaltas($calendar->year, $calendar->almcnt, $employee->expediente, $calendar->semana);
                $diasTrabajados = $calendar->diasPagados - $faltas;            

                // calcular ingreso semanal grabado
                $sueldoSemanalGravado = NominaConcept::getSueldoGrabado($calendar->year, $calendar->almcnt, $employee->expediente, $calendar->semana, $calendar->semana);                
                $sueldoSemanalGravado = ($salarioDiario * $diasTrabajados) + $sueldoSemanalGravado;
                
                // calcular SDI
                $salarioFijo = $salarioDiario + $employee->primaVacacional($employee->vacaciones, $salarioDiario) + $employee->aguinaldoDiario($salarioDiario);
                $parteVariable = $employee->parteVariable($calendar->bimestre);
                
                if($employee->salary->puesto == "VELADOR"){
                    $pagoDiaDomingo = $employee->pagoDiaDomingo($salarioDiario);
                    $primaDominical = $employee->primaDominical($pagoDiaDomingo);
                    $pagoFijoVelador = $pagoDiaDomingo + $primaDominical;
                }else{
                    $pagoDiaDomingo = 0;
                    $primaDominical = 0;
                    $pagoFijoVelador = 0;									
                }

                $SDI = $salarioFijo + $parteVariable + $pagoFijoVelador;
               
                $this->calcularNominaService->calcularNomina($sueldoMensual, $sueldoSemanal, $diasTrabajados, $sueldoSemanalGravado, $SDI, $employee, $calendar, $UMA);
                
                $this->actualizarCalculo(); // marca las filas
            }
		
        } // foreach

        //return response()->json(['message' => 'Nómina calculada y almacenada con éxito'], 200);

        return redirect()->route('calculo.success');
        
    } // calcularNomina  

    public function calcularFormulas()
    {
        $user = Auth::user();         

        $calendar = Calendar::where('almcnt', $user->almcnt)
		->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first();

        $count = NominaConcept::where('year', $user->currentYear)
        ->where('almcnt', $user->almcnt)
        ->where('semana', $calendar->semana)
        ->where('calculo', 1)
        ->count();
    
        return view('nomina_concepts.calcular-formulas', compact('calendar','count'));
    }    


    public function actualizarCalculo()
    {

        $user = Auth::user(); 

        $calendar = Calendar::where('almcnt', $user->almcnt)
		->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first();        

        NominaConcept::where('year', $calendar->year)
            ->where('almcnt', $calendar->almcnt)
            ->where('semana', $calendar->semana)
            ->update([ 'calculo' => '1' ]);

        return 1;
    }    

    public function calculoSuccess()
    {
        return view('nomina_concepts.calculo-success');
    }


    public function resetForm()
    {
        $user = Auth::user(); 

        $calendar = Calendar::where('almcnt', $user->almcnt)
		->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first();

        return view('nomina_concepts.reset-movements', compact('calendar'));
    }

    public function resetMovements(Request $request)
    {

        $user = Auth::user();

        $calendar = Calendar::where('almcnt', $user->almcnt)
		->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first();        

        NominaConcept::where('year',  $calendar->year)
            ->where('almcnt', $calendar->almcnt)
            ->where('semana', $calendar->semana)
            ->delete();

        return redirect()->back()->with('success', 'Movimientos reseteados correctamente.');
    }    

} // class
