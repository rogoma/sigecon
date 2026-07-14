<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles_permissions';

    /**
     * Para obtener el vinculo con la tabla permissions
     */
    public function permission(){
        return $this->belongsTo('App\Models\Permission');
    }

    /**
     * Para obtener el vinculo con la tabla roles
     */
    public function role(){
        return $this->belongsTo('App\Models\Role');
    }
}
