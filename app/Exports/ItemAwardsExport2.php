<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

// class ItemAwardsExport implements FromCollection


class ItemAwardsExport2 implements FromView
{
    use Exportable;

    protected $order_id;
    
    public function __construct(int $order_id)
    {
        $this->order_id = $order_id;
    }

    public function view(): view    
    {
        $order_id = $this->order_id;
                
        $orders = DB::table('vista_orders_items')        
        ->select(['order_id','batch', 'item_number' , 'code', 'level5_catalog_codes_description', 'technical_specifications', 
        'order_presentations_description', 'order_measurement_units_description', 'unit_price', 'quantity', 'max_quantity','total_amount']) 
        ->where('order_id', '=', $order_id)      
        ->get();

        
        $providers = DB::table('vista_participa')        
        ->select(['order_id','provider', 'ruc']) 
        ->where('order_id', '=', $order_id)  
        ->get();

        //se tendrá como segunda opción
        // $providers2 = DB::table('vista_participa')        
        // ->select(['order_id','provider', 'ruc']) 
        // ->where('order_id', '=', $order_id)  
        // ->get();

        return view("award.awards.items", compact("orders","providers"));
    }
}