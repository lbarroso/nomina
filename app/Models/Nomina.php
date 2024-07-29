<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Nomina extends Model
{
    use HasFactory;

    // desactivar timestamps
    public $timestamps = false;

    protected $table = 'nominas';

    // Campos llenables
    protected $fillable = [
        'almcnt', 
        'semana', 
        'mes', 
        'year', 
        'descripcion', 
        'periodicidad', 
        'fechaInicio', 
        'fechaFin', 
        'fechaPago'
    ];

    // Campos de fecha (si se necesita trabajar con fechas como instancias de Carbon)
    protected $dates = [
        'fechaInicio',
        'fechaFin',
        'fechaPago'
    ];    
    
} // class
