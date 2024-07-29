<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concept extends Model
{
    use HasFactory;
    
    protected $table = 'concepts';

    // desactivar timestamps
    public $timestamps = false;    

    // Campos llenables
    protected $fillable = [
        'concepto',
        'descripcion',
        'tipo', // deduccion, percepcion, informativo
        'visible',
        'orden',
        'tipoSAT'
    ];


}
