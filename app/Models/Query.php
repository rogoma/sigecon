<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'queries';

    /**
     * Para obtener el vinculo con la tabla orders    
     */
    public function order(){
        return $this->belongsTo('App\Models\Order');
    } 

    /**
     * Para obtener el vinculo con la tabla queries_responses    
     */
    public function queryResponses(){
        return $this->hasMany('App\Models\QueryResponse');
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
