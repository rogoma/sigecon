<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialLevel extends Model
{
    use HasFactory;

    protected $table = 'financial_levels';

    /**
     * Para obtener el vinculo con la tabla expenditure_objects
     */
    public function expenditureObjects(){
        return $this->hasMany('App\Models\ExpenditureObject');
    }
}
