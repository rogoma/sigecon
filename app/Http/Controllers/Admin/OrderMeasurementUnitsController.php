<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\OrderMeasurementUnit;

class OrderMeasurementUnitsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.order_measurement_units.index')->only('index'); // Permiso para index 
        $this->middleware('checkPermission:admin.order_measurement_units.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.order_measurement_units.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order_measurement_units = OrderMeasurementUnit::all();
        return view('admin.order_measurement_units.index', compact('order_measurement_units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.order_measurement_units.create');
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
            'description' => 'string|required|max:100'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $order_measurement_unit = new OrderMeasurementUnit;
        $order_measurement_unit->description = $request->input('description');
        $order_measurement_unit->creator_user_id = $request->user()->id;  // usuario logueado
        $order_measurement_unit->save();

        return redirect()->route('order_measurement_units.index')->with('success', 'Unidad de medida agregado correctamente');
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
        $order_measurement_unit = OrderMeasurementUnit::find($id);
        return view('admin.order_measurement_units.update', compact('order_measurement_unit'));
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
            'description' => 'string|required|max:100'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos el cargo
        $order_measurement_unit = OrderMeasurementUnit::find($id);
        $order_measurement_unit->description = $request->input('description');
        $order_measurement_unit->modifier_user_id = $request->user()->id;  // usuario logueado
        $order_measurement_unit->save();

        return redirect()->route('order_measurement_units.index')->with('success', 'Unidad de medida modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.order_measurement_units.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $order_measurement_unit = OrderMeasurementUnit::find($id);
        
        // Eliminamos en caso de no existir usuarios vinculados al cargo
        $order_measurement_unit->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el Unidad de medida ' . $order_measurement_unit->description, 'code' => 200], 200);
    }
}
