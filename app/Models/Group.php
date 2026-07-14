<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla financial_levels    
     */
    public function financialLevel(){
        return $this->belongsTo('App\Models\FinancialLevel');
    } 
}
