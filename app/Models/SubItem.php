<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubItem extends Model
{
    use HasFactory;

    protected $table = 'sub_items_oi';
    
    
    /*** Para obtener el vinculo con la tabla rubros */
    public function rubro(){
        return $this->belongsTo('App\Models\Rubro', 'rubro_id');
    }
   

    /**
     * Para obtener el vinculo con la tabla itemsOrders
     */
    public function items(){
        return $this->hasMany('App\Models\ItemOrder');
    }


    /**
     * Para obtener el vinculo con la tabla users
     */
    public function creatorUser(){
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Para obtener el vinculo con la tabla users
     */
    public function modifierUser(){
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Agregamos funciones que formatean los datos
     * para mayor utilidad en los views
     */
    public function provider(){
        if(empty($this->budget_request_provider_id)){
            return "";
        }else{
            return $this->budgetRequestProvider->provider->description;
        }
    }
    public function dncpPacIdFormat(){
        if(empty($this->dncp_pac_id)){
            return "";
        }else{
            return number_format($this->dncp_pac_id,0,",",".");
        }
    }
    public function amountFormat(){
        return number_format($this->amount,0,",",".");
    }

    public function itemFromDateFormat(){
        if(empty($this->item_from)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->item_from));
        }
    }

    public function itemToDateFormat(){
        if(empty($this->item_to)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->item_to));
        }
    }
}
