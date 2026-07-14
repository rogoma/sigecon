<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level2CatalogCode extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla level3_catalog_codes
     */
    public function level3CatalogCodes(){
        return $this->hasMany('App\Models\Level3CatalogCode');
    }

    /**
     * Para obtener el vinculo con la tabla level1_catalog_codes
     */
    public function level1CatalogCode(){
        return $this->belongsTo('App\Models\Level1CatalogCode');
    }
}
