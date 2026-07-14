<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;

    protected $table = 'components';

    /**
     * Para obtener el vinculo con la tabla orders
     */
    public function orders(){
        return $this->hasMany('App\Models\Order');
    }

    public function itemsContract(){
        return $this->hasMany('App\Models\ItemContract');
    }
}
