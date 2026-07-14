<?php

namespace App\Exports;

use App\Models\ItemAwards;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

// class ItemAwardsExport implements FromCollection


class ItemAwardsExport implements FromQuery , WithHeadings , WithMapping
{
    use Exportable;

    protected $order_id;
    
    public function __construct($order_id)
    {
        // $order_id = 19;
        $this->order_id = $order_id;
        // $this->order_id = 19;
    }

    public function query()
    {        
        return ItemAwards::query()
            //    ->where('order_id','=',19);
               ->where('order_id','=',$this->order_id);
    }

    public function headings(): array
    {
        return [
            'ID',
            'OrderID',
            'Marca',
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        //$fecha = Carbon::parse($row->fecha)->format('d/m/Y H:i:s');

        return [
            $row->id,
            $row->order_id,
            $row->trademark
        ];
    }


}

