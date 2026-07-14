<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Permission;
use Illuminate\Validation\Rule;

class PermissionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.permissions.index')->only('index'); // Permiso para index 
        $this->middleware('checkPermission:admin.permissions.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.permissions.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.permissions.create');
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
            'description' => 'required|string|max:100|unique:permissions,description'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();            
        }

        $permission = new Permission;
        $permission->description = $request->input('description');
        $permission->creator_user_id = $request->user()->id;  // usuario logueado
        $permission->save();

        return redirect()->route('permissions.index')->with('success', 'Permiso agregado correctamente');
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
        $permission = Permission::find($id);
        return view('admin.permissions.update', compact('permission'));
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
                'max:100',
                Rule::unique('permissions')->ignore($id),
            ]
        );

        


        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos el Permiso
        $permission = Permission::find($id);
        $permission->description = $request->input('description');
        $permission->modifier_user_id = $request->user()->id;  // usuario logueado
        $permission->save();

        return redirect()->route('permissions.index')->with('success', 'Permiso modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.permissions.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $permission = Permission::find($id);
        // Chequeamos si existen roles vinculados al permiso
        if($permission->rolePermission->count() > 0){
            return response()->json(['status' => 'error', 'message' => 'No se ha podido eliminar el permiso debido a que se encuentra vinculado a roles ', 'code' => 200], 200);
        }
        
        // Eliminamos en caso de no existir roles vinculados al permiso
        $permission->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el Permiso ' . $permission->description, 'code' => 200], 200);
    }
}
