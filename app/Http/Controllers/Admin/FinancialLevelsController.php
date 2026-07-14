<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\FinancialLevel;

class FinancialLevelsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.financial_levels.index')->only('index'); // Permiso para index 
        $this->middleware('checkPermission:admin.financial_levels.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.financial_levels.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $financial_levels = FinancialLevel::all();
        return view('admin.financial_levels.index', compact('financial_levels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.financial_levels.create');
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
            'description' => 'string|required|max:150',
            'number' => 'required|numeric|max:99999999'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
         
        $financial_level = new FinancialLevel;
        $financial_level->description = $request->input('description');
        $financial_level->number = $request->input('number');
        $financial_level->creator_user_id = $request->user()->id;  // usuario logueado
        $financial_level->save();

        return redirect()->route('financial_levels.index')->with('success', 'Nivel Financiero  agregado correctamente');
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
         $financial_level = FinancialLevel::find($id);
        return view('admin.financial_levels.update', compact('financial_level'));
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
            'description' => 'string|required|max:150',
            'number' => 'required|numeric|max:99999999'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos el Nivel Financiero 
        $financial_level = FinancialLevel::find($id);        
        $financial_level->description = $request->input('description');
        $financial_level->number = $request->input('number');
        $financial_level->modifier_user_id = $request->user()->id;  // usuario logueado
        $financial_level->save();

        return redirect()->route('financial_levels.index')->with('success', 'Nivel Financiero  modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.financial_levels.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $financial_level = FinancialLevel::find($id); 
        // Chequeamos si existen objetos de gasto referenciando al nivel financiero
        if($financial_level->expenditureObjects->count() > 0){
           return response()->json(['status' => 'error', 'message' => 'No se ha podido eliminar el nivel financiero debido a que se encuentra vinculado a objetos de gasto ', 'code' => 200], 200);
        }
        
        // Eliminamos en caso de no existir grupos referenciando al nivel financiero
        $financial_level->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el Nivel Financiero  ' . $financial_level->description, 'code' => 200], 200);
    }
}
