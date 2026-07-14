<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryResponse extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'queries_responses';

    /**
     * Para obtener el vinculo con la tabla queries    
     * llamamos queryParent en vez de query
     * porque la funcionalidad query está reservada
     */
    public function queryParent(){
        return $this->belongsTo('App\Models\Query', 'query_id');
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
