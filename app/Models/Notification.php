<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    // La tabla asociada a este modelo
    protected $table = 'notifications';

    // Los campos que se pueden asignar de manera masiva
    protected $fillable = [
        'title',
        'status',
    ];

} // class
