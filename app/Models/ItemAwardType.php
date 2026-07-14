<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemAwardType extends Model
{
    use HasFactory;

    protected $table = 'item_award_types';

    /**
     * Para obtener el vinculo con la tabla ItemAwardHistories
     */
    public function itemAwardHistories(){
        return $this->hasMany('App\Models\ItemAwardHistory');
    }

    /**
     * Para obtener el vinculo con la tabla orders_order_states
     */
    // public function ordersOrderStates(){
    //     return $this->hasMany('App\Models\OrderOrderState');
    // }
}
