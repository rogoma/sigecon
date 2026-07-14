<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Position;

class PositionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.positions.index')->only('index'); // Permiso para index 
        $this->middleware('checkPermission:admin.positions.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.positions.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $positions = Position::all();
        return view('admin.positions.index', compact('positions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.positions.create');
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
            'description' => 'string|required|max:100|unique:positions'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $position = new Position;
        $position->description = $request->input('description');
        $position->creator_user_id = $request->user()->id;  // usuario logueado
        $position->save();

        return redirect()->route('positions.index')->with('success', 'Cargo agregado correctamente');
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
        $position = Position::find($id);
        return view('admin.positions.update', compact('position'));
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
            'description' => 'string|required|max:100|unique:positions'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos el cargo
        $position = Position::find($id);
        $position->description = $request->input('description');
        $position->modifier_user_id = $request->user()->id;  // usuario logueado
        $position->save();

        return redirect()->route('positions.index')->with('success', 'Cargo modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.positions.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $position = Position::find($id);
        
        // Chequeamos si existen cargos vinculados al usuarios
        if($position->users->count() > 0){
            return response()->json(['status' => 'error', 'message' => 'No se ha podido eliminar el cargo debido a que se encuentra vinculado a usuario ', 'code' => 200], 200);
        }
        
        // Eliminamos en caso de no existir usuarios vinculados al cargo
        $position->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el Cargo ' . $position->description, 'code' => 200], 200);
    }
}
