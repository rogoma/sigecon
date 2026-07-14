<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level3CatalogCode extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla level4_catalog_codes
     */
    public function level4CatalogCodes(){
        return $this->hasMany('App\Models\Level4CatalogCode');
    }

    /**
     * Para obtener el vinculo con la tabla level2_catalog_codes
     */
    public function level2CatalogCode(){
        return $this->belongsTo('App\Models\Level2CatalogCode');
    }
}
