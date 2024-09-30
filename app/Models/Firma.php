<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Firma extends Model
{
    use HasFactory;

    // desactivar timestamps
    public $timestamps = false;

    protected $table = 'firmas';

    // Campos llenables
    protected $fillable = [
        'elaboro',
        'elaboroNombre',
        'valido',
        'validoNombre',
        'autorizo',
        'autorizoNombre',        
        'reviso',
        'revisoNombre',
    ];    

}
