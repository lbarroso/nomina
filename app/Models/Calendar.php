<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Calendar extends Model
{
    use HasFactory;
    
    // desactivar timestamps
    public $timestamps = false;

    // Campos llenables
    protected $fillable = [
        'almcnt',
        'semana',
        'mes',
        'year',
        'fechaInicio',
        'fechaFin',
        'diasPagados',
        'puntero',
        'bimestre'
    ];

    // Campos de fecha (si se necesita trabajar con fechas como instancias de Carbon)
    protected $dates = [
        'fechaInicio',
        'fechaFin'
    ];

   // Método accesor para obtener el nombre del mes
   public function getNombreMesAttribute()
   {
       $meses = [
           1 => 'Enero',
           2 => 'Febrero',
           3 => 'Marzo',
           4 => 'Abril',
           5 => 'Mayo',
           6 => 'Junio',
           7 => 'Julio',
           8 => 'Agosto',
           9 => 'Septiembre',
           10 => 'Octubre',
           11 => 'Noviembre',
           12 => 'Diciembre'
       ];

       return $meses[$this->mes] ?? 'Especial';
   }    
    
    // Método accesor para formatear fechaInicio
    public function getFechaInicioFormattedAttribute()
    {
        return Carbon::parse($this->fechaInicio)->format('d-m-Y');
    }

    // Método accesor para formatear 
    public function getFechaFinFormattedAttribute()
    {
        return Carbon::parse($this->fechaFin)->format('d-m-Y');
    }    

    public function scopeGetSemanaCalendarioInicio($query, $mesCalendario, $almcnt)
    {

		$row = $query->where('almcnt', $almcnt)
		->where('mes', $mesCalendario)
		->orderBy('semana', 'ASC')
		->first();   
		
		return $row->semana;
    }

    public function scopeGetSemanaCalendarioFinal($query, $mesCalendario, $almcnt)
    {
        $row = $query->where('almcnt', $almcnt)
        ->where('mes', $mesCalendario)
        ->orderBy('semana', 'DESC')
        ->first();   
        
        return $row->semana;
    }    
	
	// ultima semana 
    public function scopeGetUltimaSemanaCalendario($query, $year, $almcnt)
    {
        $row = $query->where('year', $year)
		->where('almcnt', $almcnt)
        ->orderBy('semana', 'DESC')
        ->first();   
        
        return $row->semana;
    }  	
	
	// fecha de pago plantilla excel
    public function scopeGetFechaPago($query, $year, $almcnt, $semana)
    {
        $row = $query->where('year', $year)
		->where('almcnt', $almcnt)
		->where('semana', $semana)
        ->first();   
        
        return Carbon::parse($row->fechaFin)->format('d/m/Y');
    } 	
	
	// ultima semana cerrada
    public function scopeGetUltimaSemanaCerrada($query, $year, $almcnt)
    {
        $row = $query->where('year', $year)
		->where('almcnt', $almcnt)
		->where('status', 1)
        ->orderBy('semana', 'DESC')
        ->first();   
        
        return $row->semana;
    }  		

    /*
    UPDATE calendars 
    SET bimestre =62
    WHERE mes IN(7,8)
    
    UPDATE calendars 
    SET bimestre =61
    WHERE mes IN(9,10)	
    */

} // class
