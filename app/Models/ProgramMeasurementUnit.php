<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramMeasurementUnit extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla items
     */
    public function subPrograms(){
        return $this->hasMany('App\Models\SubProgram');
    }
}
