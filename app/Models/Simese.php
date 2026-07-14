<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simese extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'simese';

    /**
     * Para obtener el vinculo con la tabla orders  
     */
    public function order(){
        return $this->belongsTo('App\Models\Order');
    } 

    /**
     * Para obtener el vinculo con la tabla users    
     */
    public function user(){
        return $this->belongsTo('App\Models\User');
    } 

    /**
     * Para obtener el vinculo con la tabla dependencies    
     */
    public function dependency(){
        return $this->belongsTo('App\Models\Dependency');
    } 

    // /**
    //  * Para obtener el vinculo con la tabla order_state
    //  */
    // public function orderState(){
    //     return $this->belongsTo('App\Models\OrderState');
    // } 
}
