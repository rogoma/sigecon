<?php

namespace App\Exports;

//use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

// class ItemAwardsExport implements FromCollection


class LocalityExport implements FromView
{
    //use Exportable;

    // protected $order_id;
    
    // public function __construct()
    // {
        
    // }

    public function view(): view    
    {        
        $localities = DB::table('vista_div_politica')
            ->select(['departamento', 'distrito', 'localidad'])
            ->orderByRaw('id_departamento')
            ->get();
        
        return view("admin.localities.items", compact("localities"));       

    }
}