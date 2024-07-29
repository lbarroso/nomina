<?php

namespace App\Services;

use App\Models\Tax;
use App\Models\Subsidy;
use App\Models\Calendar;
use App\Models\IMSS;
use App\Models\NominaConcept;

class CalcularNominaService
{

    protected $imss;

    public function __construct(IMSS $imss)
    {
        $this->imss = $imss;
    }    

   /**
     * Calcular la nómina de un empleado.
     *
     * @param float $sueldoMensual
     * @param float $sueldoSemanal
     * @param int $diasTrabajados
     * @param float $sueldoSemanalGravado
     * @param float $salarioBaseCotizacion
     * @param Employee $employee
     * @param Calendar $calendar
     * @param float $UMA
     * @return array
     */    
    public function calcularNomina($sueldoMensual, $sueldoSemanal, $diasTrabajados, $sueldoSemanalGravado, $SDI, $employee, $calendar, $UMA)
    {
        
        $isrNeto = $this->calcularISRSemanal($sueldoSemanalGravado);
        $subsidio = $this->calcularSubsidio($sueldoMensual, $employee, $sueldoSemanalGravado, $calendar, $diasTrabajados, $UMA);
        $imss = $this->calcularImss($SDI, $diasTrabajados, $UMA);        
        $isr = ($isrNeto - $subsidio) < 0 ? 0 : $isrNeto - $subsidio; // Impuesto a retener
        
        // Insertar datos en la nómina
        $this->insertarNomina($this->crearNominaData($calendar, $employee, 1, $sueldoSemanal, 'percepcion', $diasTrabajados));
        $this->insertarNomina($this->crearNominaData($calendar, $employee, 98, $isrNeto, 'informativo'));
        $this->insertarNomina($this->crearNominaData($calendar, $employee, 57, $imss, 'deduccion'));
        if($isr > 0)$this->insertarNomina($this->crearNominaData($calendar, $employee, 58, $isr, 'deduccion'));
        if($subsidio > 0)$this->insertarNomina($this->crearNominaData($calendar, $employee, 99, $subsidio, 'informativo'));

        // dump($isrNeto.'|'.$subsidio.'|'.$imss.'|'.$isr);
        return [
            'isrNeto' => number_format($isrNeto, 2),
            'isr' => number_format($isr, 2),
            'subsidio' => number_format($subsidio, 2),
            'imss' => number_format($imss, 2),
        ];

    }

    // pagar solo velador
    public function velador($pagoDiaDomingo, $primaDominical, $employee, $calendar)
    {
        // Insertar datos en la nómina
        $this->insertarNomina($this->crearNominaData($calendar, $employee, 7, $pagoDiaDomingo, 'percepcion'));
        $this->insertarNomina($this->crearNominaData($calendar, $employee, 2, $primaDominical, 'percepcion'));
    }

    // pagar solo incapacidad
    public function incapacidad($sueldoSemanal, $employee, $calendar)
    {
        // Insertar datos en la nómina
        $this->insertarNomina($this->crearNominaData($calendar, $employee, 1, $sueldoSemanal, 'percepcion'));        

    }

    /**
     * Crear los datos de la nómina.
     *
     * @param Calendar $calendar
     * @param Employee $employee
     * @param int $conceptId
     * @param float $monto
     * @param string $tipo
     * @param int $diasTrabajados
     * @return array
     */
    protected function crearNominaData($calendar, $employee, $conceptId, $monto, $tipo, $diasTrabajados = 0)
    {
        return [
            'year' => $calendar->year,
            'almcnt' => $calendar->almcnt,
            'fecha' => now()->format('Y-m-d'),
            'semana' => $calendar->semana,
            'diasPagados' => $calendar->diasPagados,
            'salary_id' => $employee->salary_id,
            'expediente' => $employee->expediente,
            'concept_id' => $conceptId,
            'monto' => $monto,
            'tipo' => $tipo,
            'diasTrabajados' => $diasTrabajados,
        ];
    }

    /**
     * Insertar datos en la nómina.
     *
     * @param array $nominaData
     * @return void
     */    
    protected function insertarNomina(array $nominaData)
    {
        NominaConcept::create($nominaData);
    }    

    // impuesto previo
    protected function calcularISRSemanal($sueldoSemanalGravado)
    {
        
        return Tax::isrSemanal($sueldoSemanalGravado);
    }
        
    // Lógica para calcular el Subsidio basado en el sueldo semanal
    protected function calcularSubsidio($SueldoMensual, $employee, $sueldoSemanalGravado, $calendar, $diasTrabajados, $UMA)
    {
        // Obtener información del calendario
        $semanaCalendarioInicio = Calendar::getSemanaCalendarioInicio($calendar->mes, $calendar->almcnt);
        $semanaCalendarioFinal = Calendar::getSemanaCalendarioFinal($calendar->mes, $calendar->almcnt); 
        $semanaCalendario = $calendar->semana;
        $semanas = ($semanaCalendarioFinal - $semanaCalendarioInicio) + 1;

        // Inicializar variables
        $totalMensual = 0;
        $totaldiasTrabajados = 0;

        // Regla de exclusión por sueldo mensual
        if($SueldoMensual > 9081.01) return 0;

        // CASO 1 SEMANA INICIO
        if($semanaCalendario == $semanaCalendarioInicio)
        {
            $totalMensual = $sueldoSemanalGravado * $semanas;
            
            if($totalMensual > 9081.01) return 0;

            return Subsidy::calculateSubsidio2024($UMA, $diasTrabajados);
        }
        
        // CAS0 2 SEMANA FINAL
        if($semanaCalendario == $semanaCalendarioFinal)
        {
            
            $semanaCalendarioFinal = $semanaCalendarioFinal - 1;

            $totalMensual = NominaConcept::getSueldoGrabado($calendar->year, $calendar->almcnt, $employee->expediente, $semanaCalendarioInicio, $semanaCalendarioFinal);
            
            $totalMensual = $totalMensual + $sueldoSemanalGravado;

            // total subsidio pagado
            $totalSubsidioPagado = NominaConcept::getSubsidioPagado($calendar->year, $calendar->almcnt, $employee->expediente, $semanaCalendarioInicio, $semanaCalendarioFinal);            

            // ajuste ISR
            if($totalMensual > 9081.01 && $totalSubsidioPagado > 0) return (-1) * $totalSubsidioPagado;  
            // condicion 1
            if($totalMensual > 9081.01 && $totalSubsidioPagado == 0) return 0;                      
            // condicion 2
            if($totalSubsidioPagado > 0) return Subsidy::calculateSubsidio2024($UMA, $diasTrabajados);

            // no se pago subsidio al trabajador en semanas anteriores DEBERIA PAGARSE COMO UNA PERCEPCION
            $totaldiasTrabajados = NominaConcept::getDiasTrabajados($calendar->year, $calendar->almcnt, $employee->expediente, $semanaCalendarioInicio, $semanaCalendarioFinal);
            $totaldiasTrabajados = $totaldiasTrabajados + $diasTrabajados;            
            if($totaldiasTrabajados > 28) $totaldiasTrabajados = 28; // mayor de cuatro semanas

            return Subsidy::calculateSubsidio2024($UMA, $totaldiasTrabajados);
        }        

        // SEMANA INTERMEDIA
        $semanas = ($semanaCalendarioFinal - $semanaCalendario) + 1;
        
        // total sueldo pagado
        $totalMensual = NominaConcept::getSueldoGrabado($calendar->year, $calendar->almcnt, $employee->expediente, $semanaCalendarioInicio, $semanaCalendario - 1);
        
        $totalMensual = $totalMensual + ($sueldoSemanalGravado * $semanas);
        
        // condicion 1
        if($totalMensual > 9081.01) return 0;        

        // default
        return Subsidy::calculateSubsidio2024($UMA, $diasTrabajados);
    }

    /**
     * Calcular el IMSS para un empleado dado su salario base de cotización, días trabajados y UMA.
     *
     * @param float $salarioBaseCotizacion
     * @param int $diasTrabajados
     * @param float $UMA
     * @return float
     */    
    protected function calcularImss($salarioBaseCotizacion, $diasTrabajados, $UMA)
    {
        return $this->imss->calcularImss($salarioBaseCotizacion, $diasTrabajados, $UMA);
    }    

} // class
