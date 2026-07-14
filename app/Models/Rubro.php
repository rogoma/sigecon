<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    use HasFactory;

    protected $table = 'rubros';

    /**
     * Para obtener el vinculo con la tabla itemsOrders
     */
    public function items(){
        return $this->hasMany('App\Models\ItemOrder');
    }

    /**
     * Para obtener el vinculo con la tabla items
     */
    public function orderPresentations(){
        return $this->belongsTo('App\Models\OrderPresentation', 'order_presentation_id');
    }

    /**
     * Para obtener el vinculo con la tabla items
     */
    public function subitems(){
        return $this->hasMany('App\Models\SubItem','sub_items_oi');
    }
    // public function items(){
    //     return $this->belongsToMany('App\Models\ItemOrder');
    // }

    /**
     * Para obtener el vinculo con la tabla level4_catalog_codes
     */
    // public function level4CatalogCode(){
    //     return $this->belongsTo('App\Models\Level4CatalogCode');
    // }
}
