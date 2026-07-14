<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Protest extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla orders    
     */
    public function order(){
        return $this->belongsTo('App\Models\Order');
    } 
}
