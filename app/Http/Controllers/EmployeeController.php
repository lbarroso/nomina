<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Salary;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        if($request->ajax()){
            return response()->json(['data' => 
                Employee::with('salary')->where('almcnt',Auth::user()->almcnt)->orderBy('curp')->get()
            ]);
        }

        return view('employees.index'); 

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $salaries = Salary::all();
        return view('employees.create', compact('salaries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'paterno' => 'required|string|max:255',
            'materno' => 'required|string|max:255',
            'rfc' => 'required|string|size:13|unique:employees|regex:/^[A-ZÑ&]{4}\d{6}[A-Z0-9]{3}$/',
            'curp' => 'required|string|max:18',
            'nss' => 'required|string|max:11',
            'fechaIngreso' => 'required|date',
            'salary_id' => 'required',
            'fechaNacimiento' => 'required|date',
            'codigoPostal' => 'required|string|max:5',
        ]);

        try {

            $expediente = $this->generateEmployeeNumber();

            Employee::create([
                'expediente' => $expediente,
                'almcnt' => Auth::user()->almcnt,
                'nombre' => $request->nombre,
                'paterno' => $request->paterno,
                'materno' => $request->materno,
                'rfc' => $request->rfc,
                'curp' => $request->curp,
                'nss' => $request->nss,
                'fechaIngreso' => $request->fechaIngreso,
                'salary_id' => $request->salary_id,
                'fechaNacimiento' => $request->fechaNacimiento,
                'codigoPostal' => $request->codigoPostal,
            ]);        
            
            return redirect()->route('employees.index')->with('success', 'Empleado agregado correctamente.');            

        } catch (QueryException $e) {
            // Manejo del error de duplicidad
            return redirect()->route('employee.create')->withErrors(['error' => 'Ya existe un empleado con el mismo RFC o número de empleado.']);
        } 

    }


    private function generateEmployeeNumber()
    {
        do {
            $numeroEmpleado = random_int(10000, 99999);
        } while (Employee::where('expediente', $numeroEmpleado)->exists());

        return $numeroEmpleado;
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return response()->json($employee);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $data = $request->all();

        $employee->update($data);

        return response()->json(['success'=>'Datos actualizados']);
    }



    public function salaries(){
        return response()->json(Salary::all());
    }

    public function showTerminateForm(Request $request)
    {
        $employee = Employee::find($request->id);
        return view('employees.terminate', compact('employee'));
    }    

    public function terminate(Request $request)
    {
        date_default_timezone_set('America/Mexico_City');

        $request->validate([
            'fechaTermino' => 'required|date'
        ]);

        // actualizar
        Employee::where('id', $request->input('id'))        
        ->update(['fechaTermino' => $request->input('fechaTermino'), 'status'=> 0 ]);

        return redirect()->route('employees.index')->with('success', 'Empleado dado de baja correctamente.');
    }

    public function activate(Request $request)
    {

        // actualizar
        Employee::where('id', $request->id)        
        ->update(['status'=> 1 ]);

        return redirect()->route('employees.index')->with('success', 'Empleado dado de baja correctamente.');
    }    

} // class
