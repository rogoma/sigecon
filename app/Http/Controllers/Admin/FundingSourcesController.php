<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\FundingSource;

class FundingSourcesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.funding_sources.index')->only('index'); // Permiso para index 
        $this->middleware('checkPermission:admin.funding_sources.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.funding_sources.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $funding_sources = FundingSource::all();
        return view('admin.funding_sources.index', compact('funding_sources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.funding_sources.create');
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
            'code' => 'numeric|required|max:32767|unique:funding_sources,code',            
            'description' => 'required|string|max:100|unique:funding_sources,description'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        //SE CAPTURA CODIGO DE LA VISTA
        $code = $request->input('code');

        //CHEQUEAMOS QUE CODIGO NO EXISTA EN LA TABLA    
        $check = FundingSource::where('code', $code)->count();        
        if($check > 0){
            $validator->errors()->add('code', 'Código ya existe, Verifique.');
            return back()->withErrors($validator)->withInput();
        }            
        

        $funding_source = new FundingSource;
        $funding_source->description = $request->input('description');
        $funding_source->code = $request->input('code');
        $funding_source->creator_user_id = $request->user()->id;  // usuario logueado
        $funding_source->save();

        return redirect()->route('funding_sources.index')->with('success', 'Fuente de Financiamiento agregado correctamente');
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
        $funding_source = FundingSource::find($id);
        return view('admin.funding_sources.update', compact('funding_source'));
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
                Rule::unique('funding_sources')->ignore($id),
            ],

            'description' => [
                'string',
                'required',
                'max:150',
                Rule::unique('funding_sources')->ignore($id),
            ]
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        

        // obtenemos la Fuente de Financiamiento
        $funding_source = FundingSource::find($id);
        $funding_source->description = $request->input('description');
        // $funding_source->code = $request->input('code');
        $funding_source->modifier_user_id = $request->user()->id;  // usuario logueado
        $funding_source->save();

        return redirect()->route('funding_sources.index')->with('success', 'Fuente de Financiamiento modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.funding_sources.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $funding_source = FundingSource::find($id);
        $funding_source->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado la Fuente de Financiamiento ' . $funding_source->description, 'code' => 200], 200);
    }
}
