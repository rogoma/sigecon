<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Provider;
use App\Models\Order;

class ProvidersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.providers.index')->only('index'); // Permiso para index
        $this->middleware('checkPermission:admin.providers.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.providers.update')->only(['edit', 'update']);   // Permiso para update
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers = Provider::all();
        return view('admin.providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.providers.create');
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
            'description' => 'required|string|max:200|unique:providers,description',
            'ruc' => 'required|string|max:15|unique:providers,ruc',
            'telefono' => 'string|nullable|max:50',
            'email_oferta' => 'email|nullable|max:60',
            'email_ocompra' => 'nullable|email|max:60',
            'representante' => 'string|nullable|max:150'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $provider = new Provider;
        $provider->description = $request->input('description');
        $provider->ruc = $request->input('ruc');
        $provider->telefono = $request->input('telefono');
        $provider->email_oferta = $request->input('email_oferta');
        $provider->email_ocompra = $request->input('email_ocompra');
        $provider->representante = $request->input('representante');
        $provider->creator_user_id = $request->user()->id;  // usuario logueado
        $provider->save();

        return redirect()->route('providers.index')->with('success', 'Proveedor agregado correctamente');
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
        $provider = Provider::find($id);
        return view('admin.providers.update', compact('provider'));
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
            'ruc' => [
                'string',
                'required', 
                'max:25',
                Rule::unique('providers')->ignore($id),
            ],

            'description' => [
                'string',
                'required', 
                'max:200',
                Rule::unique('providers')->ignore($id),
            ],
            
            'telefono' => 'string|nullable|max:50',
            'email_oferta' => 'email|nullable|max:60',
            'email_ocompra' => 'nullable|email|max:60',
            'representante' => 'string|nullable|max:150'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        // obtenemos el proveedor
        $provider = Provider::find($id);

        // // En caso de haber modificado el RUC chequeamos
        if($request->input('ruc') != $provider->ruc){
            $check_document = Provider::where('ruc', $request->input('ruc'))->where('id', '!=', $id)->count();
            if($check_document > 0){
                $validator->errors()->add('ruc', 'El RUC ingresado ya se encuentra vinculado a un proveedor.');
                return back()->withErrors($validator)->withInput();
            }
        }

        $provider->description = $request->input('description');
        $provider->ruc = $request->input('ruc');
        $provider->telefono = $request->input('telefono');
        $provider->email_oferta = $request->input('email_oferta');
        $provider->email_ocompra = $request->input('email_ocompra');
        $provider->representante = $request->input('representante');
        $provider->modifier_user_id = $request->user()->id;  // usuario logueado
        $provider->save();

        return redirect()->route('providers.index')->with('success', 'Proveedor modificado correctamente');
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
        if(!$request->user()->hasPermission(['admin.providers.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        //controla si proveedor está asociado a items_awards
        $provider = Provider::find($id);

        // // Chequeamos si existen usuarios referenciando a la dependencia
        // if($provider->orders->count() > 0){
        //     return response()->json(['status' => 'error', 'message' => 'No se ha podido eliminar el proveedor debido a que se encuentra vinculado a detalle de items', 'code' => 200], 200);
        // }

        // Eliminamos en caso de no existir usuarios vinculados al proveedor
        $provider->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el Proveedor ' . $provider->description, 'code' => 200], 200);
    }
}
