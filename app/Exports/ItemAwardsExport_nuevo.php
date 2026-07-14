<?php

namespace App\Exports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Models\ItemAwards;
use Maatwebsite\Excel\Concerns\FromCollection;


class ItemAwardsExport implements FromCollection
{    
    protected $order_id;
    
    public function __construct($order_id)
    {
        // $order_id = 19;
        $this->order_id = $order_id;
        // $this->order_id = 19;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ItemAwards::where('order_id',$this->order_id)->get();

            // ->where('order_id','=',$this->order_id);    
    }
   
}