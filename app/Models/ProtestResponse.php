<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProtestResponse extends Model
{
    use HasFactory;

    protected $table = "protests_responses";

    /**
     * Para obtener el vinculo con la tabla protests    
     */
    public function protest(){
        return $this->belongsTo('App\Models\Protest');
    } 
}
