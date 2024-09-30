<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'companies';

    // Campos fillables
    protected $fillable = [
        'nombre',
        'rfc',
        'calle',
        'calleNo',
        'municipio',
        'localidad',
        'estado',
        'codigoPostal',
        'regimen'
    ];    

    // desactivar timestamps
    public $timestamps = false;
        
}
