<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends Model
{
    use HasFactory;
    protected $table = 'departments'; // Asegúrate que coincide con el nombre real de tu tabla

    
    public function districts(): HasMany
    {
        return $this->hasMany('App\Models\District');
    }
  

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
