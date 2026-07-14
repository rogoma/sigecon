<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla orders
     */
    public function orders(){
        return $this->belongsToMany('App\Models\Order', 'budget_request_providers');
    }

    /**
     * Para obtener el vinculo con la tabla users
     */
    public function creatorUser(){
        return $this->belongsTo('App\Models\User');
    }

     /**
     * Para obtener el vinculo con la tabla budget_request_providers
     */
    public function budget_request_provider(){
        return $this->hasMany('App\Models\BudgetRequestProvider');
    }

    /**
     * Para obtener el vinculo con la tabla itemawards
     */    
    public function itemAwards(){
       return $this->hasMany('App\Models\ItemAwards');
    }
}
