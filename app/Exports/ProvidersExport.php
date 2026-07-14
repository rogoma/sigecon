<?php

namespace App\Exports;

//use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use App\Models\OrderPresentation;

// class ItemAwardsExport implements FromCollection


class ProvidersExport implements FromView
{
    //use Exportable;

    // protected $order_id;

    // public function __construct()
    // {

    // }

    public function view(): view
    {
        $providers = DB::table('providers')  //vista que muestra los datos
                    ->select(['description','ruc','telefono','email_oferta','email_ocompra','representante'])
                    ->get();

        return view("admin.providers.providers_excel", compact("providers"));
    }
}
