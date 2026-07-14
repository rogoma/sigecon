<?php

namespace App\Exports;

//use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

// class ItemAwardsExport implements FromCollection


class OrdersExport2 implements FromView
{
    //use Exportable;

    // protected $order_id;
    
    // public function __construct()
    // {
        
    // }

    public function view(): view    
    {
        $orders = DB::table('vista_reporte1') //vista que muestra los datos
                ->select([
                    'contrato',
                    'nro_contrato',
                    'contratista',
                    'monto_maximo',
                    'monto_minimo',
                    'nro_orden',
                    'fecha_orden',
                    'fecha_acuse_contr',
                    'plazo',
                    'fecha_fin_plazo',
                    'dpto',
                    'distrito',
                    'localidad',
                    'sub_componente',
                    'estado_orden',
                    'nombre_fiscal',
                    'monto_orden',
                    'order_state_id'
                ])
                ->where('order_state_id', '!=', 5) //filtra 5-Orden de Ejecución Anulada
                ->orderBy('contratista')                
                ->orderBy('nombre_fiscal')
                ->orderBy('localidad')
                ->get();
            return view("contract.orders.ordersexcel2", compact("orders"));

    }
}