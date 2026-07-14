<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UocType extends Model
{
    use HasFactory;

    protected $table = 'uoc_types';
    
    /**
     * Para obtener el vinculo con la tabla dependencies
     */
    public function dependencies(){
        return $this->hasMany('App\Models\Dependency');
    }
}
