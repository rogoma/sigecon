<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenditureObject extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla financial_levels
     */
    public function financialLevel(){
        return $this->belongsTo('App\Models\FinancialLevel');
    }

    /**
     * Para obtener el vinculo con la misma tabla
     */
    public function superiorExpenditureObject(){
        return $this->belongsTo('App\Models\ExpenditureObject');
    }

    /**
     * Para obtener el vinculo con la tabla orders
     */
    public function orders(){
        return $this->hasMany('App\Models\Order');
    }
}
