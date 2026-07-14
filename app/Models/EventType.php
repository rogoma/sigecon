<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    use HasFactory;

    protected $table = 'event_types';
    
    /**
     * Para obtener el vinculo con la tabla dependencies
     */
    public function events(){
        return $this->hasMany('App\Models\Event');
    }
    
}
