<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Salary;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Calendar;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return 'index';
    }

    public function integracionSalaries()
    {

        // año bisiesto
        $diasDelAnioActual = Carbon::now()->daysInYear();

        // bimestre de 61
        $calendar = Calendar::where('almcnt', Auth::user()->almcnt)
        ->where('puntero', 1)
        ->first();    
        
        $employees = Employee::with('salary')->where('almcnt',Auth::user()->almcnt)->where('status',1)->orderBy('curp')->get();
        
        return view('employees.integracion_salaries', compact('employees','calendar','diasDelAnioActual'));
    }        


    public function editiVariablesSdi(Request $request)
    {
        // año bisiesto
        $diasDelAnioActual = Carbon::now()->daysInYear();

        $employee = Employee::findOrFail($request->id);

        // bimestre de 61
        $calendar = Calendar::where('almcnt', Auth::user()->almcnt)
        ->where('puntero', 1)
        ->first();                

        return view('employees.variables_sdi', compact('employee', 'calendar', 'diasDelAnioActual'));
    }

    public function updateVariablesSdi(Request $request)
    {
        $request->validate([
            'valeMensual' => 'numeric|nullable',
            'valeNavideno' => 'numeric|nullable',
            'variableOtro' => 'numeric|nullable',
            'bimestre' => 'required|numeric|between:58,62',
            'id' => 'required|exists:employees,id',     
        ]); 

        try {
            $employee = Employee::findOrFail($request->id);
            $employee->update($request->only('valeMensual', 'valeNavideno', 'variableOtro'));
    
            Calendar::where('mes', $request->mes)
            ->where('almcnt', Auth::user()->almcnt)
            ->update(['bimestre' => $request->bimestre]);            
    
            return redirect()->route('home')->with('success', 'Datos actualizados correctamente.');

        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Error al actualizar los datos: ' . $e->getMessage());
        }        
    }    



} // class
