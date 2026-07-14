<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objection extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla orders    
     */
    public function order(){
        return $this->belongsTo('App\Models\Order');
    } 

    /**
     * Para obtener el vinculo con la tabla objection_responses    
     */
    public function objectionResponses(){
        return $this->hasMany('App\Models\ObjectionResponse');
    } 
    public function updated_atDateFormat(){
        if(empty($this->updated_at)){
            return "";
        }else{
            return date('d/m/Y H:i:s', strtotime($this->updated_at));
        }
    }
    public function created_atDateFormat(){
        if(empty($this->created_at)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->created_at));
        }
    } 
}
