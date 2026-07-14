<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundingSource extends Model
{
    use HasFactory;

    protected $table = 'funding_sources';

    /**
     * Para obtener el vinculo con la tabla orders
     */
    public function orders(){
        return $this->hasMany('App\Models\Order');
    }
}
