<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

// class ItemAwardsExport implements FromCollection


class ItemAwardsExport3 implements FromView
{
    use Exportable;

    protected $order_id;
    
    // public function __construct(int $order_id)
    // {
    //     $this->order_id = $order_id;
    // }

    public function view(): view    
    {
        // $order_id = $this->order_id;
        
        $orders = Order::all();

        // $orders = DB::table('vista_orders_items')        
        // ->select(['order_id','batch', 'item_number' , 'code', 'level5_catalog_codes_description', 'technical_specifications', 
        // 'order_presentations_description', 'order_measurement_units_description', 'unit_price', 'quantity', 'total_amount'])         
        // ->get();

        // $orders = DB::table('vista_pdf_form1')       
        // ->select(['id','orders_description','total_amount','modalities_code']) 
        // ->where('id', '=', $order_id)      
        // ->get();
                
        return view("order.orders.items", compact("orders"));
    }
}