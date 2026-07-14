<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\OrderState;

class OrderStatesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.order_states.index')->only('index'); // Permiso para index 
        $this->middleware('checkPermission:admin.order_states.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.order_states.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order_states = OrderState::all();
        return view('admin.order_states.index', compact('order_states'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.order_states.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'description' => 'string|required|max:150'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $order_state = new OrderState;
        $order_state->description = $request->input('description');
        $order_state->creator_user_id = $request->user()->id;  // usuario logueado
        $order_state->save();

        return redirect()->route('order_states.index')->with('success', 'Estado pedido agregado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order_state = OrderState::find($id);
        return view('admin.order_states.update', compact('order_state'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'description' => 'string|required|max:150'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos el Estado del pedido
        $order_state = OrderState::find($id);
        $order_state->description = $request->input('description');
        $order_state->modifier_user_id = $request->user()->id;  // usuario logueado
        $order_state->save();

        return redirect()->route('order_states.index')->with('success', 'Estado pedido modificado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // Chequeamos que el usuario actual disponga de permisos de eliminacion
        if(!$request->user()->hasPermission(['admin.order_states.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $order_state = OrderState::find($id);
        $order_state->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el Estado pedido ' . $order_state->description, 'code' => 200], 200);
    }
}
