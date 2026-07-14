<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemAwards extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla orders
     */
    public function order(){
        return $this->belongsTo('App\Models\Order');
    }

    /**
     * Para obtener el vinculo con la tabla providers
     */
    public function provider(){
        return $this->belongsTo('App\Models\Provider');
    }
    
    /**
     * Para obtener el vinculo con la tabla level5_catalog_codes
     */
    public function level5CatalogCode(){
        return $this->belongsTo('App\Models\Level5CatalogCode');
    }

    /**
     * Para obtener el vinculo con la tabla order_presentations
     */
    public function orderPresentation(){
        return $this->belongsTo('App\Models\OrderPresentation');
    }

    /**
     * Para obtener el vinculo con la tabla order_measurement_units
     */
    public function orderMeasurementUnit(){
        return $this->belongsTo('App\Models\OrderMeasurementUnit');
    }
    

    /**
     * Para obtener el vinculo con la item_award_histories items
     */
    public function itemAwardHistories(){
        return $this->hasMany('App\Models\ItemAwardHistory');
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

    /**
     * Agregamos funciones que formatean los datos nuevos campos 
     * para mayor utilidad en los views
     */
    public function quantityFormat(){
        return number_format($this->quantity,0,",",".");
    }
    public function min_quantityFormat(){
        return number_format($this->min_quantity,0,",",".");
    }
    public function max_quantityFormat(){
        return number_format($this->max_quantity,0,",",".");
    }
    public function unitPriceFormat(){
        return number_format($this->unit_price,0,",",".");
    }
    public function totalAmount_min_Format(){
        return number_format($this->total_amount_min,0,",",".");
    }
    public function totalAmountFormat(){
        return number_format($this->total_amount,0,",",".");
    }
}
