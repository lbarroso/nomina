<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    // campos llenables
    protected $fillable = ['puesto','tab_ant','tab_vig'];

    // relacion empleados uno a muchos
    public function empleyees(){
        return $this->hasMany(Employee::class);
    }      

} // class
