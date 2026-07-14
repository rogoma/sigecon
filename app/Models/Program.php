<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla program_types
     */
    public function programType(){
        return $this->belongsTo('App\Models\ProgramType');
    }

    /**
     * Para obtener el vinculo con la tabla sub_programs
     */
    public function subPrograms(){
        return $this->hasMany('App\Models\SubProgram');
    }

    /**
     * Para obtener el vinculo con la tabla orders
     */
    public function orders(){
        return $this->hasMany('App\Models\Order');
    }
}
