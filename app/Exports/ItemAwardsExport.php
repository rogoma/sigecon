<?php

namespace App\Exports;

use App\Models\ItemAwards;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;

// class ItemAwardsExport implements FromCollection
class ItemAwardsExport implements FromQuery

{
    use Exportable;

    //protected $order_id;
    
    public function __construct(int $order_id)
    {
        // var_dump($order_id);exit();
        // $order_id = 19;
        $this->order_id = $order_id;
        // $this->order_id = 19;
    }

    public function query()
    {
        return ItemAwards::query()->where('order_id', $this->order_id);
            
    }

}

