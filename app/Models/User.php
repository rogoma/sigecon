<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document',
        'name',
        'lastname',
        'email',
        'password',
        'dependency_id',
        'position_id',
        'state',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Para obtener el vinculo con la tabla dependencies
     */
    public function dependency(){
        return $this->belongsTo('App\Models\Dependency');
    }

    /**
     * Para obtener el vinculo con la tabla positions
     */
    public function position(){
        return $this->belongsTo('App\Models\Position');
    }

    /**
     * Para obtener el vinculo con la tabla roles
     */
    public function role(){
        return $this->belongsTo('App\Models\Role');
    }

    /**
     * Para obtener el vinculo con la tabla contracts
     */
    public function contracts(){
        return $this->hasMany('App\Models\Contract');
    }
    
    /**
     * Para obtener el vinculo con la tabla orders
     */
    public function orders(){
        return $this->hasMany('App\Models\Order');
    }

    /**
     * Obtener el nombre y el apellido del usuario concatenados
     */
    public function getFullName(){
        return $this->name.' '.$this->lastname;
    }

    /**
     * Chequear si el usuario posee el rol especificado
     */
    public function hasRole($roles){
        // chequeamos si el rol del usuario se encuentra en los roles pasados
        if(in_array($this->role->name, $roles)){
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Chequear si el usuario posee el permiso especificado
     */
    public function hasPermission($permissions){
        $role_id = $this->role_id;    // id de los roles del usuario
        $placeholders = implode(',', array_fill(0, count($permissions), '?'));    // descripcion de los permisos del usuario

        $buscar_permiso = DB::select("SELECT a.* FROM roles_permissions as a
                      INNER JOIN permissions as b ON a.permission_id = b.id
                      WHERE a.role_id = ? AND b.description IN ($placeholders)",
                      array_merge([$role_id], $permissions));

        if(count($buscar_permiso) > 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}
