<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Concept;
use App\Models\Calendar;
use App\Models\NominaConcept;
use Illuminate\Support\Facades\Auth;

class NominaConceptController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $user = Auth::user(); 
        $employees = Employee::with('salary')->where('almcnt',Auth::user()->almcnt)->where('status',1)->orderBy('curp')->get();
        $concepts = Concept::where('formula', 1)->orderBy('descripcion')->get();

        $calendar = Calendar::where('almcnt', $user->almcnt)
		->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first();

        $movements = NominaConcept::join('employees', 'nomina_concepts.expediente', '=', 'employees.expediente')
        ->join('concepts', 'nomina_concepts.concept_id', '=', 'concepts.id')
        ->where('nomina_concepts.year', $calendar->year)
        ->where('nomina_concepts.almcnt', $calendar->almcnt)
        ->where('nomina_concepts.semana', $calendar->semana)
        ->where('nomina_concepts.calculo', 0)
        ->orderBy('employees.curp')
        ->select('nomina_concepts.id','employees.nombre', 'employees.paterno', 'employees.materno', 'concepts.descripcion', 'nomina_concepts.monto')
        ->get();  

        $count = NominaConcept::where('year', $user->currentYear)
        ->where('almcnt', $user->almcnt)
        ->where('semana', $calendar->semana)
        ->where('calculo', 1)
        ->count();        

        return view('nomina_concepts.create', compact('employees', 'concepts','calendar','movements', 'count'));
    }

    public function store(Request $request)
    {

        $user = Auth::user(); 

        $calendar = Calendar::where('almcnt', $user->almcnt)
		->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first();     

        $employee_id =  $request->input('employee_id');
        $concept_id =  $request->input('concept_id');
        $monto = $request->input('monto') ? $request->input('monto') : 0;
        $diasPagados = $request->input('diasPagados') ? $request->input('diasPagados') : 0;
        $diasTrabajados = 0;
        $employee = Employee::find($employee_id);
        $concept = Concept::find($concept_id);
        
        // faltas
        if($concept_id == 52 || $concept_id == 53 || $concept_id == 54){          
            $salarioDiario = $employee->salarioDiario($employee->salary->tab_vig);
            $monto = $diasPagados * $salarioDiario;
        }

        // pago dia domingo
        if($concept_id == 7) {
            $salarioDiario = $employee->salarioDiario($employee->salary->tab_vig);
            $monto = $pagoDiaDomingo = $salarioDiario * 2;
            $primaDominical = $salarioDiario * 0.25;
            NominaConcept::create([
                'year' => $calendar->year,
                'almcnt' => $calendar->almcnt,
                'fecha' => now()->format('Y-m-d'),
                'semana' => $calendar->semana,
                'diasPagados' => 0,
                'salary_id' => $employee->salary_id,
                'expediente' => $employee->expediente,
                'concept_id' => 2, 
                'monto' => $primaDominical,
                'tipo' => 'percepcion',
                'diasTrabajados' => 0,
            ]);             
        }

        // agrgar movimiento
        NominaConcept::create([
            'year' => $calendar->year,
            'almcnt' => $calendar->almcnt,
            'fecha' => now()->format('Y-m-d'),
            'semana' => $calendar->semana,
            'diasPagados' => $diasPagados,
            'salary_id' => $employee->salary_id,
            'expediente' => $employee->expediente,
            'concept_id' => $concept_id,
            'monto' => $monto,
            'tipo' => $concept->tipo,
            'diasTrabajados' => $diasTrabajados,
        ]); 

        return redirect()->back()->with('success', 'Movimiento agregado correctamente.');
    }

    public function destroy($id)
    {
        NominaConcept::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Movimiento eliminado correctamente.');
    }



} // class
