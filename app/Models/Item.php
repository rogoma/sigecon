<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_number',
        // 'name',
        // 'lastname',
        // 'email',
        // 'password',
        // 'dependency_id',
        // 'position_id',
        // 'state',
    ];

    /**
     * Para obtener el vinculo con la tabla contratos
     */
   public function contract(){
       return $this->belongsTo('App\Models\Contract');
   }

//    public function contract_items(){
//     return $this->hasMany('App\Models\Contract');
// }

    /**
     * Para obtener el vinculo con la tabla item_award_histories (Endosos)
     */
    public function itemAwardHistories(){
        return $this->hasMany('App\Models\ItemAwardHistory');
    }


    /**
     * Para obtener el vinculo con la tabla policies (Pólizas)
     */
    public function policy(){
        return $this->belongsTo('App\Models\Policy');
    }

    public function itemFromDateFormat(){
        if(empty($this->item_from)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->item_from));
        }
    }

    public function itemToDateFormat(){
        if(empty($this->item_to)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->item_to));
        }
    }

    public function AmountFormat(){
        return number_format($this->amount,0,",",".");
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



    public function totalAmountFormat(){
        return number_format($this->total_amount,0,",",".");
    }

    /**
     * Agregamos funciones que formatean los datos campos originales
     * para mayor utilidad en los views
     */

    // public function unitPriceFormat(){
    //     return number_format($this->unit_price,0,",",".");
    // }
    // public function totalAmountFormat(){
    //     return number_format($this->total_amount,0,",",".");
    // }
}
