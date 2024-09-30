<?php

namespace App\Exports;

use App\Models\NominaConcept;
use App\Models\Employee;
use App\Models\Concept;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PercepcionesDeduccionesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $year;
    protected $almcnt;
    protected $semana;

    public function __construct($year, $almcnt, $semana)
    {
        $this->year = $year;
        $this->almcnt = $almcnt;
        $this->semana = $semana;
    }

    public function collection()
    {
        // Obtener todos los empleados
        $employees = Employee::where('almcnt', $this->almcnt)
            ->with(['nominaConcepts' => function ($query) {
                $query->where('year', $this->year)
                    ->where('semana', $this->semana);
            }])
            ->get();

        return $employees;
    }

    public function headings(): array
    {
        // Obtener todos los conceptos
        $concepts = Concept::orderBy('id')->pluck('descripcion')->toArray();

        // Encabezados de las columnas
        return array_merge(['Nombre Completo', 'CURP'], $concepts);
    }

    public function map($employee): array
    {
        // Mapear cada empleado a su fila en el Excel
        $row = [
            $employee->nombre . ' ' . $employee->paterno . ' ' . $employee->materno,
            $employee->curp,
        ];

        // Obtener todos los conceptos
        $concepts = Concept::orderBy('id')->pluck('id')->toArray();

        // Inicializar un array para los montos por concepto
        $conceptAmounts = array_fill(0, count($concepts), 0);

        foreach ($employee->nominaConcepts as $nominaConcept) {
            $index = array_search($nominaConcept->concept_id, $concepts);
            if ($index !== false) {
                $conceptAmounts[$index] = $nominaConcept->monto;
            }
        }

        // Agregar los montos de los conceptos a la fila
        return array_merge($row, $conceptAmounts);
    }
}