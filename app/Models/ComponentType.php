<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComponentType extends Model
{
    use HasFactory;

    protected $table = 'component_types';

    /**
     * Para obtener el vinculo con la tabla components
     */
    public function components(){
        return $this->hasMany('App\Models\Component', 'comp_type_id');
    }
}
