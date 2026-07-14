<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\SubProgram;
use App\Models\Program;
use App\Models\ProgramMeasurementUnit;

class SubProgramsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.sub_programs.index')->only('index'); // Permiso para index 
        $this->middleware('checkPermission:admin.sub_programs.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.sub_programs.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sub_programs = SubProgram::all();
        return view('admin.sub_programs.index', compact('sub_programs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sub_programs  = SubProgram::all();
        $programs = Program::all();
        $program_measurement_units = ProgramMeasurementUnit::all();
        return view('admin.sub_programs.create');
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
            'program_id' => 'required',            
            'description' => 'string|required|max:150',
            'activity_code' => 'required',
            'proyecto' => 'string|required|max:10',            
            'program_measurement_unit_id' => 'required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $sub_program = new SubProgram;
        $sub_program->program_id = $request->input('program_id');
        $sub_program->description = $request->input('description');
        $sub_program->activity_code = $request->input('activity_code');        
        $sub_program->proyecto = $request->input('proyecto');        
        $sub_program->program_measurement_unit_id = $request->input('program_measurement_unit_id');
        $sub_program->creator_user_id = $request->user()->id;  // usuario logueado
        $sub_program->save();
        return redirect()->route('sub_programs.index')->with('success', 'Sub_Programa agregado correctamente');
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
        $sub_program = SubProgram::find($id);
        $sub_programs = SubProgram::all();
        $programs = Program::all();
        $program_measurement_units = ProgramMeasurementUnit::all();
        return view('admin.sub_programs.update', compact('sub_program','sub_programs','programs','program_measurement_units'));
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
            'program_id' => 'required',            
            'description' => 'string|required|max:150',
            'activity_code' => 'required',
            'proyecto' => 'string|required|max:10',            
            'program_measurement_unit_id' => 'required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos el Estado del programa
        $sub_program = SubProgram::find($id);
        $sub_program->program_id = $request->input('program_id');        
        $sub_program->description = $request->input('description');
        $sub_program->activity_code = $request->input('activity_code');        
        $sub_program->proyecto = $request->input('proyecto');        
        $sub_program->program_measurement_unit_id = $request->input('program_measurement_unit_id');
        $sub_program->modifier_user_id = $request->user()->id;  // usuario logueado
        $sub_program->save();        

        return redirect()->route('sub_programs.index')->with('success', 'Sub_Programa modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.sub_programs.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $sub_program = SubProgram::find($id);
        $sub_program->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el Sub_Programa ' . $sub_program->description, 'code' => 200], 200);
    }
}
