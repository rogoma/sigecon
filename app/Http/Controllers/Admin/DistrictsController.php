<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;
use App\Models\District;
use App\Models\Locality;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DistrictExport;

class DistrictsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __constcoddistt()
    {
        $this->middleware('checkPermission:admin.districts.index')->only('index'); // Permiso para index
        $this->middleware('checkPermission:admin.districts.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.districts.update')->only(['edit', 'update']);   // Permiso para update
    }


    //Para exportar Localidades a Excel
    public function exportardistricts()
    {
        return Excel::download(new DistrictExport, 'Distritos.xlsx');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $districts = District::all();
        return view('admin.districts.index', compact('districts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::where('id', '!=', 9999)
                         ->orderBy('id')
                         ->get();
        return view('admin.districts.create', compact('departments'));         
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
            'departments' => 'required|numeric',          
            'description' => 'string|required|max:50|unique:districts'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $district = new District;
        $district->department_id = $request->input('departments');        
        $district->description = $request->input('description');
        $district->creator_user_id = $request->user()->id;  // usuario logueado
        $district->save();

        return redirect()->route('districts.index')->with('success', 'Distrito agregado correctamente');
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
        $district = District::find($id);
        $districts = District::all();
        $departments = Department::where('id', '!=', 9999)->get();
        return view('admin.districts.update', compact('departments','district','districts'));
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
            'departments' => 'required|numeric',            
            'description' => [
                'string',
                'required',
                'max:25',
                Rule::unique('districts')->ignore($id),
            ],            
        );        
        
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos el proveedor
        $district = District::find($id);
        $district->department_id = $request->input('departments');        
        $district->description = $request->input('description');        
        $district->modifier_user_id = $request->user()->id;  // usuario logueado
        $district->save();

        return redirect()->route('districts.index')->with('success', 'Distrito modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.districts.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }


        $district = District::find($id);

        if (method_exists($district, 'localities') && $district->localities()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se ha podido eliminar el distrito debido a que tiene localidades asociadas.',
                'code' => 200
            ], 200);
        }

        // $district->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Se ha eliminado el Distrito: ' . $district->description,
            'code' => 200
        ], 200);
    }
}
