<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Employee extends Model
{
    use HasFactory;

    // Activa Carbon para los campos de fecha
    protected $dates = ['fechaIngreso', 'fechaNacimiento', 'fechaTermino'];    
    
    // Campos llenables
    protected $fillable = [
        'almcnt',
        'salary_id',
        'expediente',
        'nombre',
        'paterno',
        'materno',
        'rfc',
        'curp',
        'nss',
        'fechaIngreso',
        'fechaNacimiento',
        'fechaTermino',
        'codigoPostal',
        'valeMensual',
        'valeNavideno',
        'variableOtro',
        'pagoDiaDomingo',
        'primaDominical',
        'infonavit',
        'estado'
    ];

    // relacion salarios muchos a uno
    public function salary()
    {
        return $this->belongsTo(Salary::class);
    }     

    // Método para calcular la antigüedad del empleado en años
    public function getAntiguedadAttribute()
    {
        return Carbon::parse($this->fechaIngreso)->diffInYears(Carbon::now());
    }

    // Método para calcular los días de vacaciones 2024 según la antigüedad
    public function getVacacionesAttribute()
    {
        $antiguedad = $this->antiguedad;

        switch (true) {
        case ($antiguedad >= 0 && $antiguedad < 2):
            return 12;
        case ($antiguedad >= 2 && $antiguedad < 3):
            return 14;
        case ($antiguedad >= 3 && $antiguedad < 4):
            return 16;
        case ($antiguedad >= 4 && $antiguedad < 5):
            return 18;
        case ($antiguedad >= 5 && $antiguedad < 6):
            return 20;
        case ($antiguedad >= 6 && $antiguedad < 11):
            return 22;
        case ($antiguedad >= 11 && $antiguedad < 16):
            return 24;
        case ($antiguedad >= 16 && $antiguedad < 21):
            return 26;
        case ($antiguedad >= 21 && $antiguedad < 26):
            return 28;
        case ($antiguedad >= 26 && $antiguedad < 31):
            return 30;
        case ($antiguedad >= 31):
            return 32;
        default: return 0;
    }

    }

    // Método para calcular el aguinaldo diario
    public function aguinaldoDiario($salarioDiario)
    {
        $diasDelAnioActual = Carbon::now()->daysInYear();
        // $diasDelAnioActual = 365;
        return ($salarioDiario * 40) / $diasDelAnioActual;
    }   

    // Método para calcular la prima vacacional
    public function primaVacacional($diasVacaciones, $salarioDiario)
    {
		$diasDelAnioActual = Carbon::now()->daysInYear();
		
        return ($diasVacaciones * $salarioDiario * 0.25) / $diasDelAnioActual;
    }    

    // Método para calcular el pago del día domingo
    public function pagoDiaDomingo($salarioDiario)
    {
        return ($salarioDiario * 2) / 7;
    }

    // Método para calcular la prima dominical
    public function primaDominical($pagoDiaDomingo)
    {
        return $pagoDiaDomingo * 0.25;
    }  

    public function salarioDiario($tab_vig)
    {
        return ($tab_vig / 30);
    }

    // Método para calcular la parte variable
    public function parteVariable($diasBimestre)
    {
        $diasBimestre = ($diasBimestre > 0) ? (int)$diasBimestre : 61;
       
        $parteVariable = ($this->valeMensual + $this->valeNavideno + $this->variableOtro) / $diasBimestre;
		
		return $parteVariable;

    }  



} // class 
