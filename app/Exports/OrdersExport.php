<?php

namespace App\Exports;

//use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

// class ItemAwardsExport implements FromCollection


class OrdersExport implements FromView
{
    //use Exportable;

    protected $contract_id;
    
    public function __construct(int $contract_id)
    {
        $this->contract_id = $contract_id;
    }

    public function view(): view    
    {        
        $contract_id = $this->contract_id;

        $orders = DB::table('vista_orders')//vista que muestra los datos generales
                    // ->select(['fiscal_name','fiscal_lastname','providers_description', 
                    //             'components_code','orders_number','orders_date',
                    //             'orders_total_amount','districts_description','orders_locality',
                    //             'components_description','sign_date',
                    //             'orders_plazo','order_states_description', 'orders_id'])                   
                    // ->where('contracts_id', '=', $contract_id)
                    // ->where('order_states_id', '<>', 5) //no mostrar las órdenes anuladas
                    // ->orderBy('orders_date','asc') 
                    // ->get();
                    ->select([
    'fiscal_name',
    'fiscal_lastname',    
    'components_code',
    'orders_number',
    'orders_date',
    'orders_total_amount',
    'districts_description',
    'orders_locality',
    'components_description',
    'sign_date',
    'orders_plazo',
    'order_states_description',
    'orders_id'
])
->where('contracts_id', '=', $contract_id)
->where('order_states_id', '<>', 5) // no mostrar las órdenes anuladas
->groupBy('orders_id', 'fiscal_name', 'fiscal_lastname', 'components_code',
          'orders_number', 'orders_date', 'orders_total_amount', 'districts_description',
          'orders_locality', 'components_description', 'sign_date',
          'orders_plazo', 'order_states_description') // debes agrupar todos los campos seleccionados
->orderBy('orders_date', 'asc')
->get();
        return view('contract.orders.ordersexcel', compact('orders'));
    }
}