<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileOrder extends Model
{
    use HasFactory;
    
    use HasFactory;

    protected $table = 'files_orders';

    protected $fillable = [
        // 'item_number',
        // 'rubro_id',
        // 'quantity',
        // 'unit_price_mo',
        // 'unit_price_mat',
        // 'tot_price_mo',
        // 'tot_price_mat',
        // 'item_state',
        // 'order_id',
        // 'creator_user_id',
    ];   
    /**
     * Para obtener el vinculo con la tabla orders    
     */
    public function order(){
        return $this->belongsTo('App\Models\Order');
    }

    /**
     * Agregamos funciones que formatean los datos 
     * para mayor utilidad en los views
     */
    public function updated_atDateFormat(){
        if(empty($this->updated_at)){
            return "";
        }else{
            return date('d/m/Y H:i:s', strtotime($this->updated_at));
        }
    }
    public function created_atDateFormat(){
        if(empty($this->created_at)){
            return "";
        }else{
            return date('d/m/Y', strtotime($this->created_at));
        }
    } 

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
