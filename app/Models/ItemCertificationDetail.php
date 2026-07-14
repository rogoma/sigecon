<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCertificationDetail extends Model
{
    use HasFactory;

    protected $table = 'item_certification_details';

    protected $fillable = [
        'item_certification_id',
        'rubro_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function itemCertification()
    {
        return $this->belongsTo('App\Models\ItemCertification');
    }

    public function rubro()
    {
        return $this->belongsTo('App\Models\Rubro', 'rubro_id');
    }
}
