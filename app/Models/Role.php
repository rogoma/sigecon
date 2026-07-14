<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla users
     */
    public function users(){
        return $this->hasMany('App\Models\User');
    }

    /**
     * Para obtener el vinculo con la tabla permissions
     * a traves de la tabla intermedia role_permissions
     */
    public function permissions(){
        return $this->belongsToMany('App\Models\Permission', 'roles_permissions');
    }

    /**
     * Para obtener el vinculo con la tabla menus
     * a traves de la tabla intermedia roles_menus
     */
    public function menus(){
        return $this->belongsToMany('App\Models\Menu', 'roles_menus');
    }

    /**
     * Para obtener el vinculo con la tabla roles_permissions
     */
    public function rolePermission(){
        return $this->hasMany('App\Models\RolePermission');
    }
}
