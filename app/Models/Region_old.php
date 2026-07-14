<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region_old extends Model
{
    use HasFactory;

    /**
     * Para obtener el vinculo con la tabla districts
     */
    public function districts(){
        return District::where('codreg', $this->codreg)
                     ->where('subcreg', $this->subcreg)
                     ->get();
    }
}
