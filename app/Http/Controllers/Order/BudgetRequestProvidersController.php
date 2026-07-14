<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\Provider;
use App\Models\BudgetRequestProvider;
use App\Models\ItemAwardHistory;

class BudgetRequestProvidersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $index_permissions = ['admin.budget_request_providers.index',
                            'orders.budget_request_providers.index',
                            'process_orders.budget_request_providers.index',
                            'derive_orders.budget_request_providers.index',
                            'plannings.budget_request_providers.index',
                            'tenders.budget_request_providers.index',
                            'minor_purchases.budget_request_providers.index',
                            'exceptions.budget_request_providers.index',
                            'awards.budget_request_providers.index',
                            'admin.items_budget.index'];
        $create_permissions = ['admin.budget_request_providers.create',
                            'orders.budget_request_providers.create',
                            'plannings.budget_request_providers.create',
                            'tenders.budget_request_providers.create',
                            'minor_purchases.budget_request_providers.create',
                            'exceptions.budget_request_providers.create',
                            'awards.budget_request_providers.create',
                            'admin.items_budget.create'];
        $update_permissions = ['admin.budget_request_providers.update',
                            'orders.budget_request_providers.update',
                            'plannings.budget_request_providers.update',
                            'tenders.budget_request_providers.update',
                            'minor_purchases.budget_request_providers.update',
                            'exceptions.budget_request_providers.update',
                            'awards.budget_request_providers.update',
                            'admin.items_budget.update'];

        $this->middleware('checkPermission:'.implode(',',$index_permissions))->only('index'); // Permiso para index 
        $this->middleware('checkPermission:'.implode(',',$create_permissions))->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:'.implode(',',$update_permissions))->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.budget_request_providers.index','process_orders.budget_request_providers.index',
        'derive_orders.budget_request_providers.index','plannings.budget_request_providers.index']) &&
            $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        $providers = $order->providers;
        return view('order.budget_request_providers.index', compact('order', 'providers'));
    }

    /**
     * Show the form for creating a new resource. PARA EMPRESAS COTIZANTES DE REQUIRIENTE
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Definimos la ruta para volver a la visualizacion del pedido
        if( $request->user()->hasPermission(['admin.budget_request_providers.create','orders.budget_request_providers.create']) == TRUE ){       
            $orders_route = route('orders.show', $order_id);
        }

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.budget_request_providers.create','orders.budget_request_providers.create']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        $providers = Provider::all();
        return view('order.budget_request_providers.create', compact('order', 'providers', 'orders_route'));
    }

    /**
     * Show the form for creating a new resource. PARA EMPRESAS COTIZANTES DE PAC
     *
     * @return \Illuminate\Http\Response
     */
    public function create_PAC(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Definimos la ruta para volver a la visualizacion del pedido
        if( $request->user()->hasPermission(['admin.budget_request_providers.create','plannings.budget_request_providers.create']) == TRUE ){
            $orders_route = route('plannings.show', $order_id);        
        }

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.budget_request_providers.create','plannings.budget_request_providers.create']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        $providers = Provider::all();
        return view('order.budget_request_providers.create_providersPAC', compact('order', 'providers', 'orders_route'));
    }

    /**
     * Show the form for creating a new resource. PARA EMPRESAS INVITADAS
     *
     * @return \Illuminate\Http\Response
     */
    public function create_providers_guests(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Definimos la ruta para volver a la visualizacion del pedido
        if( $request->user()->hasPermission(['tenders.budget_request_providers.create']) == TRUE ){
            $orders_route = route('tenders.show', $order_id);
        }else{
            if( $request->user()->hasPermission(['minor_purchases.budget_request_providers.create']) == TRUE ){
                $orders_route = route('minor_purchases.show', $order_id);
            }else{
                $orders_route = route('exceptions.show', $order_id);
            }
        }

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.budget_request_providers.create','tenders.budget_request_providers.create',
        'minor_purchases.budget_request_providers.create','exceptions.budget_request_providers.create']) 
        && $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        $providers = Provider::all();
        return view('order.budget_request_providers.create_providers_guests', compact('order', 'providers', 'orders_route'));
    }

    /**
     * Show the form for creating a new resource. PARA EMPRESAS PARTICIPANTES
     *
     * @return \Illuminate\Http\Response
     */
    public function create_providers_participants(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Definimos la ruta para volver a la visualizacion del pedido
        if( $request->user()->hasPermission(['tenders.budget_request_providers.create']) == TRUE ){
            $orders_route = route('tenders.show', $order_id);
        }else{
            if( $request->user()->hasPermission(['minor_purchases.budget_request_providers.create']) == TRUE ){
                $orders_route = route('minor_purchases.show', $order_id);
            }else{
                $orders_route = route('exceptions.show', $order_id);
            }
        }

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.budget_request_providers.create','tenders.budget_request_providers.create',
        'minor_purchases.budget_request_providers.create','exceptions.budget_request_providers.create']) 
        && $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        $providers = Provider::all();
        return view('order.budget_request_providers.create_providers_participants', compact('order', 'providers', 'orders_route'));
    }

     /**
     * Show the form for creating a new resource. PARA EMPRESAS PARTICIPANTES
     *
     * @return \Illuminate\Http\Response
     */
    public function create_providers_awarded(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Definimos la ruta para volver a la visualizacion del pedido
        if( $request->user()->hasPermission(['awards.budget_request_providers.create']) == TRUE ){
            $orders_route = route('awards.show', $order_id);        
        }

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.budget_request_providers.create','awards.budget_request_providers.create']) 
        && $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        $providers = Provider::all();
        //$budgetRequestProviders = BudgetRequestProvider::all();

        return view('order.budget_request_providers.create_providers_awarded', compact('order', 'providers', 'orders_route'));
    }

    /**
     * Store de empresas cotizantes EMPRESAS COTIZANTES DE REQUIRIENTE request_provider_type = 1
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        $rules = array(
            'providers' => 'array|required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Agregamos las empresas al pedido (guardando en la tabla intermedia budget_request_providers)
        $creator = array('creator_user_id' => $request->user()->id, 
                         'creator_dependency_id' => $request->user()->dependency_id,
                         'request_provider_type' => 1 );
        $order->providers()->attach($request->input('providers'), $creator);

        // Definimos la ruta para volver a la visualizacion del pedido
        return redirect()->route('orders.show', $order_id)->with('success', 'Solicitud de Presupuesto agregada correctamente');

        // if( $request->user()->hasPermission(['plannings.orders.show']) == TRUE ){
        //     return redirect()->route('plannings.show', $order_id)->with('success', 'Solicitud de Presupuesto agregada correctamente');
        // }else{
        //     return redirect()->route('orders.show', $order_id)->with('success', 'Solicitud de Presupuesto agregada correctamente');
        // }
    }

    /**
     * Store de empresas cotizantes EMPRESAS COTIZANTES de PAC request_provider_type = 5
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_PAC(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        $rules = array(
            'providers' => 'array|required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Agregamos las empresas al pedido (guardando en la tabla intermedia budget_request_providers)
        $creator = array('creator_user_id' => $request->user()->id, 
                         'creator_dependency_id' => $request->user()->dependency_id,
                         'request_provider_type' => 5 );
        $order->providers()->attach($request->input('providers'), $creator);

        // Definimos la ruta para volver a la visualizacion del pedido
        return redirect()->route('plannings.show', $order_id)->with('success', 'Solicitud de Presupuesto agregada correctamente');

        // if( $request->user()->hasPermission(['plannings.orders.show']) == TRUE ){
        //     return redirect()->route('plannings.show', $order_id)->with('success', 'Solicitud de Presupuesto agregada correctamente');
        // }else{
        //     return redirect()->route('orders.show', $order_id)->with('success', 'Solicitud de Presupuesto agregada correctamente');
        // }
    }

    /**
     * STORE PARA EMPRESAS INVITADAS request_provider_type = 2
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_providers_guests(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        $rules = array(
            'providers' => 'array|required'

        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Agregamos las empresas al pedido (guardando en la tabla intermedia budget_request_providers)
        $creator = array('creator_user_id' => $request->user()->id, 
                         'creator_dependency_id' => $request->user()->dependency_id,
                         'request_provider_type' => 2 );                        
        $order->providers()->attach($request->input('providers'), $creator);

        
        // Definimos la ruta para volver a la visualizacion de acuerdo a la modalidad del pedido LICITACIONES, COMPRAS MENORES O EXCEPCIONES
        // Para Licitaciones
        if( $request->user()->hasPermission(['tenders.orders.show']) == TRUE ){
            return redirect()->route('tenders.show', $order_id)->with('success', 'Empresa invitada agregada correctamente');
        }else{
            // Para Compras menores
            if( $request->user()->hasPermission(['minor_purchases.orders.show']) == TRUE ){
                return redirect()->route('minor_purchases.show', $order_id)->with('success', 'Empresa invitada agregada correctamente');
            }else{
                // Para Compras vía excepción
                if( $request->user()->hasPermission(['exceptions.orders.show']) == TRUE ){
                    return redirect()->route('exceptions.show', $order_id)->with('success', 'Empresa invitada agregada correctamente');
                }     
            }    
        }
    }

    /**
     * STORE PARA EMPRESAS PARTICIPANTES request_provider_type = 3
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_providers_participants(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        $rules = array(
            'providers' => 'array|required'

        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Agregamos las empresas al pedido (guardando en la tabla intermedia budget_request_providers)
        $creator = array('creator_user_id' => $request->user()->id, 
                         'creator_dependency_id' => $request->user()->dependency_id,
                         'request_provider_type' => 3 );                       
        $order->providers()->attach($request->input('providers'), $creator);

        
        // Definimos la ruta para volver a la visualizacion de acuerdo a la modalidad del pedido LICITACIONES, COMPRAS MENORES O EXCEPCIONES
        // Para Licitaciones
        if( $request->user()->hasPermission(['tenders.orders.show']) == TRUE ){
            return redirect()->route('tenders.show', $order_id)->with('success', 'Empresa participante agregada correctamente');
        }else{
            // Para Compras menores
            if( $request->user()->hasPermission(['minor_purchases.orders.show']) == TRUE ){
                return redirect()->route('minor_purchases.show', $order_id)->with('success', 'Empresa participante agregada correctamente');
            }else{
                // Para Compras vía excepción
                if( $request->user()->hasPermission(['exceptions.orders.show']) == TRUE ){
                    return redirect()->route('exceptions.show', $order_id)->with('success', 'Empresa participante agregada correctamente');
                }     
            }    
        }
    }


    /**
     * Update EMPRESAS COTIZANTES DE REQUIRIENTE
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_providers(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        $rules = array(
            'providers' => 'array|required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Obtenemos los proveedores que no hayan sido cargados por otra dependencia
        // y que no esten relacionados con items
        // $delete_providers = BudgetRequestProvider::where('order_id', $order_id)
        //         ->whereNotIn('provider_id', (array)$request->input('before_providers'))->get()->pluck('provider_id');
        // $order->providers()->detach($delete_providers);
        $delete_providers = BudgetRequestProvider::where('order_id', $order_id)
                ->where('request_provider_type', '=', 1)->get();
        foreach ($delete_providers as $provider) {
            $provider->delete();    // borramos cada proveedor de tipo que no se encuentre en el array pasado
        }

        // preparamos el array de proveedores a relacionar con el pedido                
        $providers = array();
        foreach($request->input('providers') as $provider => $id){
            $providers[$id] = ['creator_user_id' => $request->user()->id, 
                            'creator_dependency_id' => $request->user()->dependency_id,
                            'request_provider_type' => 1];
        }   

        
        // Cargamos los nuevos proveedores del pedido
        $order->providers()->attach($providers);

        // Definimos la ruta para volver a la visualizacion del pedido
        return redirect()->route('orders.show', $order_id)->with('success', 'Solicitud de Presupuesto modificada correctamente');

        // if( $request->user()->hasPermission(['plannings.budget_request_providers.update']) == TRUE ){
        //     return redirect()->route('plannings.show', $order_id)->with('success', 'Solicitud de Presupuesto modificada correctamente');
        // }else{
        //     return redirect()->route('orders.show', $order_id)->with('success', 'Solicitud de Presupuesto modificada correctamente');
        // }
    }    


    /**
     * Update EMPRESAS COTIZANTES DE REQUIRIENTE
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_providersPAC(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        $rules = array(
            'providers' => 'array|required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Obtenemos los proveedores que no hayan sido cargados por otra dependencia
        // y que no esten relacionados con items
        // $delete_providers = BudgetRequestProvider::where('order_id', $order_id)
        //         ->whereNotIn('provider_id', (array)$request->input('before_providers'))->get()->pluck('provider_id');
        // $order->providers()->detach($delete_providers);
        $delete_providers = BudgetRequestProvider::where('order_id', $order_id)
                ->where('request_provider_type', '=', 5)->get();
        foreach ($delete_providers as $provider) {
            $provider->delete();    // borramos cada proveedor de tipo que no se encuentre en el array pasado
        }

        // preparamos el array de proveedores a relacionar con el pedido                
        $providers = array();
        foreach($request->input('providers') as $provider => $id){
            $providers[$id] = ['creator_user_id' => $request->user()->id, 
                            'creator_dependency_id' => $request->user()->dependency_id,
                            'request_provider_type' => 5];
        }   

        
        // Cargamos los nuevos proveedores del pedido
        $order->providers()->attach($providers);

        // Definimos la ruta para volver a la visualizacion del pedido
        return redirect()->route('plannings.show', $order_id)->with('success', 'Solicitud de Presupuesto modificada correctamente');

        // if( $request->user()->hasPermission(['plannings.budget_request_providers.update']) == TRUE ){
        //     return redirect()->route('plannings.show', $order_id)->with('success', 'Solicitud de Presupuesto modificada correctamente');
        // }else{
        //     return redirect()->route('orders.show', $order_id)->with('success', 'Solicitud de Presupuesto modificada correctamente');
        // }
    } 

     /**
     * Update the specified resource in storage. EMPRESAS COTIZANTES COD request_provider_type=1
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update2(Request $request, $order_id)
    {
        //SI EL USUARIO ES DE ADJUDICACIONES SE EJECUTA
        //Y ACA SE PEGA LO QUE ESTÁ EN EL METODO DE ABAJO
        // $order = Order::findOrFail($order_id);
        // $budget = BudgetRequestProvider::findOrFail($budget_id);
       
        // $rules = array(                      
        //     'provider_id' => 'numeric|required|max:32767',
        //     'cc_number' => 'string|nullable|max:25',
        //     'cc_date' => 'date_format:d/m/Y|nullable',
        //     'monto_adjudica' => 'numeric|nullable|max:9223372036854775807',
        // );

        // $validator =  Validator::make($request->input(), $rules);
        // if ($validator->fails()) {
        //     return back()->withErrors($validator)->withInput();
        // }

        // // SI EL CC NO ESTÁ VACIO VERIFICA QUE TENGA FECHA EL CC
        // $cc_number = $request->input('cc_number');

        // if(!empty($cc_number)){                                    
        //     if(empty($request->input('cc_date'))){
        //         $validator->errors()->add('cc_date', 'INGRESE Fecha del C.C.');
        //         return back()->withErrors($validator)->withInput(); 
        //     }                   
        // }

        //  // VERIFICA QUE CC NO ESTÉ DUPLICADO
        // if(empty($request->input('cc_number'))){
        //     if(!empty($request->input('cc_date') && $request->input('monto_adjudica') )){
        //         $validator->errors()->add('cc_number', 'INGRESE Código de Contratación');
        //         return back()->withErrors($validator)->withInput();                 
        //     }            
        // }else{            
        //     $check_cc = BudgetRequestProvider::where('cc_number', str_replace('.', '',$cc_number))->
        //                     where('id', '!=', $budget_id)->count();
        //     if($check_cc > 0){
        //         $validator->errors()->add('cc_number', 'C.C. ingresado ya se encuentra vinculado a una Empresa');
        //         return back()->withErrors($validator)->withInput();
        //     }
            
        //     if(empty($request->input('monto_adjudica'))){
        //         $validator->errors()->add('monto_adjudica', 'INGRESE Monto de adjudicación');
        //         return back()->withErrors($validator)->withInput(); 
        //     }
        // }
        
        // $budget->provider_id = $request->input('provider_id');        
        // $budget->creator_user_id = $request->user()->id;
        // $budget->creator_dependency_id = $request->user()->dependency_id;        
        // $budget->cc_number = $request->input('cc_number');      
        // $budget->cc_date = $request->input('cc_date');
        // $budget->monto_adjudica = $request->input('monto_adjudica');
        // $budget->save();        
        
        // // Definimos la ruta para volver a la visualizacion de Adjudicaciones
        // if( $request->user()->hasPermission(['awards.orders.show']) == TRUE ){
        //     return redirect()->route('awards.show', $order_id)->with('success', 'Empresa adjudicada modificado correctamente'); 
        // }
        
        
        $order = Order::findOrFail($order_id);
        $rules = array(
            'providers' => 'array|required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Obtenemos los proveedores que no hayan sido cargados por otra dependencia
        // y que no esten relacionados con items        
        $delete_providers = BudgetRequestProvider::where('order_id', $order_id)
                ->whereNotIn('provider_id', (array)$request->input('before_providers'))->get()->pluck('provider_id');
        $order->providers()->detach($delete_providers);

        // preparamos el array de proveedores a relacionar con el pedido
        $providers = array();
        foreach($request->input('providers') as $provider => $id){
            $providers[$id] = ['creator_user_id' => $request->user()->id, 
                            'creator_dependency_id' => $request->user()->dependency_id,
                            'request_provider_type' => 1];
        }        

        // Cargamos los nuevos proveedores del pedido
        $order->providers()->attach($providers);

        // Definimos la ruta para volver a la visualizacion del pedido
        if( $request->user()->hasPermission(['plannings.budget_request_providers.update']) == TRUE ){
            return redirect()->route('plannings.show', $order_id)->with('success', 'Solicitud de Presupuesto modificada correctamente');
        }else{
            return redirect()->route('orders.show', $order_id)->with('success', 'Solicitud de Presupuesto modificada correctamente');
        }
    }

    /**
     * Update the specified resource in storage. PARA EMPRESAS ADJUDICADAS COD request_provider_type = 4
     *
     * @param  \Illuminate\Http\Request  $request     
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $order_id, $budget_id)    
    {
        $order = Order::findOrFail($order_id);
        $budget = BudgetRequestProvider::findOrFail($budget_id);
       
        $rules = array(                      
            'provider_id' => 'numeric|required|max:32767',
            'cc_number' => 'string|nullable|max:25',
            'cc_date' => 'date_format:d/m/Y|nullable',            
            'monto_adjudica' => 'string|nullable|max:200',
            'obs_award' => 'string|nullable|max:250'
        );

        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        //VERIFICAMOS QUE MONTO NO SEA SUPERIOR AL SALDO O MONTO TOTAL DE ADJUDICACIÓN        
        $monto_total_adjudica = $order->total_amount_award;
        $monto_adjudica = str_replace('.', '',$request->input('monto_adjudica'));        
        //    var_dump($monto_total_adjudica);
        //    var_dump($monto_adjudica);exit();                       
        
         if ($monto_adjudica > $monto_total_adjudica){
            $validator->errors()->add('', 'MONTO INGRESADO SUPERA MONTO TOTAL DE ADJUDICACIÓN');
            return back()->withErrors($validator)->withInput(); 
        }
        
        //SELECCIONAMOS LOS VALORES DE LAS EMPRESAS ADJUDICADAS QUE TENGAN MONTO MAYOR A 0
        $budget2 = BudgetRequestProvider::where ('order_id','=',$order_id)
                                        ->where('monto_adjudica', '>', 0)
                                        ->where('request_provider_type', '=', 4)
                                        ->get(); 
        // var_dump($rows); 
        // var_dump($monto_adjudica);exit(); 
        
       //VERIFICAMOS QUE MONTO INGRESADO + SUMATORIA DEL MONTO DE OTRAS EMPRESAS NO SEA SUPERIOR AL MONTO TOTAL DE ADJUDICACIÓN                
         $suma_amount_adjudi = 0;        
        //SUMAMOS LOS VALORES DE LAS EMPRESAS REGISTRADAS
         for ($i = 0; $i < count($budget2); $i++){        
            $suma_amount_adjudi += $budget2[$i]->monto_adjudica;
            // var_dump($suma_amount_adjudi);                            
         }
        // var_dump($suma_amount_adjudi);exit();          
        $suma_total = $monto_adjudica + $suma_amount_adjudi;

        if ($suma_total > $monto_total_adjudica){
            $validator->errors()->add('', 'SUMATORIA DE MONTO INGRESADO SUPERA MONTO TOTAL DE ADJUDICACIÓN');
            return back()->withErrors($validator)->withInput(); 
        }

        // SI EL CC NO ESTÁ VACIO VERIFICA QUE TENGA FECHA EL CC
        $cc_number = $request->input('cc_number');

        if(!empty($cc_number)){                                    
            if(empty($request->input('cc_date'))){
                $validator->errors()->add('cc_date', 'INGRESE Fecha del C.C.');
                return back()->withErrors($validator)->withInput(); 
            }                   
        }

         // VERIFICA QUE CC NO ESTÉ DUPLICADO
        if(empty($request->input('cc_number'))){
            if(!empty($request->input('cc_date') && $request->input('monto_adjudica') )){
                $validator->errors()->add('cc_number', 'INGRESE Código de Contratación');
                return back()->withErrors($validator)->withInput();                 
            }            
        }else{            
            $check_cc = BudgetRequestProvider::where('cc_number', str_replace('.', '',$cc_number))->
                            where('id', '!=', $budget_id)->count();
            if($check_cc > 0){
                $validator->errors()->add('cc_number', 'C.C. ingresado ya se encuentra vinculado a una Empresa');
                return back()->withErrors($validator)->withInput();
            }
            
            // if(empty($request->input('monto_adjudica'))){
            //     $validator->errors()->add('monto_adjudica', 'INGRESE Monto de adjudicación');
            //     return back()->withErrors($validator)->withInput(); 
            // }
        }
                

        $budget->provider_id = $request->input('provider_id');        
        $budget->creator_user_id = $request->user()->id;
        $budget->creator_dependency_id = $request->user()->dependency_id;        
        $budget->cc_number = $request->input('cc_number');      
        $budget->cc_date = $request->input('cc_date');
        
        if(empty($request->input('monto_adjudica'))){
            $budget->monto_adjudica = 0;
        }else{
            $monto_adjudica = $request->input('monto_adjudica');
            $budget->monto_adjudica = str_replace('.', '',$monto_adjudica);
        }

        $budget->obs_award = $request->input('obs_award');

        $budget->save();        
        
        // Definimos la ruta para volver a la visualizacion de Adjudicaciones
        if( $request->user()->hasPermission(['awards.orders.show']) == TRUE ){
            return redirect()->route('awards.show', $order_id)->with('success', 'Empresa adjudicada modificado correctamente'); 
        } 
    }

    /**
     * STORE PARA EMPRESAS ADJUDICADAS request_provider_type = 4
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_providers_awarded(Request $request, $order_id )
    {
        
        $order = Order::findOrFail($order_id);
               
        $rules = array(            
            'providers' => 'required',            
            'cc_number' => 'string|nullable|max:25',
            'cc_date' => 'date_format:d/m/Y|nullable', 
            // 'monto_adjudica' => 'numeric|nullable|max:200',
            'monto_adjudica' => 'string|nullable|max:200',
            'obs_award' => 'string|nullable|max:250'           

        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        //VERIFICAMOS QUE MONTO NO SEA SUPERIOR AL SALDO O MONTO TOTAL DE ADJUDICACIÓN
        
        $monto_total_adjudica = $order->total_amount_award;
        $monto_adjudica = str_replace('.', '',$request->input('monto_adjudica'));
        //  var_dump($monto_total_adjudica);
        //  var_dump($monto_adjudica);exit();                
        
        //  if ($monto_adjudica > $monto_total_adjudica){
        //     $validator->errors()->add('', 'MONTO INGRESADO SUPERA MONTO TOTAL DE ADJUDICACIÓN');
        //     return back()->withErrors($validator)->withInput(); 
        // }
        

        //SELECCIONAMOS LOS VALORES DE LAS EMPRESAS ADJUDICADAS QUE TENGAN MONTO MAYOR A 0                                        
        $budget = BudgetRequestProvider::where ('order_id','=',$order_id)
                                        ->where('monto_adjudica', '>', 0)
                                        ->where('request_provider_type', '=', 4)
                                        ->get();                
        
       //VERIFICAMOS QUE MONTO INGRESADO + SUMATORIA DEL MONTO DE OTRAS EMPRESAS NO SEA SUPERIOR AL MONTO TOTAL DE ADJUDICACIÓN                
         $suma_amount_adjudi = 0;
        
        //SUMAMOS LOS VALORES DE LAS EMPRESAS REGISTRADAS
         for ($i = 0; $i < count($budget); $i++){        
            $suma_amount_adjudi += $budget[$i]->monto_adjudica;
            // var_dump($suma_amount_adjudi);                            
         }
        //  var_dump($suma_amount_adjudi);exit();  
        
        $suma_total = $monto_adjudica + $suma_amount_adjudi;

        if ($suma_total > $monto_total_adjudica){
            $validator->errors()->add('', 'SUMATORIA DE MONTO INGRESADO SUPERA MONTO TOTAL DE ADJUDICACIÓN');
            return back()->withErrors($validator)->withInput(); 
        }


        // SI EL CC NO ESTÁ VACIO VERIFICA QUE TENGA FECHA EL CC
        $cc_number = $request->input('cc_number');

        if(!empty($cc_number)){                                    
            if(empty($request->input('cc_date'))){
                $validator->errors()->add('cc_date', 'INGRESE Fecha del C.C.');
                return back()->withErrors($validator)->withInput(); 
            }                   
        }

         // VERIFICA QUE CC NO ESTÉ DUPLICADO
        if(empty($request->input('cc_number'))){
            if(!empty($request->input('cc_date') && $request->input('monto_adjudica') )){
                $validator->errors()->add('cc_number', 'INGRESE Código de Contratación');
                return back()->withErrors($validator)->withInput();                 
            }            
        }else{            
            $check_cc = BudgetRequestProvider::where('cc_number', str_replace('.', '',$cc_number))->
                            where('id', '!=', $order_id)->count();
            if($check_cc > 0){
                $validator->errors()->add('cc_number', 'C.C. ingresado ya se encuentra vinculado a una Empresa');
                return back()->withErrors($validator)->withInput();
            }
            
            // if(empty($request->input('monto_adjudica'))){
            //     $validator->errors()->add('monto_adjudica', 'INGRESE Monto de adjudicación');
            //     return back()->withErrors($validator)->withInput(); 
            // }
        }

        // CAPTURAMOS CAMPO CON SEPARADOR DE MILES PARA MAS ABAJO SACAR LOS PUNTOS PARA GRABAR SIN FORMATO
        // $monto_adjudica = $request->input('monto_adjudica');        
        $budgetRequestProviders = new BudgetRequestProvider;        
        $budgetRequestProviders->order_id = $order_id;
        $budgetRequestProviders->provider_id = $request->input('providers');        
        $budgetRequestProviders->creator_user_id = $request->user()->id;  // usuario logueado        
        $budgetRequestProviders->creator_dependency_id = $request->user()->dependency_id;
        $budgetRequestProviders->request_provider_type = 4;        
        $budgetRequestProviders->cc_number = $request->input('cc_number');      
        $budgetRequestProviders->cc_date = $request->input('cc_date');

        // $budgetRequestProviders->monto_adjudica = str_replace('.', '',$monto_adjudica); 

        if(empty($request->input('monto_adjudica'))){
            $budgetRequestProviders->monto_adjudica = 0;
        }else{
            // $monto_adjudica = $request->input('monto_adjudica');
            $budgetRequestProviders->monto_adjudica = str_replace('.', '',$monto_adjudica);
        }



        $budgetRequestProviders->save();
        
        // Definimos la ruta para volver a la visualizacion de Adjudicaciones
        if( $request->user()->hasPermission(['awards.orders.show']) == TRUE ){
            return redirect()->route('awards.show', $order_id)->with('success', 'Empresa adjudicada agregado correctamente');        
        } 
    }

    /**
     * Update the specified resource in storage. PARA EMPRESAS ADJUDICADAS COD request_provider_type = 4
     *
     * @param  \Illuminate\Http\Request  $request     
     * @return \Illuminate\Http\Response
     */
    public function update_providers_contracts(Request $request, $order_id, $budget_id)   
    {
        $order = Order::findOrFail($order_id);
        $budget = BudgetRequestProvider::findOrFail($budget_id);
       
        $rules = array(                      
            // 'provider_id' => 'numeric|required|max:32767',
            'contract_number' => 'string|nullable|max:25',
            'contract_date' => 'date_format:d/m/Y|nullable',            
            'monto_contract' => 'string|nullable|max:200',
            'obs_contract' => 'string|nullable|max:250'
        );

        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        //VALIDACIONES
        $m_contract = $request->input('monto_contract');
        $monto_contract = str_replace('.', '',$m_contract);

        $monto_adjudica = $budget->monto_adjudica;

        if ($monto_contract > $monto_adjudica){
            $validator->errors()->add('monto_contract', 'Monto del Contrato no puede ser mayor a Monto Adjudicado');
            return back()->withErrors($validator)->withInput(); 
        }
          
        // var_dump($monto_contract,$monto_adjudicar);
        //var_dump($order['total_amount']);exit();

        // SI EL N° CONTRATO NO ESTÁ VACIO VERIFICA QUE TENGA FECHA EL CC
        $contract_number = $request->input('contract_number');

        if(!empty($contract_number)){                                    
            if(empty($request->input('contract_date'))){
                $validator->errors()->add('contract_date', 'INGRESE Fecha del Contrato');
                return back()->withErrors($validator)->withInput(); 
            }                   
        }

         // VERIFICA QUE CONTRATO NO ESTÉ DUPLICADO
        if(empty($request->input('contract_number'))){
            if(!empty($request->input('contract_date') && $request->input('monto_contract') )){
                $validator->errors()->add('contract_number', 'INGRESE Número de Contrato');
                return back()->withErrors($validator)->withInput();                 
            }            
        }else{            
            $check_contract = BudgetRequestProvider::where('contract_number', str_replace('.', '',$contract_number))->
                            where('id', '!=', $budget_id)->count();
            if($check_contract > 0){
                $validator->errors()->add('contract_number', 'N° de Contrato ya se encuentra vinculado a una Empresa');                
                return back()->withErrors($validator)->withInput();
            }
            
            if(empty($request->input('monto_contract'))){
                $validator->errors()->add('monto_contract', 'INGRESE Monto de Contrato');
                return back()->withErrors($validator)->withInput(); 
            }
        }
        
        
        // $contract_number = $request->input('contract_number');

        // $budget->provider_id = $request->input('provider_id');        
        $budget->creator_user_id = $request->user()->id;
        $budget->creator_dependency_id = $request->user()->dependency_id;  
        $budget->contract_date = $request->input('contract_date');
        $budget->obs_contract = $request->input('obs_contract');
        
        // VALIDAMOS CARGA DE NULL EN NUMERO DE CONTRATO
        if(empty($contract_number)){
            $budget->contract_number = 0;
        }else{                        
            $budget->contract_number = str_replace('.', '',$contract_number);
        }
        
        // VALIDAMOS CARGA DE NULL EN MONTO DE CONTRATO
        if(empty($request->input('monto_contract'))){
            $budget->monto_contract = 0;
        }else{            
            $monto_contract = $request->input('monto_contract');
            $budget->monto_contract = str_replace('.', '',$monto_contract);
        }
        $budget->save();        
        
        // Definimos la ruta para volver a la visualizacion de Adjudicaciones
        if( $request->user()->hasPermission(['contracts.orders.show']) == TRUE ){
            return redirect()->route('contracts.show', $order_id)->with('success', 'Datos de Contrato cargado correctamente'); 
        } 
    }


    /**
     * Update the specified resource in storage. PARA EMPRESAS INVITADAS COD request_provider_type = 2
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_providers_guests(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        $rules = array(
            'providers' => 'array|required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

         // Selecciona datos actuales y los borra para cargar de nuevo en el arrary (si se agrega o elimina)
        $delete_providers = BudgetRequestProvider::where('order_id', $order_id)
                ->where('request_provider_type', '=', 2)->get();
        foreach ($delete_providers as $provider) {
            $provider->delete();    // borramos cada proveedor de tipo que no se encuentre en el array pasado
        }

        // preparamos el array de proveedores a relacionar con el pedido
        $providers = array();
        foreach($request->input('providers') as $provider => $id){
            $providers[$id] = ['creator_user_id' => $request->user()->id, 
                            'creator_dependency_id' => $request->user()->dependency_id,
                            'request_provider_type' => 2];                            
        }

        // Cargamos los nuevos proveedores del pedido
        $order->providers()->attach($providers);
        
        // Definimos la ruta para volver a la visualizacion de acuerdo a la modalidad del pedido LICITACIONES, COMPRAS MENORES O EXCEPCIONES
        // Para Licitaciones
        if( $request->user()->hasPermission(['tenders.budget_request_providers.update']) == TRUE ){
            return redirect()->route('tenders.show', $order_id)->with('success', 'Empresa Invitada modificada correctamente');            
        }else{
            // Para Compras menores
            if( $request->user()->hasPermission(['minor_purchases.budget_request_providers.update']) == TRUE ){
                return redirect()->route('minor_purchases.show', $order_id)->with('success', 'Empresa Invitada modificada correctamente');                
            }else{
                // Para Compras vía excepción
                if( $request->user()->hasPermission(['exceptions.budget_request_providers.update']) == TRUE ){                                   
                    return redirect()->route('exceptions.show', $order_id)->with('success', 'Empresa Invitada modificada correctamente');                
                }     
            }    
        }
    }

    /**
     * Update the specified resource in storage. PARA EMPRESAS PARTICIPANTES COD request_provider_type = 3
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_providers_participants(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        $rules = array(
            'providers' => 'array|required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        // Selecciona datos actuales y los borra para cargar de nuevo en el arrary (si se agrega o elimina)
        $delete_providers = BudgetRequestProvider::where('order_id', $order_id)
                ->where('request_provider_type', '=', 3)->get();
        foreach ($delete_providers as $provider) {
            $provider->delete();    // borramos cada proveedor de tipo que no se encuentre en el array pasado
        }

        // preparamos el array de proveedores a relacionar con el pedido
        $providers = array();
        foreach($request->input('providers') as $provider => $id){
            $providers[$id] = ['creator_user_id' => $request->user()->id, 
                            'creator_dependency_id' => $request->user()->dependency_id,
                            'request_provider_type' => 3];                            
        }

        // Cargamos los nuevos proveedores del pedido
        $order->providers()->attach($providers);
        
        // Definimos la ruta para volver a la visualizacion de acuerdo a la modalidad del pedido LICITACIONES, COMPRAS MENORES O EXCEPCIONES
        // Para Licitaciones
        if( $request->user()->hasPermission(['tenders.budget_request_providers.update']) == TRUE ){
            return redirect()->route('tenders.show', $order_id)->with('success', 'Empresa participante modificada correctamente');            
        }else{
            // Para Compras menores
            if( $request->user()->hasPermission(['minor_purchases.budget_request_providers.update']) == TRUE ){
                return redirect()->route('minor_purchases.show', $order_id)->with('success', 'Empresa participante modificada correctamente');                
            }else{
                // Para Compras vía excepción
                if( $request->user()->hasPermission(['exceptions.budget_request_providers.update']) == TRUE ){                                   
                    return redirect()->route('exceptions.show', $order_id)->with('success', 'Empresa participante modificada correctamente');                
                }     
            }    
        }
    }

    /**
     * Show the form for editing the specified resource. EDITA EMPRESAS COTIZANTES
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_providers(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.budget_request_providers.update','orders.budget_request_providers.update']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }
        
        // Definimos la ruta para volver a la visualizacion de ORDERS
        // Para Licitaciones
        if( $request->user()->hasPermission(['orders.budget_request_providers.update','admin.budget_request_providers.update']) == TRUE ){
            $orders_route = route('orders.show', $order_id);       
        }

        // empresas participantes del pedido
        $empresas_cotizantes = $order->budgetRequestProviders()->where('request_provider_type', 1)->get();
        $providers = Provider::all();

        return view('order.budget_request_providers.update', compact('order', 'orders_route', 'empresas_cotizantes', 'providers'));
    }

    /**
     * Show the form for editing the specified resource. EDITA EMPRESAS COTIZANTES DE PAC
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_providersPAC(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.budget_request_providers.update','plannings.budget_request_providers.update']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }
        
        // Definimos la ruta para volver a la visualizacion de PLANNINGS
        if( $request->user()->hasPermission(['admin.budget_request_providers.update','plannings.budget_request_providers.update']) == TRUE ){
            $orders_route = route('plannings.show', $order_id);        
        }

        // empresas participantes del pedido generados desde PAC con provider_type = 5
        $empresas_cotizantesPAC = $order->budgetRequestProviders()->where('request_provider_type', 5)->get();
        $providers = Provider::all();

        return view('order.budget_request_providers.update_providersPAC', compact('order', 'orders_route', 'empresas_cotizantesPAC', 'providers'));
    }

    
    /**
     * PARA EDITAR EMPRESAS COTIZANTES, INVITADAS O PARTICIPANTES
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.budget_request_providers.update','plannings.budget_request_providers.update']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        // Definimos la ruta para volver a la visualizacion del pedido
        if( $request->user()->hasPermission(['plannings.budget_request_providers.update']) == TRUE ){
            $orders_route = route('plannings.show', $order_id);
        }else{
            $orders_route = route('orders.show', $order_id);
        }

        // obtenemos los precios referenciales con las solicitudes de presupuesto relacionadas
        $budget_request_providers = ItemAwardHistory::whereIn('item_id', $order->items->pluck('id'))->select('budget_request_provider_id')
                    ->distinct()->get()->pluck('budget_request_provider_id');
        
                    // obtenemos los proveedores de los presupuestos relacionados
        $related_providers = BudgetRequestProvider::whereIn('id', $budget_request_providers)->get();
        
        // obtenemos las solicitudes de presupuesto cargadas por otras dependencias SOLO DE COTIZACIONES request_provider_type = 1
        $other_dependencies = BudgetRequestProvider::where('creator_dependency_id', '!=', $request->user()->dependency_id)
                                                    ->where('request_provider_type')->get();

        $budget_request_providers_ids = array_merge($related_providers->pluck('provider_id')->toArray(), 
                                        $other_dependencies->pluck('provider_id')->toArray());
        
        $providers = Provider::whereNotIn('id', $budget_request_providers_ids)->get();
        
        // return view('order.budget_request_providers.update', compact('order', 'providers', 'related_providers', 'other_dependencies', 'orders_route'));
        //PARA PLANNINGS
        if( $request->user()->hasPermission(['plannings.budget_request_providers.update']) == TRUE ){
            $orders_route = route('plannings.show', $order_id);
            return view('order.budget_request_providers.update', compact('order', 'providers','related_providers', 'other_dependencies', 'orders_route'));
        }

        //PARA ORDERS
        if ($request->user()->hasPermission(['orders.budget_request_providers.update']) == true) {
            $orders_route = route('orders.show', $order_id);
            return view('order.budget_request_providers.update', compact('order', 'providers', 'related_providers', 'other_dependencies', 'orders_route'));
        }

        // PARA AWARDS
        // if ($request->user()->hasPermission(['awards.budget_request_providers.update']) == true) {
        //     $orders_route = route('awards.show', $order_id);
        //     $budget = BudgetRequestProvider::findOrFail($budget_id);                
        //     $providers = Provider::all();                
        //     return view('order.budget_request_providers.update_providers_awarded', compact('order','budget','providers','orders_route'));                            
        // }

        //  PARA CONTRACTS
        //  if ($request->user()->hasPermission(['contracts.budget_request_providers.update']) == true) {
        //     $orders_route = route('contracts.show', $order_id);
        //     $budget = BudgetRequestProvider::findOrFail($budget_id);                
        //     $providers = Provider::all();                
        //     return view('order.budget_request_providers.update_providers_contracts', compact('order','budget','providers','orders_route'));                            
        // }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // ESTE METODO TAMBIEN SE UTILIZA EN ORDER Y PLANNINGS CUANDO SE EDITA EMPRESAS COTIZANTES
    // ADEMÁS SE UTILIZA EN ADJUDICACIONES CUANDO SE EDITA EMPRESAS ADJUDICADAS
     public function edit(Request $request, $order_id, $budget_id)
     {
        $order = Order::findOrFail($order_id); 

        // FILTRAR POR DEPENDENCIA
        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        // if(!$request->user()->hasPermission(['admin.items_budget.update','contracts.budget_request_providers.update']) &&
        //     $order->dependency_id != $request->user()->dependency_id) {
        //     // COPIAR AQUI FUNCIÓN edit_providers_contracts
        // }               

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.items_budget.update','admin.budget_request_providers.update','plannings.budget_request_providers.update']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección');
        }
        
        // obtenemos los precios referenciales con las solicitudes de presupuesto relacionadas
        $budget_request_providers = ItemAwardHistory::whereIn('item_id', $order->items->pluck('id'))->select('budget_request_provider_id')
                    ->distinct()->get()->pluck('budget_request_provider_id');
        
        // obtenemos los proveedores de los presupuestos relacionados
        $related_providers = BudgetRequestProvider::whereIn('id', $budget_request_providers)->get();
        
        // obtenemos las solicitudes de presupuesto cargadas por otras dependencias SOLO DE COTIZACIONES request_provider_type = 1
        $other_dependencies = BudgetRequestProvider::where('creator_dependency_id', '!=', $request->user()->dependency_id)
                                                    ->where('request_provider_type', 1)->get();

        $budget_request_providers_ids = array_merge($related_providers->pluck('provider_id')->toArray(), 
                                        $other_dependencies->pluck('provider_id')->toArray());
        
        $providers = Provider::whereNotIn('id', $budget_request_providers_ids)->get();
        
        // Definimos la ruta para volver a la visualizacion del pedido
        
        //PARA PLANNINGS
        // if( $request->user()->hasPermission(['plannings.budget_request_providers.update']) == TRUE ){
        //     $orders_route = route('plannings.show', $order_id);
        //     return view('order.budget_request_providers.update', compact('order', 'providers','related_providers', 'other_dependencies', 'orders_route'));
        // }
        
        
        //PARA ORDERS
        // if ($request->user()->hasPermission(['orders.budget_request_providers.update']) == true) {
        //     $orders_route = route('orders.show', $order_id);
        //     return view('order.budget_request_providers.update', compact('order', 'providers', 'related_providers', 'other_dependencies', 'orders_route'));
        // }
        
        //PARA AWARDS
        if ($request->user()->hasPermission(['awards.budget_request_providers.update']) == true) {
            $orders_route = route('awards.show', $order_id);
            $budget = BudgetRequestProvider::findOrFail($budget_id);                
            $providers = Provider::all();                
            return view('order.budget_request_providers.update_providers_awarded', compact('order','budget','providers','orders_route'));                            
        }

        //PARA CONTRACTS
        if ($request->user()->hasPermission(['contracts.budget_request_providers.update']) == true) {
            $orders_route = route('contracts.show', $order_id);
            $budget = BudgetRequestProvider::findOrFail($budget_id);                
            $providers = Provider::all();                
            return view('order.budget_request_providers.update_providers_contracts', compact('order','budget','providers','orders_route'));                            
        }
    }


    

    /**
     * Show the form for editing the specified resource. PARA EMPRESAS ADJUDICADAS
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_providers_awarded(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.budget_request_providers.update','awards.budget_request_providers.update']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }
        
        // Definimos la ruta para volver a la visualizacion de acuerdo a la modalidad del pedido LICITACIONES, COMPRAS MENORES O EXCEPCIONES
        // Para Licitaciones
        if( $request->user()->hasPermission(['awards.budget_request_providers.update']) == TRUE ){
            $orders_route = route('awards.show', $order_id);        
        }

        // empresas participantes del pedido
        $empresas_adjudicadas = $order->budgetRequestProviders()->where('request_provider_type', 4)->get();
        $providers = Provider::all();

        return view('order.budget_request_providers.update_providers_awarded', compact('order', 'orders_route', 'empresas_adjudicadas', 'providers'));
    }

    /**
     * Show the form for editing the specified resource. PARA EMPRESAS INVITADAS
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_providers_guests(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.budget_request_providers.update','tenders.budget_request_providers.update','minor_purchases.budget_request_providers.update','exceptions.budget_request_providers.update']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }
        
        // Definimos la ruta para volver a la visualizacion de acuerdo a la modalidad del pedido LICITACIONES, COMPRAS MENORES O EXCEPCIONES
        // Para Licitaciones
        if( $request->user()->hasPermission(['tenders.budget_request_providers.update']) == TRUE ){
            $orders_route = route('tenders.show', $order_id);
        }else{
            // Para Compras menores
            if( $request->user()->hasPermission(['minor_purchases.budget_request_providers.update']) == TRUE ){
                $orders_route = route('minor_purchases.show', $order_id);                
            }else{
                // Para Compras vía excepción
                if( $request->user()->hasPermission(['exceptions.budget_request_providers.update']) == TRUE ){               
                    $orders_route = route('exceptions.show', $order_id);                     
                }     
            }    
        }

        // empresas invitadas del pedido
        $empresas_invitadas = $order->budgetRequestProviders()->where('request_provider_type', 2)->get();
        $providers = Provider::all();

        return view('order.budget_request_providers.update_providers_guests', compact('order', 'orders_route', 'empresas_invitadas', 'providers'));
    }

    /**
     * Show the form for editing the specified resource. PARA EMPRESAS PARTICIPANTES
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_providers_participants(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.budget_request_providers.update','tenders.budget_request_providers.update','minor_purchases.budget_request_providers.update','exceptions.budget_request_providers.update']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }
        
        // Definimos la ruta para volver a la visualizacion de acuerdo a la modalidad del pedido LICITACIONES, COMPRAS MENORES O EXCEPCIONES
        // Para Licitaciones
        if( $request->user()->hasPermission(['tenders.budget_request_providers.update']) == TRUE ){
            $orders_route = route('tenders.show', $order_id);
        }else{
            // Para Compras menores
            if( $request->user()->hasPermission(['minor_purchases.budget_request_providers.update']) == TRUE ){
                $orders_route = route('minor_purchases.show', $order_id);                
            }else{
                // Para Compras vía excepción
                if( $request->user()->hasPermission(['exceptions.budget_request_providers.update']) == TRUE ){               
                    $orders_route = route('exceptions.show', $order_id);                     
                }     
            }    
        }

        // empresas participantes del pedido
        $empresas_participantes = $order->budgetRequestProviders()->where('request_provider_type', 3)->get();
        $providers = Provider::all();

        return view('order.budget_request_providers.update_providers_participants', compact('order', 'orders_route', 'empresas_participantes', 'providers'));
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // // ESTE METODO TAMBIEN SE UTILIZA EN ORDER Y PLANNINGS CUANDO SE EDITA EMPRESAS COTIZANTES
    // // ADEMÁS SE UTILIZA EN ADJUDICACIONES CUANDO SE EDITA EMPRESAS ADJUDICADAS
    // public function edit(Request $request, $order_id, $budget_id)


     /**
     * Show the form for editing the specified resource. PARA EMPRESAS CONTRATADAS
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_providers_contracts(Request $request, $order_id, $budget_id)    
    {
        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.budget_request_providers.update','contracts.budget_request_providers.update']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }
        
        // Definimos la ruta para volver a la visualizacion de acuerdo a la modalidad del pedido LICITACIONES, COMPRAS MENORES O EXCEPCIONES
        // Para Licitaciones
        if( $request->user()->hasPermission(['contracts.budget_request_providers.update']) == TRUE ){
            $orders_route = route('contracts.show', $order_id);        
        }

        // $empresas_invitadas = $order->budgetRequestProviders()->where('request_provider_type', 4)->get();        
        $budget = BudgetRequestProvider::findOrFail($budget_id);                

        $providers = Provider::all();

        return view('order.budget_request_providers.update_providers_contracts', compact('order', 'orders_route', 'budget', 'providers'));
    }

    
        
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $order_id, $budget_id)
    {
        $order = Order::findOrFail($order_id);
        $budget = BudgetRequestProvider::findOrFail($budget_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.items_budget.delete','admin.budget_request_providers.delete','plannings.budget_request_providers.delete']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }
        
        // Quitamos todas las empresas del pedido si es un array
        // $order->providers()->sync([]);
        // return response()->json(['status' => 'success', 'message' => 'Se ha eliminado la empresas a las que se les solicito presupuesto.', 'code' => 200], 200);

        // Eliminamos en caso de no existir registros referenciando al item
        $budget->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado la empresa', 'code' => 200], 200);
    }
    

    //DESTROY ORIGINAL
    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Request $request, $order_id)
    // {
    //     $order = Order::findOrFail($order_id);

    //     // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
    //     if(!$request->user()->hasPermission(['admin.budget_request_providers.delete']) &&
    //     $order->dependency_id != $request->user()->dependency_id){
    //         return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
    //     }
        
    //     // Quitamos todas las empresas del pedido
    //     $order->providers()->sync([]);
    //     return response()->json(['status' => 'success', 'message' => 'Se han eliminado las empresas a las que se les solicito presupuesto.', 'code' => 200], 200);
    // }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy_providers_guests(Request $request, $order_id)    
    {        
        $order = Order::findOrFail($order_id);                
        
        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.budget_request_providers.delete','tenders.budget_request_providers.delete','minor_purchases.budget_request_providers.delete','exceptions.budget_request_providers.delete']) &&
            $order->dependency_id != $request->user()->dependency_id){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $guests = $order->budgetRequestProviders()->where('request_provider_type', 2)->get();
        foreach($guests as $guest){
            $guest->delete();
        }
        
        return response()->json(['status' => 'success', 'message' => 'Se han eliminado las empresas invitadas', 'code' => 200], 200);
    }

    
      
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy_providers_participants(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);                
        
        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.budget_request_providers.delete','tenders.budget_request_providers.delete','minor_purchases.budget_request_providers.delete','exceptions.budget_request_providers.delete']) &&
            $order->dependency_id != $request->user()->dependency_id){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $guests = $order->budgetRequestProviders()->where('request_provider_type', 3)->get();
        foreach($guests as $guest){
            $guest->delete();
        }        
        return response()->json(['status' => 'success', 'message' => 'Se han eliminado las empresas participantes', 'code' => 200], 200);
    }
}
