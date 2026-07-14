<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\ProgramType;

class ProgramTypesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.program_types.index')->only('index'); // Permiso para index 
        $this->middleware('checkPermission:admin.program_types.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.program_types.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $program_types = ProgramType::all();
        return view('admin.program_types.index', compact('program_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.program_types.create');
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
            'code' => 'numeric|required|max:32767|unique:program_types,code',            
            'description' => 'required|string|max:150|unique:program_types,description'            
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        //SE CAPTURA CODIGO DE LA VISTA
        $code = $request->input('code');

        //CHEQUEAMOS QUE CODIGO NO EXISTA EN LA TABLA    
        $check = ProgramType::where('code', $code)->count();        
        if($check > 0){
            $validator->errors()->add('code', 'Código ya existe, Verifique.');
            return back()->withErrors($validator)->withInput();
        }   

        $program_type = new ProgramType;
        $program_type->description = $request->input('description');
        $program_type->code = $request->input('code');
        $program_type->creator_user_id = $request->user()->id;  // usuario logueado
        $program_type->save();
        return redirect()->route('program_types.index')->with('success', 'Tipo de Programa agregado correctamente');
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
        $program_type = ProgramType::find($id);
        return view('admin.program_types.update', compact('program_type'));
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
            'code' => [
                'numeric',
                'required',
                'max:32767',                
                Rule::unique('program_types')->ignore($id),
            ],

            'description' => [
                'string',
                'required', 
                'max:150',
                Rule::unique('program_types')->ignore($id),
            ],       
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos el Estado del programa
        $program_type = ProgramType::find($id);        
        $program_type->description = $request->input('description');
        $program_type->code = $request->input('code');
        $program_type->modifier_user_id = $request->user()->id;  // usuario logueado
        $program_type->save();        

        return redirect()->route('program_types.index')->with('success', 'Tipo de Programa modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.program_types.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $program_type = ProgramType::find($id);
        $program_type->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el Tipo de Programa ' . $program_type->description, 'code' => 200], 200);
    }
}
