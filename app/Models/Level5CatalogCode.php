<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level5CatalogCode extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla level4_catalog_codes
     */
    public function level4CatalogCode(){
        return $this->belongsTo('App\Models\Level4CatalogCode');
    }

    /**
     * Para obtener el vinculo con la tabla items
     */
    public function items(){
        return $this->hasMany('App\Models\Item');
    }
}
