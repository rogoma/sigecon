<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Locality extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'district_id'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
