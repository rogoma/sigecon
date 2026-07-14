<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\DependencyType;
use Illuminate\Validation\Rule;

class DependencyTypesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.dependency_types.index')->only('index'); // Permiso para index 
        $this->middleware('checkPermission:admin.dependency_types.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.dependency_types.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dependency_types = DependencyType::all();
        return view('admin.dependency_types.index', compact('dependency_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.dependency_types.create');
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
            'description' => 'required|string|max:150|unique:dependency_types,description'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $dependency_type = new DependencyType;
        $dependency_type->description = $request->input('description');
        $dependency_type->creator_user_id = $request->user()->id;  // usuario logueado
        $dependency_type->save();

        return redirect()->route('dependency_types.index')->with('success', 'Tipo de Dependencia agregado correctamente');
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
        $dependency_type = DependencyType::find($id);
        return view('admin.dependency_types.update', compact('dependency_type'));
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
                Rule::unique('dependency_types')->ignore($id),
            ]
        );
        
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos el Tipo de Dependencia
        $dependency_type = DependencyType::find($id);
        $dependency_type->description = $request->input('description');
        $dependency_type->modifier_user_id = $request->user()->id;  // usuario logueado
        $dependency_type->save();

        return redirect()->route('dependency_types.index')->with('success', 'Tipo de Dependencia modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.dependency_types.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $dependency_type = DependencyType::find($id);
        // Chequeamos si existen dependencias referenciando al tipo de dependencia
        if($dependency_type->dependencies->count() > 0){
            return response()->json(['status' => 'error', 'message' => 'No se ha podido eliminar el tipo de dependencia debido a que se encuentra vinculado a dependencias ', 'code' => 200], 200);
        }
        
        // Eliminamos en caso de no existir dependencias referenciando al tipo de dependencia
        $dependency_type->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el Tipo de Dependencia ' . $dependency_type->description, 'code' => 200], 200);
    }
}
