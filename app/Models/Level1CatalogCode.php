<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level1CatalogCode extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla level2_catalog_codes
     */
    public function level2CatalogCodes(){
        return $this->hasMany('App\Models\Level2CatalogCode');
    }
}