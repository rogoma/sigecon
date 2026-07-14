<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    use HasFactory;

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
}
