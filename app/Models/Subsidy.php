<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subsidy extends Model
{
    use HasFactory;

    // desactivar timestamps
    public $timestamps = false;

    protected $table = 'subsidies';

    // Campos llenables
    protected $fillable = [
      // Lista de campos que pueden ser llenados masivamente
      // Ejemplo: 'field1', 'field2', 'field3'
    ];

     /**
     * Calculate the subsidy for 2024.
     *
     * @param float $UMA
     * @param int $diasTrabajados
     * @return float
     */
    public static function calculateSubsidio2024($UMA, $diasTrabajados)
    {
        // Calcular el UMA mensual
        $umaMensual = 30.40 * $UMA;

        // Calcular el subsidio mensual (11.82% del UMA mensual)
        $subsidioMensual = $umaMensual * 0.1182;

        // Calcular el subsidio diario
        $subsidioDiario = $subsidioMensual / 30.40;

        // Retornar el subsidio total para los días trabajados
        return $subsidioDiario * $diasTrabajados;
    }

    // subsidio tabla descontinuado*
    public function scopeSubsidio($query, $sueldo_pagar)
    {     

      $row = $query->where('limite_inferior', '<=', $sueldo_pagar)
      ->where('limite_superior', '>=', $sueldo_pagar)
      ->first();
      
      return $row ? number_format($row->cuota_fija, 2) : 0;

      
    }


} // class
