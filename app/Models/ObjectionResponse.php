<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectionResponse extends Model
{
    use HasFactory;

    protected $table = "objections_responses";

    /**
     * Para obtener el vinculo con la tabla objections    
     */
    public function objection(){
        return $this->belongsTo('App\Models\Objection');
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
