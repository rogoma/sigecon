<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependency extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dependencies';

    /**
     * Para obtener el vinculo con la tabla users
     */
    public function users(){
        return $this->hasMany('App\Models\User');
    }

    /**
     * Para obtener el vinculo con la misma tabla
     */
    public function superiorDependency(){
        return $this->belongsTo('App\Models\Dependency');
    }

    /**
     * Para obtener el vinculo con la tabla dependency_types
     */
    public function dependencyType(){
        return $this->belongsTo('App\Models\DependencyType');
    }

    /**
     * Para obtener el vinculo con la tabla uoc_types
     */
    public function uocType(){
        return $this->belongsTo('App\Models\UocType');//cambiar nombre en Model y otros lugares
    }

    /**
     * Para obtener el vinculo con la tabla contracts
     */
    public function contracts(){
        return $this->hasMany('App\Models\Contract');
    }

    /**
     * Para obtener la dependencia con su dependencia superior
     */
    public function getWithSuperiorDependency(){
        if(!is_null($this->superiorDependency)){
            if(!is_null($this->superiorDependency->superiorDependency)){
                return $this->description.' - '.$this->superiorDependency->description;
            }
        }
        return $this->description;
    }
}
