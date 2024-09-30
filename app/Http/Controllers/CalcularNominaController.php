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

            // pagar solo dias trabajados
            $pagarSoloDiasTrabajados = 'NO';            

            // incapacidad
            $diasIncapacidad = NominaConcept::getIncapacidad($calendar->year, $calendar->almcnt, $employee->expediente, $calendar->semana);
            
            if($diasIncapacidad >= $calendar->diasPagados){
                $this->calcularNominaService->incapacidad($sueldoSemanal, $employee, $calendar);
            }else{

                // infonavit
                $infonavit = Employee::where('almcnt', $calendar->almcnt)->where('expediente', $employee->expediente)->value('infonavit');
                if($infonavit > 0) $this->calcularNominaService->infonavit($infonavit, $employee, $calendar);

                // calcular dias trabajados
                $faltas = NominaConcept::getFaltas($calendar->year, $calendar->almcnt, $employee->expediente, $calendar->semana);
                // $diasTrabajados = $calendar->diasPagados - $faltas;
                $diasTrabajados = $calendar->diasPagados;           

                // calcular ingreso semanal grabado
                $sueldoSemanalGravado = NominaConcept::getSueldoGrabado($calendar->year, $calendar->almcnt, $employee->expediente, $calendar->semana, $calendar->semana, $UMA);
                $sueldoSemanalGravado = $sueldoSemanal + $sueldoSemanalGravado;
                
                // calcular SDI
                $salarioFijo = $salarioDiario + $employee->primaVacacional($employee->vacaciones, $salarioDiario) + $employee->aguinaldoDiario($salarioDiario);
                $parteVariable = $employee->parteVariable($calendar->bimestre);
                
				// pago dia domingo 
				// $monto = NominaConcept::getMonto($calendar->year, $calendar->almcnt, $employee->expediente, 7, $calendar->semana, $calendar->semana);
				$pagoDiaDomingo = $employee->pagoDiaDomingo; 
				// pago prima dominical
				// $monto = NominaConcept::getMonto($calendar->year, $calendar->almcnt, $employee->expediente, 2, $calendar->semana, $calendar->semana);
				$primaDominical = $employee->primaDominical;

				$pagoFijoVelador = $pagoDiaDomingo + $primaDominical;
				
                $SDI = $salarioFijo + $parteVariable + $pagoFijoVelador;
               
                $this->calcularNominaService->calcularNomina($sueldoMensual, $sueldoSemanal, $diasTrabajados, $sueldoSemanalGravado, $SDI, $employee, $calendar, $UMA);
                                
            }
		
        } // foreach

        // marcar filas
        $this->actualizarCalculo(); 

        return redirect()->route('calculo.success');
        
    } // calcularNomina  

    public function cierreNomina()
    {
        $user = Auth::user();         

        $calendar = Calendar::where('almcnt', $user->almcnt)
		->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first();

        $count = NominaConcept::where('year', $calendar->year)
        ->where('almcnt', $calendar->almcnt)
        ->where('semana', $calendar->semana)
        ->count();        

        return view('nomina_concepts.cierre-nomina', compact('calendar','count'));
    }

    // 
    public function closeNomina()
    {
        $user = Auth::user();         

        $calendar = Calendar::where('almcnt', $user->almcnt)
		->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first();

        $ultimaSemanaCalendario = Calendar::getUltimaSemanaCalendario($calendar->year, $calendar->almcnt);
        
        if($calendar->semana >= $ultimaSemanaCalendario) {

            Calendar::where('year', $calendar->year)
            ->where('almcnt', $calendar->almcnt)
            ->where('semana', $calendar->semana)
            ->update([ 'status' => '1' ]);

        } else {
        
            Calendar::where('year', $calendar->year)
            ->where('almcnt', $calendar->almcnt)
            ->where('semana', $calendar->semana)
            ->update([ 'status' => '1', 'puntero' => '0' ]);

            Calendar::where('year', $calendar->year)
            ->where('almcnt', $calendar->almcnt)
            ->where('semana', $calendar->semana + 1)
            ->update([ 'puntero' => '1' ]);

        }

        return redirect()->back()->with('success', 'Cierre de nómina aplicado correctamente.');

    }

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

    public function deleteForm()
    {
        $user = Auth::user(); 

        $calendar = Calendar::where('almcnt', $user->almcnt)
		->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first();

        return view('nomina_concepts.delete-movements', compact('calendar'));
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

    // 
    public function deleteMovements(Request $request)
    {
        $user = Auth::user();

        // Obtener el calendario actual
        $calendar = Calendar::where('almcnt', $user->almcnt)
            ->where('year', $user->currentYear)
            ->where('puntero', 1)
            ->first();
    
        // Asegurarse de que el calendario existe
        if ($calendar) {
            // Eliminar movimientos de nomina_concepts para la semana actual
            NominaConcept::where('year', $calendar->year)
                ->where('almcnt', $calendar->almcnt)
                ->where('semana', $calendar->semana)
                ->delete();
    
            // Actualizar el calendario actual para cambiar el estado y puntero
            Calendar::where('year', $calendar->year)
                ->where('almcnt', $calendar->almcnt)
                ->where('semana', $calendar->semana)
                ->update(['status' => '0', 'puntero' => '0']);
    
            // Actualizar el calendario de la semana anterior para establecer el puntero
            Calendar::where('year', $calendar->year)
                ->where('almcnt', $calendar->almcnt)
                ->where('semana', $calendar->semana - 1)
                ->update(['puntero' => '1', 'status' => '0']);
    
            // Redirigir a la ruta 'impresion.tabla' con un mensaje de éxito
            return redirect()->route('impresion.tabla')->with('success', 'Cierre de nómina aplicado correctamente.');

        }
    
        return redirect()->back()->with('error', 'No se encontró el calendario actual.');

    }    


} // class
