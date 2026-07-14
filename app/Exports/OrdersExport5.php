<?php

namespace App\Exports;

//use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use App\Models\OrderPresentation;

// class ItemAwardsExport implements FromCollection


class OrdersExport5 implements FromView
{
    //use Exportable;

    // protected $order_id;
    
    // public function __construct()
    // {
        
    // }

    public function view(): view    
    {   
        $order_pres = DB::table('order_presentations')  //vista que muestra los datos              
                    ->select(['id','description'])        
                    ->get();  

        return view("admin.order_presentations.order_pres", compact("order_pres"));
    }
}