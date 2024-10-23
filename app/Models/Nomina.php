<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Nomina extends Model
{
    use HasFactory;

    // Tabla asociada
    protected $table = 'nominas';

    protected $fillable = [
        'almcnt', 'semana', 'mes', 'year', 'motivo', 'periodicidad', 'tipo_nomina',
        'fechaInicio', 'fechaFin', 'fechaPago', 'diasPagados', 'diasTrabajados', 'concept_id', 'status'
    ];

    // Relación con el modelo Concept
    public function concept()
    {
        return $this->belongsTo(Concept::class);
    }

    /**
     * Accesor para formatear la fecha de inicio.
     */
    public function getFechaInicioFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->fechaInicio)->format('d-m-Y');
    }

    /**
     * Accesor para formatear la fecha de fin.
     */
    public function getFechaFinFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->fechaFin)->format('d-m-Y');
    }

    /**
     * Accesor para formatear la fecha de pago.
     */
    public function getFechaPagoFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->fechaPago)->format('d-m-Y');
    }
}