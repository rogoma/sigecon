<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractState extends Model
{
    use HasFactory;

    protected $table = 'contract_states';

    /**
     * Para obtener el vinculo con la tabla contracts
     */
    public function contracts(){
        return $this->hasMany('App\Models\Contract');
    }

    /**
     * Para obtener el vinculo con la tabla orders_order_states
     */
    // public function ordersOrderStates(){
    //     return $this->hasMany('App\Models\OrderOrderState');
    // }
}
