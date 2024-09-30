<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class NominaConcept extends Model
{
    use HasFactory;

    // desactivar timestamps
    public $timestamps = false;

    protected $table = 'nomina_concepts';

    // Campos llenables
    protected $fillable = [
        'year',
        'almcnt',
        'fecha',
        'semana',
        'diasPagados',
        'salary_id',
        'expediente',
        'concept_id',
        'monto',
        'tipo',
		'diasTrabajados',
		'calculo',
    ];

    // Campos de fecha (si se necesita trabajar con fechas como instancias de Carbon)
    protected $dates = [
        'fecha'
    ];

    /**
     * Relación con el modelo Concept
     */
    public function concept()
    {
        return $this->belongsTo(Concept::class);
    }  

    /**
     * Scope para obtener el sueldo grabado.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $year
     * @param int $almcnt
     * @param int $expediente
     * @param int $semanaCalendarioInicio
     * @param int $semanaCalendarioFinal
     * @return float
     */
    public function scopeGetSueldoGrabado($query, $year, $almcnt, $expediente, $semanaCalendarioInicio, $semanaCalendarioFinal, $UMA)
    {
		
        $concepts = $query->join('concepts', 'nomina_concepts.concept_id', '=', 'concepts.id')
            ->where('concepts.impuesto', 'SI')
            ->where('nomina_concepts.year', $year)
            ->where('nomina_concepts.almcnt', $almcnt)
            ->where('nomina_concepts.expediente', $expediente)
            ->where('nomina_concepts.tipo', 'percepcion')
            ->whereBetween('nomina_concepts.semana', [$semanaCalendarioInicio, $semanaCalendarioFinal])
            ->get(['concepts.id', 'nomina_concepts.monto']);

        $sueldoGrabado = 0;
		
        foreach ($concepts as $concept) {
			// pago dia domingo
			if($concept->id == 7) $concept->monto = $concept->monto * 0.50;
			// prima vacacional
			if($concept->id == 9){
				$exedente = 15 * $UMA;
				$exedente = $concept->monto - $exedente;
				if($exedente > 0) $concept->monto = $exedente;
			} 
            // dias festivos
            if($concept->id == 12){
                $concept->monto = $concept->monto * 0.50;
            }
				
			$sueldoGrabado += $concept->monto;
        }

        return $sueldoGrabado;
    }
	
	// elaboracion del calculo
	public function scopeGetFechaElaboracion($query, $year, $almcnt, $semanaCalendario)
	{
		// Obtener la fecha del primer registro que cumpla con las condiciones
		$fecha = $query->where('year', $year)
			->where('almcnt', $almcnt)
			->where('semana', $semanaCalendario)
			->where('concept_id', 1)
			->first(['fecha']);		
			
		// Si se encuentra un registro, formatear la fecha
		if ($fecha) {
			$formattedDate = Carbon::parse($fecha->fecha)->format('d-m-Y');
		} else {
			$formattedDate = ''; // O manejar el caso en que no se encuentre un registro
		}

		// Retornar la fecha formateada
		return $formattedDate;
			
	}
	
    public function scopeGetDiasTrabajados($query, $year, $almcnt, $expediente, $semanaCalendarioInicio, $semanaCalendarioFinal)
    {
		return $query->where('nomina_concepts.year', $year)
			->where('nomina_concepts.almcnt', $almcnt)
			->where('nomina_concepts.concept_id', 1) // sueldo
			->where('nomina_concepts.expediente', $expediente)
			->whereBetween('nomina_concepts.semana', [$semanaCalendarioInicio, $semanaCalendarioFinal])
			->sum('nomina_concepts.diasTrabajados');
    }

    // faltas
    public function scopeGetFaltas($query, $year, $almcnt, $expediente, $semana)
    {
        $conceptIds = [52,53,54];
        
		return $query->where('nomina_concepts.year', $year)
			->where('nomina_concepts.almcnt', $almcnt)
			->where('nomina_concepts.expediente', $expediente)
			->where('nomina_concepts.semana', $semana)
            ->whereIn('concept_id', $conceptIds)
			->sum('nomina_concepts.diasPagados');
    }
	
    public function scopeGetSubsidioPagado($query, $year, $almcnt, $expediente, $semanaCalendarioInicio, $semanaCalendarioFinal)
    {
		return $query->where('nomina_concepts.year', $year)
			->where('nomina_concepts.almcnt', $almcnt)
			->where('nomina_concepts.concept_id', 99) // subsidio
			->where('nomina_concepts.expediente', $expediente)
			->whereBetween('nomina_concepts.semana', [$semanaCalendarioInicio, $semanaCalendarioFinal])
			->sum('nomina_concepts.monto');
    }	

    // dias incapacidad
    public function scopeGetIncapacidad($query, $year, $almcnt, $expediente, $semanaCalendario)
    {
        return $query->where('nomina_concepts.year', $year)
            ->where('nomina_concepts.almcnt', $almcnt)
            ->where('nomina_concepts.concept_id', 53)
            ->where('nomina_concepts.expediente', $expediente)
            ->where('nomina_concepts.semana', $semanaCalendario)
            ->sum('nomina_concepts.diasPagados');
    }
	
	// percepciones por empleado
	public function ScopeGetPercepciones($query, $year, $almcnt, $expediente, $semanaCalendario)
	{
        return $query->where('nomina_concepts.year', $year)
            ->where('nomina_concepts.almcnt', $almcnt)
			->where('nomina_concepts.semana', $semanaCalendario)
            ->where('nomina_concepts.expediente', $expediente)
			->where('nomina_concepts.tipo', 'percepcion')
            ->get();	
	}
	
	// total percepciones
	public function ScopeGetTotalPercepciones($query, $year, $almcnt, $semana)
	{
        return $query->where('nomina_concepts.year', $year)
            ->where('nomina_concepts.almcnt', $almcnt)
			->where('nomina_concepts.semana', $semana)
			->where('nomina_concepts.tipo', 'percepcion')
            ->sum('nomina_concepts.monto');
	}
	
	// deducciones por empleado
	public function ScopeGetDeducciones($query, $year, $almcnt, $expediente, $semanaCalendario)
	{
        return $query->where('nomina_concepts.year', $year)
            ->where('nomina_concepts.almcnt', $almcnt)
			->where('nomina_concepts.semana', $semanaCalendario)
            ->where('nomina_concepts.expediente', $expediente)
			->where('nomina_concepts.tipo', 'deduccion')
            ->get();	
	}
	
	// total percepciones
	public function ScopeGetTotalDeducciones($query, $year, $almcnt, $semana)
	{
        return $query->where('nomina_concepts.year', $year)
            ->where('nomina_concepts.almcnt', $almcnt)
			->where('nomina_concepts.semana', $semana)
			->where('nomina_concepts.tipo', 'deduccion')
            ->sum('nomina_concepts.monto');
	}
	
	// isr Neto por empleado
    public function scopeGetIsrNeto($query, $year, $almcnt, $expediente, $semana)
    {
		return $query->where('nomina_concepts.year', $year)
			->where('nomina_concepts.almcnt', $almcnt)
			->where('nomina_concepts.concept_id', 98) // isr Neto
			->where('nomina_concepts.expediente', $expediente)
			->where('nomina_concepts.semana', $semana)
			->sum('nomina_concepts.monto');
    }	
	
	// monto por concepto
    public function scopeGetMonto($query, $year, $almcnt, $expediente, $concept_id, $semanaCalendarioInicio, $semanaCalendarioFinal)
    {
		return $query->where('nomina_concepts.year', $year)
			->where('nomina_concepts.almcnt', $almcnt)
			->where('nomina_concepts.concept_id', $concept_id) // concepto
			->where('nomina_concepts.expediente', $expediente)
			->whereBetween('nomina_concepts.semana', [$semanaCalendarioInicio, $semanaCalendarioFinal])
			->sum('nomina_concepts.monto');
    }		
	
	
} // class

/**
SELECT  concepts.concepto, nomina_concepts.monto, concepts.impuesto, concepts.formula
FROM nomina_concepts
INNER JOIN concepts ON nomina_concepts.concept_id = concepts.id AND concepts.impuesto ='SI'
WHERE nomina_concepts.year = 2024 AND nomina_concepts.almcnt =813 AND nomina_concepts.expediente=12554 AND nomina_concepts.tipo='percepcion'
AND nomina_concepts.semana BETWEEN 31 AND 34
**/