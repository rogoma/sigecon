<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Level4CatalogCode;
use App\Models\Level5CatalogCode;
use App\Models\Level1CatalogCode;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\VarDumper;

class Level5CatalogCodeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.catalog_level5s.index')->only('index'); // Permiso para index
        $this->middleware('checkPermission:admin.catalog_level5s.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.catalog_level5s.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $catalog_level5s = Level5CatalogCode::all();
        return view('admin.catalog_level5s.index', compact('catalog_level5s'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $catalog_level4s = Level4CatalogCode::all();
        return view('admin.catalog_level5s.create', compact('catalog_level4s'));
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
            'level4_catalog_code' => 'numeric|required|max:99999999',
            // 'level4_catalog_description'=> 'string|required',
            // 'level4_catalog_code_id'=> 'required',
            'code' => 'numeric|required|max:9999',
            'description' => 'string|required|max:200'
            // ax:999999999999999|unique:users
            // 'catalog_level4s' => 'required',
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if(empty($request->input('level4_catalog_description'))){
            $validator->errors()->add('level4_catalog_description', 'Buscar y seleccionar código de Cát. 4');
            return back()->withErrors($validator)->withInput();
        }else{
            // $validator->errors()->add('cdp_date', 'INGRESE Fecha del CDP');
            // return back()->withErrors($validator)->withInput();
        }

        if(empty($request->input('level4_catalog_code_id'))){
            $validator->errors()->add('level4_catalog_code_id', 'Buscar y seleccionar código de Cát. 4');
            return back()->withErrors($validator)->withInput();
        }else{
            // $validator->errors()->add('cdp_date', 'INGRESE Fecha del CDP');
            // return back()->withErrors($validator)->withInput();
        }

         //SE CONCATENAN LOS DATOS DE LOS CODIGOS CAT4 Y CAT5
         $code4 = $request->input('level4_catalog_code');
         $code5 = $request->input('code');
         $codes = $code4."-".$code5;

        // CONTROL DE DUPLICIDAD DE CODIGO NIVEL 5
        $code = $request->input('code');
        $check_code = Level5CatalogCode::where('code', '=', $codes)->count();

        if($check_code > 0){
            $validator->errors()->add('code',  $codes.' Código ya existe en catálogo.');
            return back()->withErrors($validator)->withInput();
        }

        $catalog_level5 = new Level5CatalogCode;

        // $rest = substr($request->input('code'), 0, 8);
        // $catalog_level5->code = $rest;
        $catalog_level5->level4_catalog_code_id = $request->input('level4_catalog_code_id');
        $catalog_level5->code = $codes;
        $catalog_level5->description = $request->input('description');
        $catalog_level5->creator_user_id = $request->user()->id;  // usuario logueado
        $catalog_level5->save();

        return redirect()->route('catalog_level5s.index')->with('success', 'Código de Catalogo Nivel 5 agregado correctamente');
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
        // NO SE USA POR EL MOMENTO
        // $catalog_level4s = Level4CatalogCode::all();
        // $catalog_level5 = Level5CatalogCode::find($id);
        // return view('admin.catalog_level5s.update', compact('catalog_level4s','catalog_level5'));
    }

    /**
     * Visualización de un pedido
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit_c5(Request $request, $id)
    {

        $catalog_level5 = Level5CatalogCode::findOrFail($id);

        //BUSCAMOS VALORES EN CATALOGO4 A PARTIR DEL ID QUE SE ENCUENTRA EN CATALOGO 5
        $catalog_level4 = Level4CatalogCode::findOrFail($catalog_level5->level4_catalog_code_id);

        // var_dump($catalog_level5);exit();
        // var_dump($catalog_level5->level4_catalog_code_id);
        // $catalog_level4 = Level4CatalogCode::where id = $catalog_level5->level4_catalog_code_id;
        // $catalog_level4 = Level5CatalogCode::where('level4_catalog_codes')
        // ->select(['code'])
        // ->where('id', '=', $catalog_level5->level4_catalog_code_id)
        // ->get();

        // $catalog_level4 = Level4CatalogCode::where('id', '=', $catalog_level5->level4_catalog_code_id)
        //                                      ->get();

        return view('admin.catalog_level5s.update', compact('catalog_level5','catalog_level4'));
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
        //solo se actualizan codigo y descripción de CatN5
        $rules = array(
            'catalog_level4s' => 'numeric|required|max:99999999',
            'code' => 'numeric|required|max:9999',
            'description' => 'string|required|max:200'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        //SE CONCATENAR LOS DATOS DE LOS CODIGOS CAT4 Y CAT5
        $code4 = $request->input('catalog_level4s');
        $code5 = $request->input('code');
        $codes = $code4."-".$code5;

        // $catalog_level5 = Level5CatalogCode::findOrFail($id);
        //BUSCAMOS VALORES EN CATALOGO4 A PARTIR DEL ID QUE SE ENCUENTRA EN CATALOGO 5
        // $catalog_level4 = Level4CatalogCode::findOrFail($catalog_level5->level4_catalog_code_id);

        // CONTROL DE DUPLICIDAD DE CODIGO NIVEL 5
        $code = $request->input('code');
        $check_code = Level5CatalogCode::where('code', '=', $codes)->count();

        //BUSCAMOS VALORES EN CATALOGO4 A PARTIR DEL ID QUE SE ENCUENTRA EN CATALOGO 5
        // $check_code = Level5CatalogCode::find($code4);
        // var_dump($check_code);exit();

        // $code = Level5CatalogCode::where('code', '=', $codes)->get();

        if($check_code > 0){
            $validator->errors()->add('code',  $codes.' Código ya existe en catálogo.');
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos el catalogoN5
        $catalog_level5 = Level5CatalogCode::find($id);
        $catalog_level5->code = $codes;
        $catalog_level5->description = $request->input('description');
        $catalog_level5->modifier_user_id = $request->user()->id;  // usuario logueado
        $catalog_level5->save();

        return redirect()->route('catalog_level5s.index')->with('success', 'Catalogo Nivel 5 modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.catalog_level5s.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $catalog_level5 = Level5CatalogCode::find($id);

        // Chequeamos si existen código de catálogo referenciando a algún pedido (item)
        // if($catalog_level5->users->count() > 0){
        //     return response()->json(['status' => 'error', 'message' => 'No se ha podido eliminar la dependencia debido a que se encuentra vinculada a usuarios ', 'code' => 200], 200);
        // }

        // Eliminamos en Catalogo Nivel 5 en caso de no estar vinculado
        $catalog_level5->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el Catalogo Nivel 5' . $catalog_level5->description, 'code' => 200], 200);
    }
}
