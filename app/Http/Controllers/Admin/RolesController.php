<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\Permission;

class RolesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.roles.index')->only('index'); // Permiso para index 
        $this->middleware('checkPermission:admin.roles.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.roles.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
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
            // 'number' => 'numeric|required|unique:orders,number',
            'name' => 'string|required|unique:roles,name|max:50',
            'description' => 'string|unique:roles,description|required|max:100',
            'permissions' => 'array|required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $role = new Role;
        $role->name = $request->input('name');
        $role->description = $request->input('description');
        $role->creator_user_id = $request->user()->id;  // usuario logueado
        $role->save();

        // Agregamos los permisos al rol creado
        $role->permissions()->attach($request->input('permissions'));

        return redirect()->route('roles.index')->with('success', 'Rol agregado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permissions = Permission::all();
        return view('admin.roles.update', compact('role', 'permissions'));
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
            'name' => 'string|required|max:50',
            'description' => 'string|required|max:100',           
            'permissions' => 'array|required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos el Rol
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->description = $request->input('description');
        $role->modifier_user_id = $request->user()->id;  // usuario logueado
        $role->save();

        // Modificamos los permisos del rol
        $role->permissions()->sync($request->input('permissions'));

        return redirect()->route('roles.index')->with('success', 'Rol modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.roles.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $role = Role::find($id);
        // Chequeamos si existen permisos vinculados al rol
        if($role->rolePermission->count() > 0){            
            return response()->json(['status' => 'error', 'message' => 'No se ha podido eliminar el rol debido a que se encuentra vinculado a permisos ', 'code' => 200], 200);
        }

        // Chequeamos si existen usuarios vinculados al rol
        if($role->userRole->count() > 0){
            return response()->json(['status' => 'error', 'message' => 'No se ha podido eliminar el rol debido a que se encuentra vinculado a usuarios ', 'code' => 200], 200);
        }
        
        // Eliminamos en caso de no existir permisos ni usuarios vinculados al rol
        $role->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el Rol ' . $role->description, 'code' => 200], 200);
    }
}
