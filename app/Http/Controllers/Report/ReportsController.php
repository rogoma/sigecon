<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Provider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use App\Mail\enviar_alertas;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport6;
use Mpdf;
// use Mpdf;

class ReportsController extends Controller
{

    public function __construct()
    {
        //$this->middleware('guest');

        // pdfUsers2 expone la cédula de todos los usuarios (que es su contraseña por defecto),
        // se restringe al permiso de administración de usuarios ya existente.
        $this->middleware('checkPermission:admin.users.index')->only(['pdfUsers2']);
    }

    public function index()
    {
        // for ($i = 0; $i < 5; $i++) {
        //     $html = '<h1>Hello World '.$i.'</h1>';
        //     PDF2::SetTitle('Hello World'.$i);
        //     PDF2::AddPage();
        //     PDF2::Write(0, 'Hello World'.$i);
        //     // Write the file instead of throw it in the browser
        //     PDF2::Output(public_path('hello_world' . $i . '.pdf'), 'F');
        //     PDF2::reset();
        // }
    }

    public function pdfMPDF()
    {
        $tipres = DB::table('order_presentations')
            // $tipres = DB::table('level5_catalog_codes')
            ->select(['id', 'description'])
            ->orderBy('description', 'asc')
            ->get();
        return view('reports.tipres', compact('tipres'));
    }


    // REPORTE QUE MUESTRAN TODAS LAS ORDENES DEL SISTEMA
    public function generarOrders1(Request $request)
    {
        //capturamos el nombre del método para poder cambiar el título del reporte en la vista
        $nombreMetodo = __METHOD__;

        //No filtra por permiso si es rol admin y uoc2
        // if ($request->user()->hasPermission(['admin.contracts.show'])) {
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
                'order_state_id',
                'estado_orden'
            ])
            ->where('order_state_id', '!=', 5) //filtra 5-Orden de Ejecución Anulada
            ->orderBy('contratista')
            ->orderBy('nombre_fiscal')
            ->orderBy('localidad')
            ->get();
        // }

        $view = View::make('reports.orders', compact('orders', 'nombreMetodo'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
        return $pdf->stream('ORDENES DE EJECUCION' . '.pdf');
    }

    // MUESTRA REPORTE CON GRÁFICOS
    public function showChart()
    {
        $summary = DB::table('vista_totals_123')->first();
        $summary2 = DB::table('vista_totals_456')->first();
        $summary3 = DB::table('vista_totals_789')->get();
        $summary4 = DB::table('vista_totals_800')->get();

        $summary4Json = $summary4->map(function ($item) {
            return [
                'provider_name' => $item->provider_name,
                'order_state_name' => $item->order_state_name,
                'total_orders' => $item->total_orders,
            ];
        });
        return view('reports.contracts_totals', compact('summary', 'summary2', 'summary3', 'summary4', 'summary4Json'));
    }


    // MUESTRA UNA ORDEN DE EJECUCIÓN EN ESPECÍFICO
    public function generarContracts10(Request $request, $order_id)
    {

        // if ($request->user()->hasPermission(['orders.reports.show'])) {
        //Donde contracts es una vista
        $user = auth()->user();
        $data = ['userName' => $user->name]; // Pasar el nombre del usuario           

        $contracts0 = DB::table('vista_encab_orden')
            ->select([
                'id2',
                'description2',
                'id',
                'component_code',
                'number',
                'number_year',
                'batch',
                'id1',
                'description',
                'locality',
                'id3',
                'description3',
                'id4',
                'description4',
                'code',
                'description1',
                'reference',
                'comments'
            ])
            ->where('id', '=', $order_id)
            ->orderBy('id')
            ->get();

        $contracts1 = DB::table('vista_full')
            ->select(DB::raw('DISTINCT ON (orders_id) 
                    orders_id, orders_number, contracts_description, contracts_iddncp, contracts_number_year,
                    providers_description,orders_number,orders_references, districts_description,
                    creator_user_id, fiscal_ci,fiscal_name, fiscal_lastname, departments_description,
                    orders_locality,orders_date, dependencies_description,sign_date,components_code,
                    components_description,orders_total_amount,modalities_description,orders_comments, 
                    orders_plazo,minim_amount,fiscal4_id,fiscal4_date,contract_admin_id,batch, order_states_id'))
            ->where('orders_id', '=', $order_id)
            ->orderBy('orders_id')
            ->get();


        $contracts2 = DB::table('vista_full_rep10') //vista que muestra los datos
            ->select([
                'sub_items_oi_description',
                'items_orders_item_number',
                'rubros_code',
                'rubros_description',
                'items_orders_quantity',
                'order_presentations_description',
                'items_orders_unit_price_mo',
                'items_orders_unit_price_mat'
            ])
            ->where('orders_id', '=', $order_id)
            ->Orderby('items_orders_id')
            ->get();
        // }

        $view = View::make('reports.contracts_items10', compact('contracts0', 'contracts1', 'contracts2', 'user'))->render();
        // $view = View::make('reports.contracts_items', compact('contracts1', 'contracts2', 'contracts3'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        // $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
        return $pdf->stream('ORDEN DE EJECUCIÓN' . '.pdf');
    }


    // Para mostrar todos los llamados que tienen contratos
    public function generarContracts(Request $request, $contract_id)
    {
        //No filtra por permiso si es rol admin y uoc2
        if ($request->user()->hasPermission(['admin.contracts.show'])) {
            //Donde contracts es una vista
            $contracts1 = DB::table('vista_contracts') //vista que muestra los datos
                ->select([
                    'iddncp',
                    'llamado',
                    'number_year',
                    'year_adj',
                    'estado',
                    'modalidad',
                    'tipo_contrato',
                    'total_amount',
                    'comentarios',
                    'contratista',
                    'dependencia',
                    'dependency_id'
                ])
                ->where('contract_id', '=', $contract_id)
                ->get();


            $contracts2 = DB::table('vista_contracts_full')
                ->select(DB::raw('DISTINCT ON (polizas) polizas, number_policy, tipo_contrato, item_from, item_to, amount, comments, contratista, dependencia'))
                ->where('contract_id', '=', $contract_id)
                ->orderBy('polizas')
                ->get();

            //Donde contracts3 muestra endosos (itemawards_histories)
            $contracts3 = DB::table('vista_contracts_full2') //vista que muestra los datos
                ->select([
                    'number_policy',
                    'polizas',
                    'number_policy1',
                    'item_from1',
                    'item_to1',
                    'amount1',
                    'comments1',
                    'contratista',
                    'dependencia',
                    'state1'
                ])
                ->where('contract_id', '=', $contract_id)
                ->whereNotNull('number_policy1')
                // ->where('monto_adjudica', null)
                ->get();
        } else {
            //Donde contracts es una vista
            $contracts1 = DB::table('vista_contracts') //vista que muestra los datos
                ->select([
                    'iddncp',
                    'llamado',
                    'number_year',
                    'year_adj',
                    'estado',
                    'modalidad',
                    'tipo_contrato',
                    'total_amount',
                    'comentarios',
                    'contratista',
                    'dependencia',
                    'dependency_id'
                ])
                ->where('contract_id', '=', $contract_id)
                // ->where('dependency_id', $request->user()->dependency_id)//filtra por dependencia que generó la info
                ->get();

            //Donde contracts_full es una vista
            // $contracts2 = DB::table('vista_contracts_full')//vista que muestra los datos
            // ->select (['polizas', 'number_policy','tipo_contrato','item_from','item_to',
            // 'amount', 'comments', 'contratista', 'dependencia'])
            // ->where('contract_id', '=', $contract_id)
            // ->where('dependency_id', $request->user()->dependency_id)//filtra por dependencia que generó la info
            // ->get();
            $contracts2 = DB::table('vista_contracts_full')
                ->select(DB::raw('DISTINCT ON (polizas) polizas, number_policy, tipo_contrato, item_from, item_to, amount, comments, contratista, dependencia'))
                ->where('contract_id', '=', $contract_id)
                ->where('dependency_id', $request->user()->dependency_id) //filtra por dependencia que generó la info
                ->orderBy('polizas')
                ->get();

            //Donde contracts3 muestra endosos (itemawards_histories)
            $contracts3 = DB::table('vista_contracts_full2') //vista que muestra los datos
                ->select([
                    'number_policy',
                    'polizas',
                    'number_policy',
                    'number_policy1',
                    'item_from1',
                    'item_to1',
                    'amount1',
                    'comments1',
                    'contratista',
                    'dependencia',
                    'state1'
                ])
                ->where('contract_id', '=', $contract_id)
                ->whereNotNull('number_policy1')
                ->where('dependency_id', $request->user()->dependency_id) //filtra por dependencia que generó la info
                ->get();
        }

        $view = View::make('reports.contracts_items3', compact('contracts1', 'contracts2', 'contracts3'))->render();
        // $view = View::make('reports.contracts_items', compact('contracts1', 'contracts2', 'contracts3'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        // $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
        return $pdf->stream('LLAMADO-POLIZAS-ENDOSOS' . '.pdf');
    }

    public function generarContracts0(Request $request)
    {
        //capturamos el nombre del método para poder cambiar el título del reporte en la vista
        $nombreMetodo = __METHOD__;

        //No filtra por permiso si es rol admin y uoc2
        if ($request->user()->hasPermission(['admin.contracts.show'])) {
            $contracts = DB::table('vista_contracts') //vista que muestra los datos
                ->select([
                    'llamado',
                    'iddncp',
                    'number_year',
                    'year_adj',
                    'sign_date',
                    'contratista',
                    'estado',
                    'code',
                    'modalidad',
                    'org_financ',
                    'tipo_contrato',
                    'contract_begin_date',
                    'contract_end_date',
                    'total_amount',
                    'comentarios',
                    'dependencia'
                ])
                ->get();
        } else {
            $contracts = DB::table('vista_contracts') //vista que muestra los datos
                ->select([
                    'llamado',
                    'iddncp',
                    'number_year',
                    'year_adj',
                    'sign_date',
                    'contratista',
                    'estado',
                    'code',
                    'modalidad',
                    'org_financ',
                    'tipo_contrato',
                    'contract_begin_date',
                    'contract_end_date',
                    'total_amount',
                    'comentarios',
                    'dependencia'
                ])
                ->where('dependency_id', $request->user()->dependency_id) //filtra por dependencia que generó la info
                ->get();
        }

        $view = View::make('reports.contracts', compact('contracts', 'nombreMetodo'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
        return $pdf->stream('LLAMADO-CONTRATOS' . '.pdf');
    }

    // Para mostrar todos los llamados que estan en curso (estado = 1)
    public function generarContracts1(Request $request)
    {
        //capturamos el nombre del método para poder cambiar el título del reporte en la vista
        $nombreMetodo = __METHOD__;

        //No filtra por permiso si es rol admin y uoc2
        if ($request->user()->hasPermission(['admin.contracts.show'])) {
            $contracts = DB::table('vista_contracts') //vista que muestra los datos
                ->select([
                    'llamado',
                    'iddncp',
                    'number_year',
                    'year_adj',
                    'sign_date',
                    'contratista',
                    'estado',
                    'code',
                    'modalidad',
                    'org_financ',
                    'tipo_contrato',
                    'contract_begin_date',
                    'contract_end_date',
                    'total_amount',
                    'comentarios',
                    'dependencia'
                ])
                ->where('state_id', '=', 1)
                ->get();
        } else {
            $contracts = DB::table('vista_contracts') //vista que muestra los datos
                ->select([
                    'llamado',
                    'iddncp',
                    'number_year',
                    'year_adj',
                    'sign_date',
                    'contratista',
                    'estado',
                    'code',
                    'modalidad',
                    'org_financ',
                    'tipo_contrato',
                    'contract_begin_date',
                    'contract_end_date',
                    'total_amount',
                    'comentarios',
                    'dependencia'
                ])
                ->where('state_id', '=', 1)
                ->where('dependency_id', $request->user()->dependency_id) //filtra por dependencia que generó la info
                ->get();
        }

        $view = View::make('reports.contracts', compact('contracts', 'nombreMetodo'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
        return $pdf->stream('LLAMADO-CONTRATOS EN CURSO' . '.pdf');
    }

    // Para mostrar todos los llamados que estan rescindido (estado = 2)
    public function generarContracts2(Request $request)
    {
        //capturamos el nombre del método para poder cambiar el título del reporte en la vista
        $nombreMetodo = __METHOD__;


        //No filtra por permiso si es rol admin y uoc2
        if ($request->user()->hasPermission(['admin.contracts.show'])) {
            $contracts = DB::table('vista_contracts') //vista que muestra los datos
                ->select([
                    'llamado',
                    'iddncp',
                    'number_year',
                    'year_adj',
                    'sign_date',
                    'contratista',
                    'estado',
                    'code',
                    'modalidad',
                    'org_financ',
                    'tipo_contrato',
                    'contract_begin_date',
                    'contract_end_date',
                    'total_amount',
                    'comentarios',
                    'dependencia'
                ])
                ->where('state_id', '=', 2)
                ->get();
        } else {
            $contracts = DB::table('vista_contracts') //vista que muestra los datos
                ->select([
                    'llamado',
                    'iddncp',
                    'number_year',
                    'year_adj',
                    'sign_date',
                    'contratista',
                    'estado',
                    'code',
                    'modalidad',
                    'org_financ',
                    'tipo_contrato',
                    'contract_begin_date',
                    'contract_end_date',
                    'total_amount',
                    'comentarios',
                    'dependencia'
                ])
                ->where('state_id', '=', 2)
                ->where('dependency_id', $request->user()->dependency_id) //filtra por dependencia que generó la info
                ->get();
        }

        // CONTROLA SI HAY DATOS PARA GENERAR REPORTE
        $val = $contracts->count();

        if ($val > 0) {
            $view = View::make('reports.contracts', compact('contracts', 'nombreMetodo'))->render();
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($view);
            $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
            return $pdf->stream('LLAMADO-CONTRATOS CERRADOS' . '.pdf');
        } else {
            return redirect()->route('contracts.index')->with('error', 'NO HAY DATOS PARA GENERAR EL REPORTE');
        }
    }

    // Para mostrar todos los llamados que estan cerrados (estado = 3)
    public function generarContracts3(Request $request)
    {
        //capturamos el nombre del método para poder cambiar el título del reporte en la vista
        $nombreMetodo = __METHOD__;

        if ($request->user()->hasPermission(['admin.contracts.show'])) {
            $contracts = DB::table('vista_contracts') //vista que muestra los datos
                ->select([
                    'llamado',
                    'iddncp',
                    'number_year',
                    'year_adj',
                    'sign_date',
                    'contratista',
                    'estado',
                    'code',
                    'modalidad',
                    'org_financ',
                    'tipo_contrato',
                    'contract_begin_date',
                    'contract_end_date',
                    'total_amount',
                    'comentarios',
                    'dependencia'
                ])
                ->where('state_id', '=', 3)
                ->get();
        } else {
            $contracts = DB::table('vista_contracts') //vista que muestra los datos
                ->select([
                    'llamado',
                    'iddncp',
                    'number_year',
                    'year_adj',
                    'sign_date',
                    'contratista',
                    'estado',
                    'code',
                    'modalidad',
                    'org_financ',
                    'tipo_contrato',
                    'contract_begin_date',
                    'contract_end_date',
                    'total_amount',
                    'comentarios',
                    'dependencia'
                ])
                ->where('state_id', '=', 3)
                ->where('dependency_id', $request->user()->dependency_id) //filtra por dependencia que generó la info
                ->get();
        }

        // CONTROLA SI HAY DATOS PARA GENERAR REPORTE
        $val = $contracts->count();

        if ($val > 0) {
            $view = View::make('reports.contracts', compact('contracts', 'nombreMetodo'))->render();
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($view);
            $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
            return $pdf->stream('LLAMADOS-CONTRATOS RESCINDIDOS' . '.pdf');
        } else {
            return redirect()->route('contracts.index')->with('error', 'NO HAY DATOS PARA GENERAR EL REPORTE');
        }
    }

    // Para mostrar todos los llamados que estan en proceso de rescisión (estado = 6)
    public function generarContracts6(Request $request)
    {
        //capturamos el nombre del método para poder cambiar el título del reporte en la vista
        $nombreMetodo = __METHOD__;

        if ($request->user()->hasPermission(['admin.contracts.show'])) {
            $contracts = DB::table('vista_contracts') //vista que muestra los datos
                ->select([
                    'llamado',
                    'iddncp',
                    'number_year',
                    'year_adj',
                    'sign_date',
                    'contratista',
                    'estado',
                    'code',
                    'modalidad',
                    'org_financ',
                    'tipo_contrato',
                    'contract_begin_date',
                    'contract_end_date',
                    'total_amount',
                    'comentarios',
                    'dependencia'
                ])
                ->where('state_id', '=', 6)
                ->get();
        } else {
            $contracts = DB::table('vista_contracts') //vista que muestra los datos
                ->select([
                    'llamado',
                    'iddncp',
                    'number_year',
                    'year_adj',
                    'sign_date',
                    'contratista',
                    'estado',
                    'code',
                    'modalidad',
                    'org_financ',
                    'tipo_contrato',
                    'contract_begin_date',
                    'contract_end_date',
                    'total_amount',
                    'comentarios',
                    'dependencia'
                ])
                ->where('state_id', '=', 6)
                ->where('dependency_id', $request->user()->dependency_id) //filtra por dependencia que generó la info
                ->get();
        }

        // CONTROLA SI HAY DATOS PARA GENERAR REPORTE
        $val = $contracts->count();

        if ($val > 0) {
            $view = View::make('reports.contracts', compact('contracts', 'nombreMetodo'))->render();
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($view);
            $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
            return $pdf->stream('LLAMADO-CONTRATOS EN PROCESO DE RESCISIÓN' . '.pdf');
        } else {
            return redirect()->route('contracts.index')->with('error', 'NO HAY DATOS PARA GENERAR EL REPORTE');
        }
    }

    // // Para mostrar detalles de las pólizas
    // public function generarContracts4(Request $request)
    // {
    //     //capturamos el nombre del método para poder cambiar el título del reporte en la vista
    //     $nombreMetodo = __METHOD__;

    //     //Donde contracts es una vista
    //     $contracts = DB::table('vista_contracts')//vista que muestra los datos
    //     ->select(['llamado','iddncp',
    //     'number_year',
    //     'contratista',
    //     'estado',
    //     'total_amount',
    //     'advance_validity_from',
    //     'advance_validity_to',
    //     'fidelity_validity_from',
    //     'fidelity_validity_to',
    //     'accidents_validity_from',
    //     'accidents_validity_to',
    //     'risks_validity_from',
    //     'risks_validity_to',
    //     'civil_resp_validity_from',
    //     'civil_resp_validity_to',
    //     'comentarios'])
    //     // ->where('state_id', '=', 3)
    //     ->where('dependency_id', $request->user()->dependency_id)//filtra por dependencia que generó la info
    //     ->get();

    //     $view = View::make('reports.contracts_polizas', compact('contracts', 'nombreMetodo'))->render();
    //     $pdf = App::make('dompdf.wrapper');
    //     $pdf->loadHTML($view);
    //     $pdf->setPaper('A4', 'landscape');//coloca en apaisado
    //     return $pdf->stream('LLAMADOS-DETALLES DE PÓLIZAS'.'.pdf');
    // }

    // PARA MOSTRAR ALERTAS DE VENCIMIENTOS DE LAS PÓLIZAS Y ENDOSOS
    public function generarContracts5(Request $request)
    {
        if ($request->user()->hasPermission(['admin.contracts.show'])) {
            //filtra y muestra vista para elegir reporte en pdf por dependencia
            $contracts_poli = DB::table('vista_contracts_days_full')
                ->selectRaw('DISTINCT ON (dependencia) iddncp, number_year, dependency_id, dependencia')
                ->whereIn('state_id', [1]) //1-En curso
                ->where([
                    ['dias_advance', '<=', 0],
                    ['dias_advance_endo', null]
                ])
                ->orWhere([
                    ['dias_advance', '<=', 0],
                    ['dias_advance_endo', '<=', 0]
                ])
                ->get();
            //MUESTRA VISTA PARA PODER ELEGIR LA DEPENDENCIA PARA MOSTRAR EL PDF DE ALERTA
            return view('reports.contracts_vctos_polizas_menu', compact('contracts_poli'));
        } else {
            //muestra pdf reporte en pdf de dependencia del usuario
            $contracts_poli = DB::table('vista_contracts_vctos_poli') //vista que muestra los datos
                ->select([
                    'iddncp',
                    'number_year',
                    'contratista',
                    'tipo_contrato',
                    'total_amount',
                    'fecha_tope_advance',
                    'vcto_adv',
                    'dias_advance',
                    'llamado',
                    'polizas',
                    'number_policy',
                    'modalidad',
                    'dependencia',
                    'comments',
                    'award_type_id',
                    'award_type_description',
                    'state_id',
                ])
                ->where('dias_advance', '<=', 0)
                ->where('dependency_id', $request->user()->dependency_id) //filtra por dependencia que generó la info
                ->get();

            $contracts_endo = DB::table('vista_contracts_vctos_endo') //vista que muestra los datos
                ->select([
                    'iddncp',
                    'number_year',
                    'contratista',
                    'tipo_contrato',
                    'amount_endoso',
                    'fecha_tope_advance_endo',
                    'vcto_adv_endo',
                    'dias_advance_endo',
                    'llamado',
                    'polizas',
                    'number_policy_endoso',
                    'modalidad',
                    'dependencia',
                    'comments_endoso',
                    'state_endoso',
                    'award_type_id',
                    'award_type_description',
                    'state_id',
                ])
                ->where('dias_advance_endo', '<=', 0)
                ->where('dependency_id', $request->user()->dependency_id) //filtra por dependencia que generó la info
                ->get();

            $view = View::make('reports.contracts_vctos_polizas', compact('contracts_poli', 'contracts_endo',))->render();
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($view);
            $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
            return $pdf->stream('DETALLES ALERTA VENCIMIENTOS DE PÓLIZAS' . '.pdf');
        }
    }

    // PARA MOSTRAR ALERTAS DE VENCIMIENTOS DE LAS PÓLIZAS Y ENDOSOS DE TODAS LAS DEPENDENCIAS DESDE UOC
    public function generarContracts7(Request $request, $dependency_id)
    {
        if ($request->user()->hasPermission(['admin.contracts.show'])) {
            //muestra pdf reporte en pdf de dependencia del usuario
            $contracts_poli = DB::table('vista_contracts_vctos_poli') //vista que muestra los datos
                ->select([
                    'iddncp',
                    'number_year',
                    'contratista',
                    'tipo_contrato',
                    'total_amount',
                    'fecha_tope_advance',
                    'vcto_adv',
                    'dias_advance',
                    'llamado',
                    'polizas',
                    'number_policy',
                    'modalidad',
                    'dependencia',
                    'comments',
                    'award_type_id',
                    'award_type_description',
                    'state_id',
                ])
                ->where('dias_advance', '<=', 0)
                ->where('dependency_id', '=', $dependency_id)
                ->get();

            $contracts_endo = DB::table('vista_contracts_vctos_endo') //vista que muestra los datos
                ->select([
                    'iddncp',
                    'number_year',
                    'contratista',
                    'tipo_contrato',
                    'amount_endoso',
                    'fecha_tope_advance_endo',
                    'vcto_adv_endo',
                    'dias_advance_endo',
                    'llamado',
                    'polizas',
                    'number_policy_endoso',
                    'modalidad',
                    'dependencia',
                    'comments_endoso',
                    'state_endoso',
                    'award_type_id',
                    'award_type_description',
                    'state_id',
                ])
                ->where('dias_advance_endo', '<=', 0)
                ->where('dependency_id', '=', $dependency_id)
                ->get();

            $view = View::make('reports.contracts_vctos_polizas', compact('contracts_poli', 'contracts_endo',))->render();
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($view);
            $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
            return $pdf->stream('DETALLES ALERTA VENCIMIENTOS DE PÓLIZAS' . '.pdf');
        }
        // Mail::to('rogoma700@gmail.com')->send(new enviar_alertas($contenido));
    }

    // POR DEPENDENCIAS GENERA PDF - PARA MOSTRAR ALERTAS DE VENCIMIENTOS DE LAS PÓLIZAS Y ENDOSOS
    public function generarContracts9(Request $request)
    {
        $contracts_poli = DB::table('vista_contracts_vctos_poli')
            ->selectRaw('DISTINCT ON (dependencia) iddncp, number_year, dependency_id, dependencia')
            ->whereIn('state_id', [1]) //1-En curso
            ->where([
                ['dias_advance', '<=', 0],
                ['dias_advance_endo', null]
            ])
            ->orWhere([
                ['dias_advance', '<=', 0],
                ['dias_advance_endo', '<=', 0]
            ])
            ->get();

        $contracts_endo = DB::table('vista_contracts_vctos_endo') //vista que muestra los datos
            ->selectRaw('DISTINCT ON (dependencia) iddncp, number_year, dependency_id, dependencia')
            ->whereIn('state_id', [1]) //1-En curso
            ->where([
                ['dias_advance', '<=', 0],
                ['dias_advance_endo', null]
            ])
            ->orWhere([
                ['dias_advance', '<=', 0],
                ['dias_advance_endo', '<=', 0]
            ])
            ->get();

        return view('reports.contracts_vctos_polizas_menu', compact('contracts_poli', 'contracts_endo'));
    }


    // Para mostrar llamados de Compras Menores
    public function generarPanelMinor()
    {
        //Donde cargos es una vista
        $orders = DB::table('vista_orders') //vista que muestra los datos
            ->select([
                'number',
                'dncp_pac_id',
                'order_description',
                'modality_code',
                'exp_obj_code',
                'total_amount',
                'cdp_number',
                'cdp_date',
                'order_state_description',
                'begin_date',
                'covid',
                'year'
            ])
            ->wherein('modality_id', [4, 5, 6])
            ->get();
        $view = View::make('reports.panel_minor', compact('orders'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
        return $pdf->stream('PROCESOS COMPRAS MENORES' . '.pdf');
    }

    // Para mostrar llamados de Compras Vía Excepción
    public function generarPanelException()
    {
        //Donde cargos es una vista
        $orders = DB::table('vista_orders') //vista que muestra los datos
            ->select([
                'number',
                'dncp_pac_id',
                'order_description',
                'modality_code',
                'exp_obj_code',
                'total_amount',
                'cdp_number',
                'cdp_date',
                'order_state_description',
                'begin_date',
                'covid',
                'year'
            ])
            ->wherein('modality_id', [9, 10, 28])
            ->get();
        $view = View::make('reports.panel_exception', compact('orders'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
        return $pdf->stream('PROCESOS COMPRAS VIA EXCEPCIÓN' . '.pdf');
    }

    // Para mostrar Procesos de Pedidos sin filtrar por dependencia que están en proceso aún no adjudicados
    public function generarPanelUta()
    {
        //Donde cargos es una vista
        $orders = DB::table('vista_uta') //vista que muestra los datos
            ->select([
                'number',
                'dncp_pac_id',
                'order_description',
                'modality_code',
                'exp_obj_code',
                'total_amount',
                'cdp_number',
                'cdp_date',
                'order_state_description',
                'begin_date',
                'covid',
                'year',
                'provider',
                'contract_number',
                'contract_date',
                'cc_number',
                'cc_date',
                'monto_adjudica'
            ])
            ->where('monto_adjudica', null)
            ->where('actual_state', '>', 0)
            ->orderBy('provider', 'asc')
            ->get();


        // if ($val = $orders->count()){
        //     var_dump($val);
        // }

        //SE CUENTAN LOS REGISTROS, SI SUPERA MUESTRA COMO HTML, SINO MUESTRA COMO PDF
        $val = $orders->count();

        if ($val > 500) {
            return view('reports.panel_uta_ht', compact('orders'));
        } else {
            $view = View::make('reports.panel_uta', compact('orders'))->render();
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($view);
            $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
            return $pdf->stream('LLAMADOS EN CURSO' . '.pdf');
        }

        // PARA MOSTRAR COMO PDF
        // $view = View::make('reports.panel_uta', compact('orders'))->render();
        // $pdf = App::make('dompdf.wrapper');
        // $pdf->loadHTML($view);
        // $pdf->setPaper('A4', 'landscape');//coloca en apaisado
        // return $pdf->stream('LLAMADOS EN CURSO'.'.pdf');

        // PARA MOSTRAR COMO VISTA EN HTML
        return view('reports.panel_uta', compact('orders'));
    }

    // Para mostrar Procesos de Pedidos sin filtrar por dependencia que ya están adjudicados
    public function generarPanelUta2()
    {
        //Donde cargos es una vista
        $orders = DB::table('vista_uta') //vista que muestra los datos
            ->select([
                'number',
                'dncp_pac_id',
                'order_description',
                'modality_code',
                'exp_obj_code',
                'total_amount',
                'cdp_number',
                'cdp_date',
                'order_state_description',
                'begin_date',
                'covid',
                'year',
                'provider',
                'contract_number',
                'contract_date',
                'cc_number',
                'cc_date',
                'monto_adjudica'
            ])
            ->where('monto_adjudica', '>', 0)
            ->orderBy('provider', 'asc')
            ->get();

        $val = $orders->count();

        if ($val > 500) {
            return view('reports.panel_uta', compact('orders'));
        } else {
            $view = View::make('reports.panel_uta', compact('orders'))->render();
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($view);
            $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
            return $pdf->stream('LLAMADOS ADJUDICADOS' . '.pdf');
        }

        // $view = View::make('reports.panel_uta', compact('orders'))->render();
        // $pdf = App::make('dompdf.wrapper');
        // $pdf->loadHTML($view);
        // $pdf->setPaper('A4', 'landscape');//coloca en apaisado
        // return $pdf->stream('LLAMADOS ADJUDICADOS'.'.pdf');
    }

    // Para mostrar Procesos de Pedidos Anulados
    public function generarPanelUta3()
    {
        //Donde cargos es una vista
        $orders = DB::table('vista_uta') //vista que muestra los datos
            ->select([
                'number',
                'dncp_pac_id',
                'order_description',
                'modality_code',
                'exp_obj_code',
                'total_amount',
                'cdp_number',
                'cdp_date',
                'order_state_description',
                'begin_date',
                'covid',
                'year',
                'provider',
                'contract_number',
                'contract_date',
                'cc_number',
                'cc_date',
                'monto_adjudica'
            ])
            ->where('actual_state', '=', 0)
            ->orderBy('provider', 'asc')
            ->get();

        $val = $orders->count();

        if ($val > 500) {
            return view('reports.panel_uta', compact('orders'));
        } else {
            $view = View::make('reports.panel_uta', compact('orders'))->render();
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($view);
            $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
            return $pdf->stream('PEDIDOS ANULADOS' . '.pdf');
        }
    }


    // Para mostrar Procesos a usuarios de PEDIDOS
    public function generarPanelPedido($dependency)
    {
        //Donde cargos es una vista
        $orders = DB::table('vista_uta') //vista que muestra los datos
            ->select([
                'number',
                'dncp_pac_id',
                'order_description',
                'modality_code',
                'exp_obj_code',
                'total_amount',
                'cdp_number',
                'cdp_date',
                'order_state_description',
                'begin_date',
                'covid',
                'year',
                'provider',
                'contract_number',
                'contract_date',
                'cc_number',
                'cc_date',
                'monto_adjudica'
            ])
            ->where('dependency_id', '=', $dependency)
            ->where('monto_adjudica', null)
            ->where('actual_state', '>', 0)
            ->orderBy('provider', 'asc')
            ->get();

        $val = $orders->count();

        if ($val > 500) {
            return view('reports.panel_uta', compact('orders'));
        } else {
            $view = View::make('reports.panel_uta', compact('orders'))->render();
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($view);
            $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
            return $pdf->stream('LLAMADOS EN CURSO' . '.pdf');
        }
    }

    // Para mostrar Procesos a usuarios de PEDIDOS
    public function generarPanelPedido2($dependency)
    {
        //Donde cargos es una vista
        $orders = DB::table('vista_uta') //vista que muestra los datos
            ->select([
                'number',
                'dncp_pac_id',
                'order_description',
                'modality_code',
                'exp_obj_code',
                'total_amount',
                'cdp_number',
                'cdp_date',
                'order_state_description',
                'begin_date',
                'covid',
                'year',
                'provider',
                'contract_number',
                'contract_date',
                'cc_number',
                'cc_date',
                'monto_adjudica'
            ])
            ->where('dependency_id', '=', $dependency)
            ->where('monto_adjudica', '>', 0)
            ->orderBy('provider', 'asc')
            ->get();

        $val = $orders->count();

        if ($val > 500) {
            return view('reports.panel_uta', compact('orders'));
        } else {
            $view = View::make('reports.panel_uta', compact('orders'))->render();
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($view);
            $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
            return $pdf->stream('LLAMADOS EN CURSO' . '.pdf');
        }
    }

    public function pdfTipres()
    {
        $tipres = DB::table('order_presentations')
            ->select(['id', 'description'])
            ->orderBy('description', 'asc')
            ->get();
        $view = View::make('reports.tipres', compact('tipres'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('Tipres' . '.pdf');
    }

    public function pdfTipresUnid()
    {
        $tipresunid = DB::table('order_measurement_units')
            ->select(['id', 'description'])
            ->orderBy('description', 'asc')
            ->get();
        //
        $view = View::make('reports.tipresunid', compact('tipresunid'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('TipresUnid' . '.pdf');
    }

    public function pdfPrefs($order_id)
    {
        $prefs = DB::table('vista_award')
            ->select(['nombre_pedido', 'dncp_pac_id', 'proveedor', 'monto', 'nro_item', 'nro_llamado', 'codc5', 'descrip_cc5'])
            ->where('order_id', '=', $order_id)
            ->orderBy('nro_item', 'asc')
            ->orderBy('proveedor', 'asc')
            ->orderBy('dncp_pac_id', 'asc')
            ->get();
        //
        $view = View::make('reports.prefs', compact('prefs'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('Prefs' . '.pdf');
    }

    //     public function pdfUsers()
    //     {
    //         // Donde users es una vista
    //         // $users = DB::table('vista_users')
    //         // ->select(['document','name','lastname','dependency','rol','position','permission'])
    //         // ->get();

    //         // Donde users2 es una vista
    //         $users = DB::table('vista_users2')
    //         ->select(['document','name','lastname','description'])
    //         ->get();
    // //
    //         $view = View::make('reports.users', compact('users'))->render();
    //         $pdf = App::make('dompdf.wrapper');
    //         $pdf->loadHTML($view);
    //         return $pdf->stream('Users'.'.pdf');
    //     }

    public function pdfUsers2()
    {
        //SE ORDENA POR CI SE CASTEA VARCHAR A INTEGER EN ORDERBYRAW
        $users = DB::table('vista_users_full')
            ->select(['nombre', 'apellido', 'ci', 'email', 'dependencia', 'cargo', 'rol', 'nro_uoc', 'sicp', 'state'])
            ->orderByRaw('ci::int')
            ->get();

        // PARA MOSTRAR COMO PDF
        $view = View::make('reports.users2', compact('users'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('Users' . '.pdf');

        // PARA MOSTRAR COMO VISTA EN HTML
        // return view('reports.users2', compact('users'));

    }

    //MUESTRA DISTRITOS
    public function pdfDistricts()
    {
        //SE ORDENA POR CI SE CASTEA VARCHAR A INTEGER EN ORDERBYRAW
        $districts = DB::table('vista_div_politica')
            ->select('department_id', 'departamento', 'distrito')
            ->distinct()
            ->orderBy('department_id')
            ->get();

        // PARA MOSTRAR COMO PDF
        $view = View::make('reports.districts', compact('districts'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('Distritos' . '.pdf');

        // PARA MOSTRAR COMO VISTA EN HTML
        // return view('reports.users2', compact('users'));

    }

    //MUESTRA LOCALIDADES
    public function pdfLocalities()
    {
        //SE ORDENA POR CI SE CASTEA VARCHAR A INTEGER EN ORDERBYRAW
        $localities = DB::table('vista_div_politica')
            ->select(['departamento', 'distrito', 'localidad'])
            ->orderByRaw('id_departamento, distrito, localidad')
            ->get();

        // PARA MOSTRAR COMO PDF
        $view = View::make('reports.localities', compact('localities'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('Localidades' . '.pdf');

        // PARA MOSTRAR COMO VISTA EN HTML
        // return view('reports.users2', compact('users'));

    }

    //MUESTRA PROVIDERS
    public function pdfContratistas()
    {
        //SE ORDENA POR CI SE CASTEA VARCHAR A INTEGER EN ORDERBYRAW
        $providers = DB::table('providers')
            ->select(['description', 'ruc', 'telefono', 'email_oferta', 'email_ocompra', 'representante'])
            ->orderBy('description')
            ->get();

        // PARA MOSTRAR COMO PDF
        $view = View::make('reports.providers', compact('providers'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('Contratistas' . '.pdf');

        // PARA MOSTRAR COMO VISTA EN HTML
        // return view('reports.users2', compact('users'));

    }

    //MUESTRA DEPENDENCIAS
    public function dependencies()
    {
        $dependencies = DB::table('vista_dependencies_full')
            ->select(['sup_dependencia', 'dependencia', 'tipo_dependencia', 'tipo_uoc', 'uoc_number', 'sicp'])
            ->get();

        // PARA MOSTRAR COMO PDF
        $view = View::make('reports.dependencies', compact('dependencies'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('Dependencias' . '.pdf');

        // PARA MOSTRAR COMO VISTA EN HTML
        // return view('reports.users2', compact('users'));

    }


    public function generarForm1($order_id)
    {
        //Donde cargos es una vista
        $orders = DB::table('vista_pdf_form') //vista que muestra los datos
            ->select([
                'order_id',
                'number',
                'responsible',
                'dependencies_description',
                'modalities_code',
                'orders_description',
                'begin_date',
                'code_supexp',
                'pt',
                'pc',
                'activity_code',
                'ff',
                'fo',
                'total_amount',
                'anio2022',
                'monto_2022',
                'anio2023',
                'monto_2023',
                'anio2024',
                'monto_2024',
                'anio2025',
                'monto_2025',
                'year',
                'og1',
                'og2',
                'og3',
                'og4',
                'og5',
                'og6'
            ])
            ->where('order_id', '=', $order_id)
            ->get();

        $anio = date("Y");
        //$anio= date(Y);
        $view = View::make('reports.form1', compact('orders'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
        return $pdf->stream('Formulario1' . '.pdf');
    }

    public function generarForm2($order_id)
    {
        //Donde cargos es una vista
        $orders = DB::table('vista_orders') //vista que muestra los datos
            ->select([
                'dependency',
                'order_description',
                'ad_referendum',
                'plurianualidad',
                'system_awarded_by',
                'code_supexp',
                'exp_obj_code',
                'program_type_code',
                'program_code',
                'activity_code',
                'ff_code',
                'of_code',
                'total_amount',
                'fonacide',
                'modality_type',
                'catalogs_technical_annexes',
                'alternative_offers',
                'open_contract',
                'period_time',
                'manufacturer_authorization',
                'financial_advance_percentage_amount',
                'technical_specifications',
                'samples',
                'delivery_plan',
                'evaluation_committee_proposal',
                'payment_conditions',
                'contract_guarantee',
                'product_guarantee',
                'contract_administrator',
                'contract_validity_to',
                'additional_technical_documents',
                'additional_qualified_documents',
                'price_sheet',
                'property_title',
                'magnetic_medium',
                'referring_person_data',
                'og1',
                'og2',
                'og3',
                'og4',
                'og5',
                'og6'
            ])
            ->where('order_id', '=', $order_id)
            ->get();

        $view = View::make('reports.form2', compact('orders'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('Formulario2' . '.pdf');
    }

    //Form3 para conrato abierto
    public function generarForm31($order_id)
    {
        //Donde cargos es una vista
        $orders = DB::table('vista_orders_items') //vista que muestra los datos
            ->select([
                'dependency',
                'batch',
                'item_number',
                'code_supexp',
                'exp_obj_code',
                'program_type_code',
                'program_code',
                'activity_code',
                'ff_code',
                'code',
                'level5_catalog_codes_description',
                'technical_specifications',
                'order_presentations_description',
                'order_measurement_units_description',
                'min_quantity',
                'max_quantity',
                'unit_price',
                'total_amount',
                'total_amount_min',
                'quantity'
            ])
            ->where('order_id', '=', $order_id)
            ->get();

        $view = View::make('reports.form31', compact('orders'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
        return $pdf->stream('Formulario3' . '.pdf');

        //return view('reports.form3', compact('orders'));
    }

    //Form3 para conrato cerrado
    public function generarForm3($order_id)
    {
        //Donde cargos es una vista
        $orders = DB::table('vista_orders_items') //vista que muestra los datos
            ->select([
                'dependency',
                'batch',
                'item_number',
                'code_supexp',
                'exp_obj_code',
                'program_type_code',
                'program_code',
                'activity_code',
                'ff_code',
                'code',
                'level5_catalog_codes_description',
                'technical_specifications',
                'order_presentations_description',
                'order_measurement_units_description',
                'min_quantity',
                'max_quantity',
                'unit_price',
                'total_amount',
                'total_amount_min',
                'quantity'
            ])
            ->where('order_id', '=', $order_id)
            ->get();

        $view = View::make('reports.form3', compact('orders'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('A4', 'landscape'); //coloca en apaisado
        return $pdf->stream('Formulario3' . '.pdf');

        //return view('reports.form3', compact('orders'));
    }

    public function generarForm4($order_id)
    {
        $order = Order::find($order_id);
        //Donde cargos es una vista
        $f4_1 = DB::table('vista_form4_1') //vista que muestra los datos
            ->select(['form4_city', 'form4_date', 'providers_description', 'ruc'])
            ->where('order_id', '=', $order_id)
            ->whereIn('request_provider_type', [1]) //EMPRESAS PARTICIPANTES
            ->get();

        $f4_2 = DB::table('vista_form4_2') //vista que muestra los datos
            ->select(['order_id', 'item_number', 'providers_description', 'level5_catalog_codes_description', 'dncp_pac_id', 'amount'])
            ->where('order_id', '=', $order_id)
            ->get();

        // validamos los datos
        // $validator = Validator::make($item, $rules); // Creamos un objeto validator
        // if ($validator->fails()) {
        //     return back()->withErrors($validator)->withInput()->with('fila', $row);
        // }

        // Chequea si existe el código de catálogo5
        // $level5_catalog_code = Level5CatalogCode::where('code', $item['level5_catalog_code'])->get()->first();
        // if(($request->user()->dependency_id == 59)){
        //     return redirect()->route('plannings.show', $order_id)->with('success', 'Ítem modificado correctamente'); // Caso usuario posee rol pedidos
        // }else{
        //     return redirect()->route('orders.show', $order_id)->with('success', 'Ítem modificado correctamente'); // Caso usuario posee rol pedidos
        // }

        if (is_null($f4_2)) {
            // return back()->withErrors($validator)->withInput()->with('fila', $row);
            // $view = View::make('reports.form4', compact('f4_1','f4_2', 'order'))->render();
            // $pdf = App::make('dompdf.wrapper');
            // $pdf->loadHTML($view);
            // //$pdf->setPaper('A4', 'landscape');//coloca en apaisado
            // return $pdf->stream('Formulario4'.'.pdf');
        } else {
            $view = View::make('reports.form4', compact('f4_1', 'f4_2', 'order'))->render();
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($view);
            //$pdf->setPaper('A4', 'landscape');//coloca en apaisado
            return $pdf->stream('Formulario4' . '.pdf');
        }
    }

    //Para mostrar un archivo PDF guardado en el Proyecto
    public function pdfArchivo()
    {
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=Modalidades_DNCP.pdf");
        readfile("files/Modalities.pdf");
    }

    //Para mostrar un archivo PDF guardado en el Proyecto - SECUENCIA DE LICITACIONES
    public function pdfSecuencia()
    {
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=Secuencia LICITACIONES.pdf");
        readfile("files/LICITACIONES.pdf");
    }

    //Para mostrar un archivo PDF guardado en el Proyecto - SECUENCIA DE COMPRAS MENORES
    public function pdfSecuencia2()
    {
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=Secuencia COMPRAS MENORES.pdf");
        readfile("files/COMPRAS MENORES.pdf");
    }

    //Para mostrar un archivo PDF guardado en el Proyecto - SECUENCIA DE CVE
    public function pdfSecuencia3()
    {
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=Secuencia PROCESOS COMPLEMENTARIOS.pdf");
        readfile("files/PROCESOS COMPLEMENTARIOS.pdf");
    }

    //Para mostrar un archivo PDF guardado en el Proyecto - SECUENCIA DE CVE
    public function pdfChange_pass()
    {
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=Usuarios - Cambio de Password.pdf");
        readfile("files/users.pdf");
    }
}
