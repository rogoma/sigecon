<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla roles 
     * a traves de la tabla intermedia role_permissions
     */
    public function roles(){
        return $this->belongsToMany('App\Models\Role', 'roles_permissions');
    }

    /**
     * Para obtener el vinculo con la tabla role_permissions
     */
    public function rolePermission(){
        return $this->hasMany('App\Models\RolePermission');
    }
}
