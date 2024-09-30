<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantillaEmployee extends Model
{
    use HasFactory;

    // desactivar timestamps
    public $timestamps = false;   

    // Los campos que se pueden asignar de manera masiva
    protected $fillable = [
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
        'Sindicalizado',
        'CuentaBancaria', 
        'almcnt'
    ];    
    
    
} // class
