<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'department_id']; // Nombre en inglés

    public function department(): BelongsTo
    {
        return $this->belongsTo('App\Models\Department');
    }

    public function orders(): HasMany //Relación con la tabla orders
    {
        return $this->hasMany('App\Models\Order');
    }

    public function localities() //Relación con la tabla localidades
    {
        return $this->hasMany('App\Models\Locality');
    }
}
