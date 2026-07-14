<?php

namespace App\Exports;

//use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

// class ItemAwardsExport implements FromCollection


class DistrictExport implements FromView
{
    //use Exportable;

    // protected $order_id;
    
    // public function __construct()
    // {
        
    // }

    public function view(): view    
    {        
        $districts = DB::table('vista_div_politica')
            ->select('department_id','departamento', 'distrito')
            ->distinct()
            ->orderBy('department_id')            
            ->get();
        
        return view("admin.districts.items", compact("districts"));       

    }
}