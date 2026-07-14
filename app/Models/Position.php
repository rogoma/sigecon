<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla users
     */
    public function users(){
        return $this->hasMany('App\Models\User');
    }
}
