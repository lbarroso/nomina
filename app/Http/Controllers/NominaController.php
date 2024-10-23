<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Nomina;
use App\Models\Concept;
use App\Models\Employee;
use App\Models\Calendar;
use App\Models\NominaConcept;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Services\CalcularNominaService;

class NominaController extends Controller
{
    protected $calcularNominaService;

    public function __construct(CalcularNominaService $calcularNominaService)
    {
        $this->middleware('auth');

        $this->calcularNominaService = $calcularNominaService;

    }

      /**
     * Mostrar la lista de nóminas (regulares, extraordinarias, extemporáneas).
     */
    public function index(Request $request)
    {
        $user = Auth::user(); 

        // Crear la consulta base, filtrando por almacén
        $query = Nomina::where('almcnt', $user->almcnt); // Filtrar por el almacén del usuario        
    
        // Filtrar por Fecha de Inicio
        if ($request->filled('fechaInicio')) {
            $query->where('fechaInicio', '>=', $request->fechaInicio);
        }
    
        // Filtrar por Fecha de Fin
        if ($request->filled('fechaFin')) {
            $query->where('fechaFin', '<=', $request->fechaFin);
        }
    
        // Filtrar por Tipo de Nómina
        if ($request->filled('tipo_nomina')) {
            $query->where('tipo_nomina', $request->tipo_nomina);
        }
    
        // Ordenar por fecha de creación y paginar los resultados
        $nominas = $query->orderBy('created_at', 'desc')->paginate(10);

        // Obtener los conceptos visibles y que estén dentro del rango de ID [1, 2, 3, 4]
        $concepts = Concept::where('visible', 1) // Solo conceptos visibles
            ->whereIn('id', [16, 34, 43, 102]) // Solo IDs en el rango especificado
            ->get();
                
        // Retornar la vista con las nóminas filtradas y paginadas
        return view('nominas.index', compact('nominas','concepts'));
    }
    

    /**
     * Mostrar formulario para crear una nueva nómina.
     */
    public function create()
    {
        // Obtener todos los conceptos de la tabla 'concepts'
        $concepts = Concept::all();
        
         // Pasar los conceptos a la vista de creación de nómina
        return view('nominas.create');
    }

    /**
     * Almacenar una nueva nómina en la base de datos.
     */
    public function store(Request $request)
    {
        // Obtener el almacén (almcnt) del usuario autenticado
        $almcnt = auth()->user()->almcnt;

        date_default_timezone_set('America/Mexico_City');

        // Validar los datos ingresados
        $validated = $request->validate([
            'motivo' => [
                'required',
                'string',
                'max:255',
                // Validar que el motivo sea único por almacén (almcnt)
                \Illuminate\Validation\Rule::unique('nominas')->where(function ($query) use ($almcnt) {
                    return $query->where('almcnt', $almcnt);
                })
            ],
            'periodicidad' => 'required|in:semanal,especial',
            'tipo_nomina' => 'required|in:regular,extraordinaria,extemporanea',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
            'diasPagados' => 'required|integer|min:1',
            'concept_id' => 'required|exists:concepts,id',
            'monto' => 'nullable|numeric|gt:0', // El monto solo es requerido si concept_id es 43
            // Validación para evitar duplicados basados en 'year', 'almcnt', y 'semana'
            'year' => 'unique:nominas,year,NULL,id,almcnt,' . $almcnt . ',semana,' . $request->semana
        ]);

        // Obtener el año desde la fecha de inicio
        $year = Carbon::parse($validated['fechaInicio'])->year;

        // Obtener el almacén (almcnt) - Ejemplo: obtenerlo del usuario autenticado
        $almcnt = auth()->user()->almcnt;

        // Obtener el mes de la fecha de inicio
        $mesCalendario = Carbon::parse($validated['fechaInicio'])->month;

        // Crear una nueva nómina con los datos validados más los calculados
        $nomina = Nomina::create([
            'year' => $year,
            'almcnt' => $almcnt,
            'fechaInicio' => Carbon::parse($validated['fechaInicio']),
            'fechaFin' => Carbon::parse($validated['fechaFin']),
            'fechaPago' => Carbon::now(),
            'semana' => 0, // Semana obtenida del modelo Calendar
            'mes' => $mesCalendario,
            'diasPagados' => $validated['diasPagados'],
            'motivo' => $validated['motivo'],
            'periodicidad' => $validated['periodicidad'],
            'tipo_nomina' => $validated['tipo_nomina'],
            'concept_id' => $validated['concept_id']
        ]);

        $nominaId = $nomina->id; // Obtener el ID de la nómina recién insertada

        // Obtener todos los empleados activos (status = 1)
        $empleadosActivos = Employee::where('status', 1)->where('almcnt', $almcnt)->get();

        // Insertar un registro en nomina_concepts para cada empleado activo
        foreach ($empleadosActivos as $empleado) {
            NominaConcept::create([
                'year' => $year,
                'almcnt' => $almcnt,
                'fecha' => Carbon::now(), // O usar la fecha de nómina si es necesario
                'semana' => 0,
                'salary_id' => $empleado->salary_id, // Asignar el salario del empleado
                'expediente' => $empleado->expediente, // Usar el expediente del empleado
                'concept_id' => $validated['concept_id'], // Concepto capturado en la modal
                'monto' => 0, // El monto puede ser ajustado más adelante
                'tipo' => 'percepcion', // Este valor puede ajustarse según el tipo de concepto
                'diasPagados' => $validated['diasPagados'], // Usamos los días pagados capturados
                'calculo' => 0, // Este campo será calculado en el futuro
                'tipo_nomina' => $validated['tipo_nomina'],
                'nomina_id' => $nominaId,
            ]);
        }
        
        // Redirigir a la ruta nominas.empleados con el ID de la nómina recién creada
        return  redirect()->route('nominas.empleados', $nomina->id);
                
    }

    /**
     * Mostrar los detalles de una nómina específica.
     */
    public function show($id)
    {
        $nomina = Nomina::findOrFail($id);

        return view('nominas.show', compact('nomina'));
    }

    /**
     * Mostrar formulario para editar una nómina.
     */
    public function edit($id)
    {
        $nomina = Nomina::findOrFail($id);

        return view('nominas.edit', compact('nomina'));
    }

    /**
     * Actualizar una nómina en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $nomina = Nomina::findOrFail($id);

        // Validar datos actualizados
        $validated = $request->validate([
            'codigo' => 'required|unique:nominas,codigo,' . $nomina->id,
            'periodicidad' => 'required|in:regular,extraordinaria,extemporanea',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
            'diasPagados' => 'required|integer|min:1'
        ]);

        // Actualizar la nómina con los datos validados
        $nomina->update([
            'codigo' => $validated['codigo'],
            'periodicidad' => $validated['periodicidad'],
            'fechaInicio' => Carbon::parse($validated['fechaInicio']),
            'fechaFin' => Carbon::parse($validated['fechaFin']),
            'diasPagados' => $validated['diasPagados'],
            'motivo' => $request->motivo ?? null
        ]);

        // Redirigir con mensaje de éxito
        return redirect()->route('nominas.index')->with('success', 'Nómina actualizada exitosamente.');
    }

    /**
     * Eliminar una nómina de la base de datos.
     */
    public function destroy($id)
    {
        $nomina = Nomina::findOrFail($id);

        // Eliminar la nómina
        $nomina->delete();

        // Redirigir a la lista con mensaje de éxito
        return redirect()->route('nominas.index')->with('success', 'Nómina eliminada correctamente.');
    }

    /**
     * Mostrar los empleados agregados a la última nómina.
     *
     * @return \Illuminate\View\View
     */
    public function nominasEmpleados($id)
    {
        date_default_timezone_set('America/Mexico_City');

        // Obtener el almacén (almcnt) del usuario autenticado
        $almcnt = auth()->user()->almcnt;

        // Obtener la nómina específica donde coincida el ID
        $nomina = Nomina::where('id', $id)->first();

        // Si no se encuentra la nómina, redirigir con un mensaje de error
        if (!$nomina) {
            return redirect()->route('nominas.index')->with('error', 'No se encontró ninguna nómina para los parámetros dados.');
        }

        // conceptos percepciones y deducciones        
        $nominaconcepts = NominaConcept::select(
            'nomina_concepts.expediente', 
            'employees.nombre', 
            'employees.paterno', 
            'employees.materno', 
            'employees.curp',
            'nomina_concepts.id',
            'nomina_concepts.calculo', 
            \DB::raw("SUM(CASE WHEN nomina_concepts.tipo = 'percepcion' THEN nomina_concepts.monto ELSE 0 END) as percepciones"),
            \DB::raw("SUM(CASE WHEN nomina_concepts.tipo = 'deduccion' THEN nomina_concepts.monto ELSE 0 END) as deducciones")
        )
        ->join('employees', function ($join) use ($almcnt) {
            $join->on('nomina_concepts.expediente', '=', 'employees.expediente')
                 ->where('employees.almcnt', '=', $almcnt); // Filtramos por almacén ($almcnt)
        })
        ->where('nomina_concepts.nomina_id', $id) // Filtramos por nomina_id
        ->groupBy('nomina_concepts.expediente') // Agrupamos por expediente y los datos del empleado
        ->orderBy('employees.curp') // Ordenamos por curp
        ->get();
        
        // Consulta para obtener empleados en la modal agregar empleados
        $employees = Employee::where('almcnt', $almcnt)->orderBy('curp')->get();   

        // Pasar la nómina y los empleados a la vista
        return view('nominas.empleados_nomina', [
            'nomina' => $nomina,
            'nominaconcepts' => $nominaconcepts,
            'employees' => $employees
        ]);        
       
    } //EndMethod

    public function agregarEmpleado(Request $request, $id)
    {
        // Validar la solicitud
        $request->validate([
            'empleado_id' => 'required|exists:employees,id',
            'monto' => 'required|numeric|min:0',
        ]);
    
        // Obtener la nómina
        $nomina = Nomina::findOrFail($id);

        // Verificar si la nómina está cerrada
        if ($nomina->status == 'cerrada') {
            return redirect()->route('nominas.empleados', $nomina->id)
                ->with('error', 'No puedes agregar empleados a una nómina cerrada.');
        }        
    
        // Obtener el empleado
        $empleado = Employee::findOrFail($request->empleado_id);

        // Verificar si el empleado ya está en la nómina (es decir, si ya existe en nomina_concepts)
        $exists = NominaConcept::where('nomina_id', $nomina->id)
                ->where('expediente', $empleado->expediente)
                ->exists();

        // Si el empleado ya está en la nómina, devolver un error
        if ($exists) {
            return redirect()->route('nominas.empleados', $nomina->id)
                ->with('error', 'El empleado ya ha sido agregado a esta nómina.');
        }

        // Crear un nuevo registro en la tabla nomina_concepts
        NominaConcept::create([
            'nomina_id' => $nomina->id,
            'expediente' => $empleado->expediente,
            'concept_id' => $nomina->concept_id, // Asignar el concepto de la nómina
            'monto' => $request->monto,
            'tipo' => 'percepcion', // Esto puede ser dinámico dependiendo del concepto
            'calculo' => 0, // Inicialmente sin calcular
            'diasPagados' => $nomina->diasPagados, // Usar los días pagados de la nómina
            'year' => $nomina->year,
            'almcnt' => $nomina->almcnt,
            'semana' => 0,
            'tipo_nomina' => 'extraordinaria',
            'salary_id' => $empleado->salary_id,
            'fecha' => $nomina->fechaPago,
        ]);
    
        // Redirigir a la vista de empleados con un mensaje de éxito
        return redirect()->route('nominas.empleados', $nomina->id)->with('success', 'Empleado agregado correctamente.');
    } 

    public function editarEmpleado(Request $request)
    {
        // Validar los datos
        $request->validate([
            'id' => 'required|exists:nomina_concepts,id',
            'monto' => 'required|numeric|min:0',
        ]);
    
        // Obtener el registro de nomina_concepts
        $concept = NominaConcept::findOrFail($request->id);
    
        // Actualizar el monto
        $concept->monto = $request->monto;
        $concept->save();
    
        // Redirigir de nuevo a la vista de empleados con un mensaje de éxito
        return redirect()->route('nominas.empleados', $concept->nomina_id)
            ->with('success', 'Monto del empleado actualizado correctamente.');
    }
    

    public function aplicarCalculo($nomina_id)
    {
        date_default_timezone_set('America/Mexico_City');

        // Obtener la nómina
        $nomina = Nomina::findOrFail($nomina_id);
                
        // Verificar si la nómina está cerrada
        if ($nomina->status == 'cerrada') {
            return redirect()->route('nominas.empleados', $nomina->id)
                ->with('error', 'No puedes aplicar cálculo a una nómina cerrada.');
        }
                
        // Borrar todos los registros de deducción que coincidan con nomina_id y tengan calculo = 0
        NominaConcept::where('nomina_id', $nomina_id)
                     ->where('tipo', 'deduccion')
                     ->where('calculo', 0)
                     ->delete();
    
        // Obtener los registros de nomina_concepts relacionados con la nómina
        $nominaConcepts = NominaConcept::where('nomina_id', $nomina_id)->where('tipo','percepcion')->get();
    
        // Validar que la nómina exista
        if (!$nominaConcepts) {
            return redirect()->back()->with('error', 'Nómina no encontrada.');
        }
                
        // Iterar sobre cada concepto de nómina
        foreach ($nominaConcepts as $concept) {
            // Obtener el concepto específico desde la tabla 'concepts'
            $conceptInfo = Concept::find($concept->concept_id);
    
            // Verificar si el concepto genera impuesto (impuesto == 'SI')
            if ($conceptInfo && $conceptInfo->impuesto == 'SI' && $concept->monto > 0) {
                // Reutilizar el servicio para calcular el ISR según el sueldo gravado
                $isrCalculado = $this->calcularNominaService->calcularNominaEspecial($concept->monto);
          
                // Crear un nuevo registro en nomina_concepts para guardar el ISR calculado
                NominaConcept::create([
                    'year' => $concept->year,
                    'almcnt' => $concept->almcnt,
                    'fecha' => Carbon::now(), // O la fecha correspondiente
                    'semana' => $concept->semana,
                    'salary_id' => $concept->salary_id,
                    'expediente' => $concept->expediente,
                    'concept_id' => 58, // ID del concepto para ISR, asegúrate de tenerlo en la tabla concepts
                    'monto' => $isrCalculado,
                    'tipo' => 'deduccion', // Marca como deducción
                    'diasPagados' => $concept->diasPagados,
                    'calculo' => 0, // Marca como calculado
                    'nomina_id' => $nomina_id,
                    'tipo_nomina' => 'extraordinaria',                    
                ]);
            }

        } // foreach
    
        // Redirigir de nuevo a la vista de empleados con un mensaje de éxito
        return redirect()->route('nominas.empleados', $nomina_id)->with('success', 'Cálculo de ISR aplicado correctamente.');
    }
    

    public function cerrarNomina($id)
    {
        // Obtener la nómina por su ID
        $nomina = Nomina::findOrFail($id);
    
        // Verificar si la nómina ya está cerrada
        if ($nomina->status == 'cerrada') {
            return redirect()->route('nominas.index')->with('error', 'La nómina ya está cerrada.');
        }
    
        // Actualizar el estado de la nómina a "cerrada"
        $nomina->status = 'cerrada';
        $nomina->save();

        // Actualizar el campo 'calculo' a 1 en los registros relacionados de la tabla nomina_concepts
        NominaConcept::where('nomina_id', $id)->update(['calculo' => 1]);
    
        // Redirigir con un mensaje de éxito
        return redirect()->route('nominas.index')->with('success', 'Nómina cerrada correctamente.');
    }
    

    public function destroyEmpleado($id)
    {
        // Buscar el registro de nomina_concepts por su ID
        $concept = NominaConcept::findOrFail($id);
    
        // Eliminar el registro
        $concept->delete();
    
        // Redirigir de nuevo a la vista con un mensaje de éxito
        return redirect()->back()->with('success', 'Empleado eliminado correctamente de la nómina.');
    }
    
  
    

} // class
