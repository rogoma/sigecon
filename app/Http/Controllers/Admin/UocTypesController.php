<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\UocType;
use Illuminate\Validation\Rule;

class UocTypesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.uoc_types.index')->only('index'); // Permiso para index 
        $this->middleware('checkPermission:admin.uoc_types.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.uoc_types.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uoc_types = UocType::all();
        return view('admin.uoc_types.index', compact('uoc_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.uoc_types.create');
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
            'description' => 'required|string|max:150|unique:uoc_types,description'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $uoc_type = new UocType;
        $uoc_type->description = $request->input('description');
        $uoc_type->creator_user_id = $request->user()->id;  // usuario logueado
        $uoc_type->save();

        return redirect()->route('uoc_types.index')->with('success', 'Tipo de UOC agregado correctamente');
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
        $uoc_type = UocType::find($id);
        return view('admin.uoc_types.update', compact('uoc_type'));
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
            'description' => [
                'string',
                'required',
                'max:150',
                Rule::unique('uoc_types')->ignore($id),
            ]
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos el Tipo de UOC
        $uoc_type = UocType::find($id);
        $uoc_type->description = $request->input('description');
        $uoc_type->modifier_user_id = $request->user()->id;  // usuario logueado
        $uoc_type->save();

        return redirect()->route('uoc_types.index')->with('success', 'Tipo de UOC modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.uoc_types.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $uoc_type = UocType::find($id);
        // Chequeamos si existen dependencias referenciando al tipo de uoc
        if($uoc_type->dependencies->count() > 0){
            return response()->json(['status' => 'error', 'message' => 'No se ha podido eliminar el tipo de uoc debido a que se encuentra vinculado a dependencias ', 'code' => 200], 200);
        }
        
        // Eliminamos en caso de no existir dependencias referenciando al tipo de uoc
        $uoc_type->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el Tipo de UOC ' . $uoc_type->description, 'code' => 200], 200);
    }
}
