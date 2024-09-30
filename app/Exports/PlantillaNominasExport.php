<?php

namespace App\Exports;

use App\Models\PlantillaNomina;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;

class PlantillaNominasExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = Auth::user();

        return PlantillaNomina::where('almcnt', $user->almcnt)->get();
        
    }

 /**
     * Define the headings for the Excel sheet
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'CURP',
            'Dias Pagados',
            'Fecha de Pago',
            'Concepto Sueldo',
            'Concepto IMSS',
            'SDI (Indemnizacion)',
            'Concepto ISR',
            'Concepto P Dominical',
            'Concepto Sub Emp',
            'Subsidio tabla',
            'Concepto Infonavit',
            'Concepto Pension Alim',
            'Concepto Desc Faltas',
            'Concepto Desc Inc',
            'Concepto P Vacacional',
            'Exento P Vacacional',
            'Concepto Retroactivo',
            'Concepto Aguinaldo',
            'Exento Aguinaldo',
            'Concepto Premios',
            'Concepto Productividad',
            'Concepto ayuda lentes',
            'Exento ayuda lentes',
            'Concepto apoyo dental',
            'Exento apoyo dental',
            'Concepto separacion',
            'Exento separacion',            
            'almcnt'
        ];

    }

} // class 
