<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        // 'item_number',
        // 'rubro_id',
        // 'quantity',
        // 'unit_price_mo',
        // 'unit_price_mat',
        // 'tot_price_mo',
        // 'tot_price_mat',
        // 'item_state',
        // 'order_id',
        // 'creator_user_id',
    ];   
    
    /*** Para obtener el vinculo con la tabla orders */
    public function order(){        
        return $this->belongsTo('App\Models\Order');
    }

    /**
     * Para obtener el vinculo con la tabla event_types
     */
    public function eventType(){
        return $this->belongsTo('App\Models\EventType');
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

    public function eventDateFormat(){
        if(empty($this->event_date)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->event_date));
        }
    } 
    
    public function eventDateFinFormat(){
        if(empty($this->event_date_fin)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->event_date_fin));
        }
    } 
}
