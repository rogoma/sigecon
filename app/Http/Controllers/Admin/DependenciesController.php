<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Dependency;
use App\Models\DependencyType;
use App\Models\UocType;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport4;
use Illuminate\Validation\Rule;

class DependenciesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.dependencies.index')->only('index'); // Permiso para index
        $this->middleware('checkPermission:admin.dependencies.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.dependencies.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dependencies = Dependency::all();
        return view('admin.dependencies.index', compact('dependencies'));
    }

    //Para exportar a Excel usuarios
    public function exportarExcel()
    {
        return Excel::download(new OrdersExport4, 'Dependencias.xlsx');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dependencies = Dependency::all();
        $dependency_types = DependencyType::all();
        $uoc_types = UocType::all();
        return view('admin.dependencies.create', compact('dependencies', 'dependency_types', 'uoc_types'));
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
            'description' => 'required|string|max:150|unique:dependencies,description',
            'dependency_types' => 'required',
            'uoc_types' => 'numeric|nullable|max:999',
            'uoc_number' => 'numeric|nullable|min:1|max:999',
            'superior_dependency' => 'numeric|max:999',
            'sicp' => 'numeric|nullable|min:1|max:99999',
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $dependency = new Dependency;
        $dependency->description = $request->input('description');
        $dependency->dependency_type_id = $request->input('dependency_types');
        $dependency->uoc_type_id = $request->input('uoc_types');
        $dependency->uoc_number = $request->input('uoc_number');
        $dependency->superior_dependency_id = $request->input('superior_dependency');
        $dependency->sicp = $request->input('sicp');
        $dependency->creator_user_id = $request->user()->id;  // usuario logueado
        $dependency->save();

        return redirect()->route('dependencies.index')->with('success', 'Dependencia agregado correctamente');
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
    {   $dependency = Dependency::find($id);
        $dependencies = Dependency::all();
        $dependency_types = DependencyType::all();
        $uoc_types = UocType::all();
        return view('admin.dependencies.update', compact( 'dependency','dependencies','dependency_types', 'uoc_types'));
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
                Rule::unique('dependencies')->ignore($id),
            ],

            // 'description' => 'required|string|max:150|unique:dependencies,description',

            'dependency_types' => 'required',
            'uoc_types' => 'numeric|nullable|max:999',
            'uoc_number' => 'numeric|nullable|min:1|max:999',
            'superior_dependency' => 'numeric|max:999',
            'sicp' => 'numeric|nullable|min:1|max:99999',
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos la dependencia
        $dependency = Dependency::find($id);
        $dependency->description = $request->input('description');
        $dependency->dependency_type_id = $request->input('dependency_types');
        $dependency->uoc_type_id = $request->input('uoc_types');
        $dependency->uoc_number = $request->input('uoc_number');
        $dependency->superior_dependency_id = $request->input('superior_dependency');
        $dependency->sicp = $request->input('sicp');
        $dependency->modifier_user_id = $request->user()->id;  // usuario logueado
        $dependency->save();
        return redirect()->route('dependencies.index')->with('success', 'Usuario modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.dependencies.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $dependency = Dependency::find($id);
        // Chequeamos si existen usuarios referenciando a la dependencia
        if($dependency->users->count() > 0){
            return response()->json(['status' => 'error', 'message' => 'No se ha podido eliminar la dependencia debido a que se encuentra vinculada a usuarios ', 'code' => 200], 200);
        }

        // En caso de no existir usuarios referenciando a la dependencia la eliminamos
        $dependency->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado la dependencia ' . $dependency->description, 'code' => 200], 200);
    }
}
