<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IMSS extends Model
{
    use HasFactory;

    // Desactivar timestamps
    public $timestamps = false;

    // Definir la tabla asociada
    protected $table = 'imsses';

    // Campos llenables
    protected $fillable = [
        // Lista de campos que pueden ser llenados masivamente
        // Ejemplo: 'field1', 'field2', 'field3'
    ];

    // Constantes para los porcentajes
    const EXCEDENTE_OBRERO_PORCENTAJE = 0.004; // 0.40%
    const PRESTACION_DINERO_PORCENTAJE = 0.0025; // 0.25%
    const GASTOS_MEDICOS_PENSIONADOS_PORCENTAJE = 0.00375; // 0.375%
    const INVALIDEZ_VIDA_PORCENTAJE = 0.00625; // 0.625%
    const CESANTIA_EDAD_AVANZADA_VEJEZ_PORCENTAJE = 0.01125; // 1.125%

    /**
     * Calcular el IMSS total.
     *
     * @param float $salarioBaseCotizacion
     * @param int $diasTrabajados
     * @param float $UMA
     * @return float
     */
    public function calcularImss($salarioBaseCotizacion, $diasTrabajados, $UMA)
    {
        return 
            $this->excedenteObrero($salarioBaseCotizacion, $diasTrabajados, $UMA) + 
            $this->prestacionDinero($salarioBaseCotizacion, $diasTrabajados) + 
            $this->gastosMedicosPensionados($salarioBaseCotizacion, $diasTrabajados) + 
            $this->invalidezVida($salarioBaseCotizacion, $diasTrabajados) + 
            $this->cesantiaEdadAvanzadaVejez($salarioBaseCotizacion, $diasTrabajados);
    }

    /**
     * Calcular el excedente obrero.
     *
     * @param float $salarioBaseCotizacion
     * @param int $diasTrabajados
     * @param float $UMA
     * @return float
     */
    protected function excedenteObrero($salarioBaseCotizacion, $diasTrabajados, $UMA)
    {
        $excedente = $UMA * 3;
        
        if ($salarioBaseCotizacion > $excedente) {
            $diferencia = $salarioBaseCotizacion - $excedente;
          
            return ($diferencia * self::EXCEDENTE_OBRERO_PORCENTAJE) * $diasTrabajados;			
        }
        
        return 0;
    }

    /**
     * Calcular la prestación en dinero.
     *
     * @param float $salarioBaseCotizacion
     * @param int $diasTrabajados
     * @return float
     */
    protected function prestacionDinero($salarioBaseCotizacion, $diasTrabajados)
    {
        
        return ($salarioBaseCotizacion * self::PRESTACION_DINERO_PORCENTAJE) * $diasTrabajados;
    }    
    
    /**
     * Calcular los gastos médicos de pensionados.
     *
     * @param float $salarioBaseCotizacion
     * @param int $diasTrabajados
     * @return float
     */
    protected function gastosMedicosPensionados($salarioBaseCotizacion, $diasTrabajados)
    {
     
        return ($salarioBaseCotizacion * self::GASTOS_MEDICOS_PENSIONADOS_PORCENTAJE) * $diasTrabajados;
    }    

    /**
     * Calcular la invalidez y vida.
     *
     * @param float $salarioBaseCotizacion
     * @param int $diasTrabajados
     * @return float
     */
    protected function invalidezVida($salarioBaseCotizacion, $diasTrabajados)
    {
      
        return ($salarioBaseCotizacion * self::INVALIDEZ_VIDA_PORCENTAJE) * $diasTrabajados;
    }    

    /**
     * Calcular la cesantía en edad avanzada y vejez.
     *
     * @param float $salarioBaseCotizacion
     * @param int $diasTrabajados
     * @return float
     */
    protected function cesantiaEdadAvanzadaVejez($salarioBaseCotizacion, $diasTrabajados)
    {
        
        return ($salarioBaseCotizacion * self::CESANTIA_EDAD_AVANZADA_VEJEZ_PORCENTAJE) * $diasTrabajados;
    }    
    
} // class
