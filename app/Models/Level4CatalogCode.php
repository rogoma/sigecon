<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level4CatalogCode extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla level5_catalog_codes
     */
    public function level5CatalogCodes(){
        return $this->hasMany('App\Models\Level5CatalogCode');
    }

    /**
     * Para obtener el vinculo con la tabla level3_catalog_codes
     */
    public function level3CatalogCode(){
        return $this->belongsTo('App\Models\Level3CatalogCode');
    }
}
