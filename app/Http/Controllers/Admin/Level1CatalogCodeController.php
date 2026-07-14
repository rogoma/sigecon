<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Level1CatalogCode;

class Level1CatalogCodeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.catalog_level1s.index')->only('index'); // Permiso para index 
        $this->middleware('checkPermission:admin.catalog_level1s.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.catalog_level1s.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $catalog_level1s = Level1CatalogCode::all();
        return view('admin.catalog_level1s.index', compact('catalog_level1s'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.catalog_level1s.create');
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
            'code' => 'integer|required|max:99999999',            
            'description' => 'string|required|max:150'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // ORIGINAL
        // CONTROL DE DUPLICIDAD DE CDP - Convertimos String a Number y preguntamos si ya existe el N° CDP        
        $code = $request->input('code');
        $check_code = Level1CatalogCode::where('code', '=', $code)->count();

        if($check_code > 0){
            $validator->errors()->add('code', 'Código ya existe en catálogo.');
            return back()->withErrors($validator)->withInput();
        }

        $catalog_level1 = new Level1CatalogCode;
        $catalog_level1->code = $request->input('code');
        $catalog_level1->description = $request->input('description');
        $catalog_level1->creator_user_id = $request->user()->id;  // usuario logueado
        $catalog_level1->save();

        return redirect()->route('catalog_level1s.index')->with('success', 'Código de Catalogo Nivel 1 agregado correctamente');
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
        $catalog_level1 = Level1CatalogCode::find($id);
        return view('admin.catalog_level1s.update', compact('catalog_level1'));
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
            'code' => 'integer|required',
            'description' => 'string|required|max:150'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos el catalogo
        $catalog_level1 = Level1CatalogCode::find($id);
        $catalog_level1->code = $request->input('code');
        $catalog_level1->description = $request->input('description');
        $catalog_level1->modifier_user_id = $request->user()->id;  // usuario logueado
        $catalog_level1->save();

        return redirect()->route('catalog_level1s.index')->with('success', 'Catalogo Nivel 1 modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.catalog_level1s.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $catalog_level1 = Level1CatalogCode::find($id);
        
        // Eliminamos en Catalogo Nivel 1 en caso de no estar vinculado
        $catalog_level1->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el Catalogo Nivel 1' . $catalog_level1->description, 'code' => 200], 200);
    }
}