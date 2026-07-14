<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCertification extends Model
{
    use HasFactory;

    protected $table = 'item_certifications';

    protected $fillable = [
        'order_id',
        'number',
        'period',
        'sign_date',
        'creator_user_id',
    ];

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function details()
    {
        return $this->hasMany('App\Models\ItemCertificationDetail');
    }

    public function creatorUser()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function signDateFormat()
    {
        return \Carbon\Carbon::parse($this->sign_date)->format('d/m/Y');
    }
}
