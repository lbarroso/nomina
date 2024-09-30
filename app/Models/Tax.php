<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $table = 'taxes';
    
    // desactivar timestamps
    public $timestamps = false;

    // capos llenables
    protected $fillable = ['limiteInferior','limiteSuperior'];    

    // impuesto previo semanal ISR
    public function scopeIsrSemanal($query, $sueldoGravado)
    {
        
        $row = $query->where('limiteInferior', '<=', $sueldoGravado)
        ->where('limiteSuperior', '>=', $sueldoGravado)
		->where('periodo', 'semanal')
        ->first();

        $diferencia = $sueldoGravado - $row->limiteInferior;
        
        $impuesto_marginal = ($diferencia * $row->porcentaje) / 100;
        
        $impuesto_previo = $row->cuotaFija + $impuesto_marginal;

        // return number_format($impuesto_previo ,2);
        return $impuesto_previo;

    }

} // class
