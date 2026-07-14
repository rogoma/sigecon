<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProgram extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla programs
     */
    public function program(){
        return $this->belongsTo('App\Models\Program');
    }

    /**
     * Para obtener el vinculo con la tabla program_measurement_units
     */
    public function programMeasurementUnit(){
        return $this->belongsTo('App\Models\ProgramMeasurementUnit');
    }

    /**
     * Para desplegar la linea presupuestaria
     */
    public function budgetStructure(){
        return $this->program->programType->code.'-'.$this->program->code.'-'.
               $this->activity_code.'-'.$this->proyecto.' '.$this->description;
    }
}