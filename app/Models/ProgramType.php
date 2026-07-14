<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramType extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla programs
     */
    public function programs(){
        return $this->hasMany('App\Models\Program');
    }
}
