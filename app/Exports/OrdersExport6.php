<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View2;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrdersExport6 implements FromView
{
    public function generarContracts0(Request $request)
    {
        //capturamos el nombre del método para poder cambiar el título del reporte en la vista
        $nombreMetodo = __METHOD__;

        //No filtra por permiso si es rol admin y uoc2
        if($request->user()->hasPermission(['admin.contracts.show'])){
            $contracts = DB::table('vista_contracts')//vista que muestra los datos
            ->select(['llamado', 'iddncp','number_year','year_adj','sign_date','contratista',
            'estado', 'code', 'modalidad', 'org_financ', 'tipo_contrato','contract_begin_date',
            'contract_end_date', 'total_amount', 'comentarios','dependencia'])
            ->get();
        }else{
            $contracts = DB::table('vista_contracts')//vista que muestra los datos
            ->select(['llamado', 'iddncp','number_year','year_adj','sign_date','contratista',
            'estado', 'code', 'modalidad', 'org_financ', 'tipo_contrato','contract_begin_date',
            'contract_end_date', 'total_amount', 'comentarios','dependencia'])
            ->where('dependency_id', $request->user()->dependency_id)//filtra por dependencia que generó la info
            ->get();
        }
        // $view = View2::make('contract.contracts.users', compact('contracts'))->render();
    }

    public function view(): View
    {
        $contracts = DB::table('vista_contracts_vctos')//vista que muestra los datos
        ->select(['iddncp','number_year','contratista','tipo_contrato','total_amount','fecha_tope_advance',
        'vcto_adv','dias_advance', 'llamado', 'polizas', 'number_policy', 'modalidad', 'comentarios','dependencia'])
        ->where('dias_advance', '<=', 0)
        ->get();
        return view("contract.contracts.users", compact("contracts"));
    }
}


// <?php

// namespace App\Exports;

// use Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Concerns\FromView;
// use Illuminate\Support\Facades\DB;


// class OrdersExport6 implements FromView
// {
//     public function view(): view
//     {
//         $contracts = DB::table('vista_contracts_vctos')//vista que muestra los datos
//         ->select(['iddncp','number_year','contratista','tipo_contrato','total_amount','fecha_tope_advance',
//         'vcto_adv','dias_advance', 'llamado', 'polizas', 'number_policy', 'modalidad', 'comentarios'])
//         ->where('dias_advance', '<=', 0)
//         ->get();
//         return view("contract.contracts.users", compact("contracts"));
//     }
// }
