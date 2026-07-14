<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Program;

class ProgramsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.programs.index')->only('index'); // Permiso para index 
        $this->middleware('checkPermission:admin.programs.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.programs.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programs = Program::all();
        return view('admin.programs.index', compact('programs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.programs.create');
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
            'program_type_id' => 'required',
            'description' => 'string|required|max:200',
            'code' => 'required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $program = new Program;
        $program->program_type_id = $request->input('program_type_id');
        $program->description = $request->input('description');
        $program->code = $request->input('code');
        $program->creator_user_id = $request->user()->id;  // usuario logueado
        $program->save();
        return redirect()->route('programs.index')->with('success', 'Programa agregado correctamente');
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
        $program = Program::find($id);
        return view('admin.programs.update', compact('program'));
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
            'program_type_id' => 'required',
            'description' => 'string|required|max:200',
            'code' => 'required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos el Estado del programa
        $program = Program::find($id);
        $program->program_type_id = $request->input('program_type_id');
        $program->description = $request->input('description');
        $program->code = $request->input('code');
        $program->modifier_user_id = $request->user()->id;  // usuario logueado
        $program->save();        

        return redirect()->route('programs.index')->with('success', 'Programa modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.programs.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $program = Program::find($id);
        $program->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el Programa ' . $program->description, 'code' => 200], 200);
    }
}
