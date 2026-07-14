<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\FinancialOrganism;

class FinancialOrganismsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.financial_organisms.index')->only('index'); // Permiso para index 
        $this->middleware('checkPermission:admin.financial_organisms.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.financial_organisms.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $financial_organisms = FinancialOrganism::all();
        return view('admin.financial_organisms.index', compact('financial_organisms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.financial_organisms.create');
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
            'code' => 'numeric|required|max:32767|unique:financial_organisms,code',            
            'description' => 'required|string|max:100|unique:financial_organisms,description'
            
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
         
        //SE CAPTURA CODIGO DE LA VISTA
        $code = $request->input('code');

        //CHEQUEAMOS QUE CODIGO NO EXISTA EN LA TABLA    
        $check = FinancialOrganism::where('code', $code)->count();        
        if($check > 0){
            $validator->errors()->add('code', 'Código ya existe, Verifique.');
            return back()->withErrors($validator)->withInput();
        }   

        $financial_organism = new FinancialOrganism;
        $financial_organism->description = $request->input('description');
        $financial_organism->code = $request->input('code');
        $financial_organism->creator_user_id = $request->user()->id;  // usuario logueado
        $financial_organism->save();

        return redirect()->route('financial_organisms.index')->with('success', 'Organismo Financiero  agregado correctamente');
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
         $financial_organism = FinancialOrganism::find($id);
        return view('admin.financial_organisms.update', compact('financial_organism'));        
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
                Rule::unique('financial_organisms')->ignore($id),
            ],

            'description' => [
                'string',
                'required', 
                'max:100',
                Rule::unique('financial_organisms')->ignore($id),
            ],
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos el Organismo Financiero 
        $financial_organism = FinancialOrganism::find($id);        
        $financial_organism->description = $request->input('description');
        $financial_organism->code = $request->input('code');
        $financial_organism->modifier_user_id = $request->user()->id;  // usuario logueado
        $financial_organism->save();

        return redirect()->route('financial_organisms.index')->with('success', 'Organismo Financiero  modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.financial_organisms.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $financial_organism = FinancialOrganism::find($id); 
        $financial_organism->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el Organismo Financiero  ' . $financial_organism->description, 'code' => 200], 200);
    }
}
