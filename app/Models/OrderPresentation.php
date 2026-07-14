<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPresentation extends Model
{
    use HasFactory;

    protected $table = 'order_presentations';
    /**
     * Para obtener el vinculo con la tabla items
     */
    public function rubros(){
        return $this->hasMany('App\Models\Rubro','order_presentation_id');
    }
    
    /**
     * Para obtener el vinculo con la tabla items
     */
    // public function items(){
    //     return $this->hasMany('App\Models\Item');
    // }
}
