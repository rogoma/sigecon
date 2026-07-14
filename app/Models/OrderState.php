<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderState extends Model
{
    use HasFactory;

    protected $table = 'order_states';

    /**
     * Para obtener el vinculo con la tabla orders
     */
    public function orders(){
        return $this->belongsTo('App\Models\Order');
    }

//     public function orders(){
//         return $this->hasMany('App\Models\Order');
//    }

    /**
     * Para obtener el vinculo con la tabla orders_order_states
     */
    public function ordersOrderStates(){
        return $this->hasMany('App\Models\OrderOrderState');
    }
}
