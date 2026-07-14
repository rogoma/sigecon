<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\ExpenditureObject;

class ExpenditureObjectsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.expenditure_objects.index')->only('index'); // Permiso para index 
        $this->middleware('checkPermission:admin.expenditure_objects.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.expenditure_objects.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenditure_objects = ExpenditureObject::all();
        return view('admin.expenditure_objects.index', compact('expenditure_objects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.expenditure_objects.create');
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
            'code' => 'required',            
            'description' => 'string|required|max:150',
            'alias' => 'string|required|max:100',
            'level' => 'required',            
            // 'financial_level_id' => 'required',
            // 'superior_expenditure_object_id' => 'required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $expenditure_object = new ExpenditureObject;
        $expenditure_object->code = $request->input('code');
        $expenditure_object->description = $request->input('description');
        $expenditure_object->alias = $request->input('alias');        
        $expenditure_object->level = $request->input('level');    
        $expenditure_object->financial_level_id = $request->input('financial_level_id');        
        $expenditure_object->superior_expenditure_object_id = $request->input('superior_expenditure_object_id');
        $expenditure_object->creator_user_id = $request->user()->id;  // usuario logueado
        $expenditure_object->save();
        return redirect()->route('expenditure_objects.index')->with('success', 'Objeto de Gasto agregado correctamente');
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
        $expenditure_object = ExpenditureObject::find($id);
        return view('admin.expenditure_objects.update', compact('expenditure_object'));
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
            // 'code' => 'required',            
            'description' => 'string|required|max:150',
            'alias' => 'string|required|max:100',
            'level' => 'required',            
            // 'financial_level_id' => 'required',
            // 'superior_expenditure_object_id' => 'required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos el Estado del objeto de gasto
        $expenditure_object = ExpenditureObject::find($id);
        // $expenditure_object->code = $request->input('code');
        $expenditure_object->description = $request->input('description');
        $expenditure_object->alias = $request->input('alias');        
        $expenditure_object->level = $request->input('level');    
        $expenditure_object->financial_level_id = $request->input('financial_level_id');        
        $expenditure_object->superior_expenditure_object_id = $request->input('superior_expenditure_object_id');
        $expenditure_object->modifier_user_id = $request->user()->id;  // usuario logueado
        $expenditure_object->save();        

        return redirect()->route('expenditure_objects.index')->with('success', 'Objeto de Gasto modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.expenditure_objects.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $expenditure_object = ExpenditureObject::find($id);
        $expenditure_object->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el Objeto de Gasto ' . $expenditure_object->description, 'description' => 200], 200);
    }
}
