<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderOrderState extends Model
{
    use HasFactory;

    protected $table = 'orders_order_states';

    /**
     * Para obtener el vinculo con la tabla orders  
     */
    public function order(){
        return $this->belongsTo('App\Models\Order');
    }

    /**
     * Para obtener el vinculo con la tabla orders  
     */
    public function orderState(){
        return $this->belongsTo('App\Models\OrderState');
    }

    /**
     * Para obtener el vinculo con la tabla users
     */
    public function creatorUser(){
        return $this->belongsTo('App\Models\User');
    }

    // Formateamos la fecha
    public function createdAtDateFormat(){
        if(empty($this->created_at)){
            return "";
        }else{
            return date('d/m/Y H:i', strtotime($this->created_at));
        }
    }
}
