<?php

namespace App\Exports;

use App\Models\PlantillaEmployee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;

class PlantillaEmployeeExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = Auth::user();

        return PlantillaEmployee::where('almcnt', $user->almcnt)->get();
    }

 /**
     * Define the headings for the Excel sheet
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'RFC',
            'CURP',
            'NoEmpleado',
            'Nombres',
            'ApellidoPaterno',
            'ApellidoMaterno',
            'PeriodicidadPago',
            'Pais',
            'Email',
            'TipoRegimen',
            'esAsimilado',
            'NSS',
            'Puesto',
            'FechaInicioRelLab',
            'TipoContrato',
            'TipoJornada',
            'SDI',
            'Departamento',
            'Estado',
            'CodigoPostal',
            'Calle',
            'NoExterior',
            'NoInterior',
            'Localidad',
            'Colonia',
            'Municipio',
            'Telefono',
            'Clabe',
            'Banco',
            'Observaciones',
            'Categoria',
            'ZonaSalario',            
            'SueldoDiario',
            'TipoIngreso',
            'PorcIngPropios',
            'sindicalizado',
            'cuenta bancaria',
            'almcnt'
        ];

    }

} // class 
