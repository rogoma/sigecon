<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\OrderPresentation;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport5;

class OrderPresentationsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.order_presentations.index')->only('index'); // Permiso para index 
        $this->middleware('checkPermission:admin.order_presentations.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.order_presentations.update')->only(['edit', 'update']);   // Permiso para update
    }

    //Para exportar a Excel usuarios
    public function exportarExcel()
    {     
        return Excel::download(new OrdersExport5, 'Presentaciones.xlsx');
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order_presentations = OrderPresentation::all();
        return view('admin.order_presentations.index', compact('order_presentations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.order_presentations.create');
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

        $order_presentation = new OrderPresentation;
        $order_presentation->description = $request->input('description');
        $order_presentation->creator_user_id = $request->user()->id;  // usuario logueado
        $order_presentation->save();

        return redirect()->route('order_presentations.index')->with('success', 'Presentación de orden agregado correctamente');
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
        $order_presentation = OrderPresentation::find($id);
        return view('admin.order_presentations.update', compact('order_presentation'));
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
        $order_presentation = OrderPresentation::find($id);
        $order_presentation->description = $request->input('description');
        $order_presentation->modifier_user_id = $request->user()->id;  // usuario logueado
        $order_presentation->save();

        return redirect()->route('order_presentations.index')->with('success', 'Presentación de orden  modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.order_presentations.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $order_presentation = OrderPresentation::find($id);
        
        // Eliminamos en caso de no existir usuarios vinculados al cargo
        $order_presentation->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el Presentación de orden  ' . $order_presentation->description, 'code' => 200], 200);
    }
}
