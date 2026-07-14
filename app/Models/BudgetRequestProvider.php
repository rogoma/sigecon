<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetRequestProvider extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'budget_request_providers';

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
     * Para obtener el vinculo con la tabla item_award_histories
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

    public function ccDateFormat(){
        if(empty($this->cc_date)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->cc_date));
        }
    }
    
    public function montoAdjudicaFormat(){
        return number_format($this->monto_adjudica,0,",",".");
    }

    
    public function montoContractFormat(){
        return number_format($this->monto_contract,0,",",".");
    }

    public function contractNumberFormat(){
        return number_format($this->contract_number,0,",",".");
    }

    public function contractDateFormat(){
        if(empty($this->contract_date)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->contract_date));
        }
    }

    public function contractYearFormat(){
        if(empty($this->contract_date)){
            return "";
        }else{
            return date('Y', strtotime($this->contract_date));
        }
    }
}
