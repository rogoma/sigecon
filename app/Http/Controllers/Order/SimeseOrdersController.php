<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\Simese;

class SimeseOrdersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $index_permissions = ['admin.simese.index',
                            'orders.simese.index',
                            'process_orders.simese.index',
                            'derive_orders.simese.index',
                            'plannings.simese.index',
                            'tenders.simese.index',
                            'minor_purchases.simese.index',
                            'awards.simese.index',
                            'contracts.simese.index',
                            'utas.simese.index',
                            'legal_advices.simese.index',
                            'comites.simese.index',
                            'coordinations.simese.index',
                            'dgafs.simese.index'];
        $create_permissions = ['admin.simese.create',
                            'orders.simese.create',
                            'derive_orders.simese.create',
                            'plannings.simese.create',
                            'tenders.simese.create',
                            'minor_purchases.simese.create',
                            'awards.simese.create',
                            'exceptions.simese.create',
                            'contracts.simese.create',
                            'utas.simese.create',
                            'legal_advices.simese.create',
                            'comites.simese.create',
                            'coordinations.simese.create',
                            'dgafs.simese.create'];
        $update_permissions = ['admin.simese.update',
                            'orders.simese.update',
                            'derive_orders.simese.update',
                            'plannings.simese.update',
                            'tenders.simese.update',
                            'minor_purchases.simese.update',
                            'awards.simese.update',
                            'exceptions.simese.update',
                            'contracts.simese.update',
                            'utas.simese.update',
                            'legal_advices.simese.update',
                            'comites.simese.update',
                            'coordinations.simese.update',
                            'dgafs.simese.update'];

        $this->middleware('checkPermission:'.implode(',',$index_permissions))->only('index'); // Permiso para index 
        $this->middleware('checkPermission:'.implode(',',$create_permissions))->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:'.implode(',',$update_permissions))->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        $user_dependency = $request->user()->dependency_id;
        // Obtenemos los simese cargados por otras dependencias
        $related_simese = $order->simese()->where('dependency_id', '!=', $user_dependency)->get();
        // ruta a vista del pedido
        if($request->user()->hasPermission(['plannings.simese.create'])){
            $show_route = route('plannings.show', $order_id);
        }elseif($request->user()->hasPermission(['tenders.simese.create'])){
            $show_route = route('tenders.show', $order_id);
        }elseif($request->user()->hasPermission(['minor_purchases.simese.create'])){
            $show_route = route('minor_purchases.show', $order_id);         
        }elseif($request->user()->hasPermission(['awards.simese.create'])){
            $show_route = route('awards.show', $order_id);
        }elseif($request->user()->hasPermission(['exceptions.simese.create'])){
            $show_route = route('exceptions.show', $order_id);
        }elseif($request->user()->hasPermission(['contracts.simese.create'])){
            $show_route = route('contracts.show', $order_id);        
        }elseif($request->user()->hasPermission(['utas.simese.create'])){
            $show_route = route('utas.show', $order_id);
        }elseif($request->user()->hasPermission(['legal_advices.simese.create'])){
            $show_route = route('legal_advices.show', $order_id);
        }elseif($request->user()->hasPermission(['comites.simese.create'])){
            $show_route = route('comites.show', $order_id);
        }elseif($request->user()->hasPermission(['coordinations.simese.create'])){
            $show_route = route('coordinations.show', $order_id);
        }elseif($request->user()->hasPermission(['dgafs.simese.create'])){
            $show_route = route('dgafs.show', $order_id);
        }else {             
            $show_route = route('orders.show', $order_id);
        }
        return view('order.simese.create', compact('order', 'related_simese', 'show_route'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $order_id)
    {
        $order = Order::find($order_id);
        $years = (array)$request->input('year');
        $simeses = (array)$request->input('simese');

        //VALIDAMOS SIMESE-AÑO SI YA EXISTE EN LA BD
        // $validator =  Validator::make($request->input());

        // if(empty($request->input('cdp_number'))){
            
        // }else{
        //     $cdp_number = $request->input('cdp_number');
        //     $check_cdp = Order::where('cdp_number', str_replace('.', '',$cdp_number))->
        //                     where('id', '!=', $order_id)->count();
        //     if($check_cdp > 0){
        //         $validator->errors()->add('cdp_number', 'Número de CDP ingresado ya se encuentra vinculado a un pedido.');
        //         return back()->withErrors($validator)->withInput();
        //     }            
        // }

        for ($i=0; $i < count($years); $i++) { 
            $simese = new Simese;
            $simese->year = $years[$i];
            $simese->simese = $simeses[$i];
            $simese->order_id = $order_id;
            $simese->order_state_id = $order->actual_state;
            $simese->creator_user_id = $request->user()->id;
            $simese->dependency_id = $request->user()->dependency_id;
            $simese->save();
        }

        $request->session()->flash('success', 'Se han agregado exitosamente SIMESE relacionado al pedido.');
        return response()->json(['status' => 'success', 'code' => 200], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        // Obtenemos los id de los estados del pedido
        $user_dependency = $request->user()->dependency_id;
        // Obtenemos los simese cargados por otras dependencias
        $related_simese = $order->simese()->where('dependency_id', '!=', $user_dependency)->get();
        // Obtenemos los simese cargados por la dependencia del usuario
        $related_simese_user = $order->simese->where('dependency_id', $user_dependency);
        // ruta a vista del pedido
        if($request->user()->hasPermission(['plannings.simese.create'])){
            $show_route = route('plannings.show', $order_id);
        }elseif($request->user()->hasPermission(['tenders.simese.create'])){
            $show_route = route('tenders.show', $order_id);
        }elseif($request->user()->hasPermission(['minor_purchases.simese.create'])){
            $show_route = route('minor_purchases.show', $order_id);         
        }elseif($request->user()->hasPermission(['awards.simese.create'])){
            $show_route = route('awards.show', $order_id);                 
        }elseif($request->user()->hasPermission(['exceptions.simese.create'])){
            $show_route = route('exceptions.show', $order_id);
        }elseif($request->user()->hasPermission(['contracts.simese.create'])){
            $show_route = route('contracts.show', $order_id);
        }elseif($request->user()->hasPermission(['utas.simese.create'])){
            $show_route = route('utas.show', $order_id);
        }elseif($request->user()->hasPermission(['legal_advices.simese.create'])){
            $show_route = route('legal_advices.show', $order_id);
        }elseif($request->user()->hasPermission(['comites.simese.create'])){
            $show_route = route('comites.show', $order_id);
        }elseif($request->user()->hasPermission(['coordinations.simese.create'])){
            $show_route = route('coordinations.show', $order_id);
        }elseif($request->user()->hasPermission(['dgafs.simese.create'])){
            $show_route = route('dgafs.show', $order_id);
        }else {             
            $show_route = route('orders.show', $order_id);
        }
        return view('order.simese.update', compact('order', 'related_simese', 'related_simese_user', 'show_route'));
    }

    /**
     * Updated a specific resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $order_id)
    {
        $order = Order::find($order_id);
        $years = (array)$request->input('year');
        $simeses = (array)$request->input('simese');

        $user_dependency = $request->user()->dependency_id;
        // Obtenemos los simese cargados por la dependencia del usuario
        $related_simese_user = $order->simese()->where('dependency_id', $user_dependency)->get();

        // Borramos los simese cargados por la dependencia del usuario anteriormente
        foreach($related_simese_user as $simese){
            $simese->delete();
        }

        // Guardamos los nuevos documentos del formulario
        for ($i=0; $i < count($years); $i++) { 
            $simese = new Simese;
            $simese->year = $years[$i];
            $simese->simese = $simeses[$i];
            $simese->order_id = $order_id;
            $simese->order_state_id = $order->actual_state;
            $simese->creator_user_id = $request->user()->id;
            $simese->dependency_id = $request->user()->dependency_id;
            $simese->save();
        }

        $request->session()->flash('success', 'Se ha actualizado exitosamente los documentos relacionados al pedido.');
        return response()->json(['status' => 'success', 'code' => 200], 200);
    }
}
