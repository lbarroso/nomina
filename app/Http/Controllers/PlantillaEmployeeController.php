<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlantillaEmployee;
use App\Models\Calendar;
use App\Models\NominaConcept;
use App\Models\Employee;
use Excel;
use App\Exports\PlantillaEmployeeExport;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;

class PlantillaEmployeeController extends Controller
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
        
        $UMA =  env('UMA'); // Accede a la variable UMA desde .ENV

        $calendar = Calendar::where('almcnt', $user->almcnt)
        ->where('year', $user->currentYear)
        ->where('puntero', 1)
        ->first(); 
        
        $year = !empty($request->year) ? $request->year : $calendar->year;
        
        $semana = !empty($request->semana) ? $request->semana : $calendar->semana;
        
        // Vaciar la tabla plantilla_nominas para el almcnt actual
        PlantillaEmployee::where('almcnt', $user->almcnt)->delete();

        // listar empleados
        $results = NominaConcept::select('employees.curp', 'nomina_concepts.expediente')
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

        // Crear un nuevo registro en la tabla plantilla_nominas para cada resultado
        foreach ($results as $result) {

            $employee = Employee::where('almcnt', $user->almcnt)
            ->where('expediente', $result->expediente)
            ->first();         
            
            // obtener SDI
			$SDI = NominaConcept::getMonto($year, $calendar->almcnt, $employee->expediente, 3, $semana, $semana);

            if($employee->estado =='Nuevo León') $estado = 'NL';
            else if($employee->estado == 'Chihuahua') $estado = 'CHI';
            else if($employee->estado == 'Coahuila') $estado = 'COA';
            else $estado = 'OAX';
			
            // insertar datos
            PlantillaEmployee::create([
                'RFC' => $employee->rfc,
                'CURP' => $employee->curp,                
                'NoEmpleado' => $employee->expediente,
                'Nombres' => strtoupper($employee->nombre),
                'ApellidoPaterno' => strtoupper($employee->paterno),
                'ApellidoMaterno' => strtoupper($employee->materno),
                'PeriodicidadPago' => 'Semanal',
                'Pais' => 'México',
                'Email' => !empty($user->email) ? $user->email : '',
                'TipoRegimen' => '2',
                'esAsimilado' => '',
                'NSS' => $employee->nss,
                'Puesto' => strtoupper($employee->salary->puesto),
                'FechaInicioRelLab' => Carbon::parse($employee->fechaIngreso)->format('d/m/Y'),
                'TipoContrato' => 'DEFINITIVO',
                'TipoJornada' => 'COMPLETA 8 HORAS',
                'SDI' => $SDI,
                'Departamento' => '',
                'Estado' => $estado,
                'CodigoPostal' => $employee->codigoPostal,
                'Calle' => !empty($employee->calle) ? $employee->calle : '',
                'NoExterior' => !empty($employee->calleNoInt) ? $employee->calleNoInt : '',
                'NoInterior' => !empty($employee->calleNoExt) ? $employee->calleNoExt : '',
                'Localidad'  => !empty($employee->localidad) ? $employee->localidad : '',
                'Colonia' => '',
                'Municipio' => !empty($employee->municipio) ? $employee->municipio : '',
                'Telefono' => !empty($employee->telefono) ? $employee->telefono : '',
                'Clabe' => '',
                'Banco' => '',
                'Observaciones' => '',
                'Categoria' => 'SUELDOS Y SALARIOS',
                'ZonaSalario' => 'B',                
                'SueldoDiario' => $employee->salary->tab_vig / 30,
                'TipoIngreso' => '',
                'PorcIngPropios' => '',
                'Sindicalizado' => 'No',
                'CuentaBancaria' =>'',
                'almcnt' => $calendar->almcnt,
            ]);
        }

        // Redirigir o retornar una respuesta adecuada
        return redirect()->back()->with('employee', $semana);
    }  


    /**
     * Export the plantilla_nominas table to an Excel file.
     */
    public function excel(Request $request)
    {

        $semana = $request->semana ? $request->semana : 0;

        return Excel::download(new PlantillaEmployeeExport, 'Plantilla_Empleados-'.$semana.'.xlsx');
    }    

    
} // class
