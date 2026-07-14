<?php

namespace App\Exports;

//use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use App\Models\User;

// class ItemAwardsExport implements FromCollection


class OrdersExport4 implements FromView
{
    //use Exportable;

    // protected $order_id;
    
    // public function __construct()
    // {
        
    // }

    public function view(): view    
    {   
        $dependencies = DB::table('vista_dependencies_full')  //vista que muestra los datos              
                    ->select(['sup_dependencia','dependencia','tipo_dependencia','tipo_uoc','uoc_number','sicp','sup_dependencia'])        
                    ->get();  

        return view("admin.dependencies.dependencies", compact("dependencies"));
    }
}