<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantillaNomina extends Model
{
    use HasFactory;

    // desactivar timestamps
    public $timestamps = false;

    // Campos llenables
    protected $fillable = [
        'CURP',
        'DiasPagados',
        'FechaDePago',
        'ConceptoSueldo',
        'ConceptoIMSS',
        'SDI_Indemnizacion',
        'ConceptoISR',
        'Concepto_P_Dominical',
        'Concepto_Sub_Emp',
        'Subsidio_tabla',
        'ConceptoInfonavit',
        'Concepto_Pension_Alim',
        'Concepto_Desc_Faltas',
        'Concepto_Desc_Inc',
        'Concepto_P_Vacacional',
        'Exento_P_Vacacional',
        'ConceptoRetroactivo',
        'Concepto_Aguinaldo',
        'Exento_Aguinaldo',
        'Concepto_Premios',
        'Concepto_Productividad',
        'Concepto_ayuda_lentes',
        'Exento_ayuda_lentes',
        'Concepto_apoyo_dental',
        'Exento_apoyo_dental',
        'Concepto_separacion',
        'Exento_separacion',        
        'almcnt'
    ];    
        

} // class
