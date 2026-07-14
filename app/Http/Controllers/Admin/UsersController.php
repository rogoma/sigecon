<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Order;
use App\Models\Dependency;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Position;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport3;



class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin.users.index')->only('index'); // Permiso para index
        $this->middleware('checkPermission:admin.users.create')->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:admin.users.update')->only(['edit', 'update']);   // Permiso para update
    }

    //Para exportar a Excel usuarios
    public function exportarExcel()
    {
        return Excel::download(new OrdersExport3, 'Usuarios.xlsx');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dependencies = Dependency::all();
        $roles = Role::all();
        $positions = Position::all();

        //para cargar en combo
        $estados = [
            1 => 'Activo',
            2 => 'Inactivo'
        ];
        return view('admin.users.create', compact('dependencies', 'roles', 'positions', 'estados'));
    }

    public function create2()
    {
        $dependencies = Dependency::all();
        $sup_dependencies = Dependency::where('superior_dependency_id', '>', 0)->get();

        return view('admin.users.create2', compact('dependencies','sup_dependencies'));
    }

    public function change_pass()
    {
        $dependencies = Dependency::all();
        $roles = Role::all();
        $positions = Position::all();
        return view('admin.users.change_pass', compact('dependencies', 'roles', 'positions'));
    }

    public function sup_dependencias(Request $request){
        if(isset($request->texto)){
            $sup_dependencias = Dependency::where('superior_dependency_id',($request->texto))->get();
            return response()->json(
                [
                    'lista' => $sup_dependencias,
                    'success' => true
                ]
                );
        }else{
            return response()->json(
                [
                    'success' => false
                ]
                );

        }

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
            'name' => 'string|required|max:150',
            'lastname' => 'string|required|max:150',
            'document' => 'required|numeric|max:999999999999999|unique:users',
            'email' => 'string|nullable|max:100',
            'dependency' => 'required',
            'position' => 'required',
            'role' => 'numeric|required',
            'state' => 'required',
            'contract_admin' => 'boolean|required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = new User;
        $user->name = $request->input('name');
        $user->lastname = $request->input('lastname');
        $user->document = $request->input('document');
        $user->password = Hash::make($request->input('document'));
        $user->email = $request->input('email');
        $user->role_id = $request->input('role');
        $user->dependency_id = $request->input('dependency');
        $user->position_id = $request->input('position');
        $user->contract_admin = $request->input('contract_admin');
        $user->state = $request->input('state');
        $user->creator_user_id = $request->user()->id;  // usuario logueado
        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario agregado correctamente');
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
        $user = User::find($id);
        $dependencies = Dependency::all();
        $roles = Role::all();
        $positions = Position::all();

        //para cargar en combo
        $estados = [
            1 => 'Activo',
            2 => 'Inactivo'
        ];

        return view('admin.users.update', compact('user', 'dependencies', 'roles', 'positions'));
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
            'name' => 'string|required|max:150',
            'lastname' => 'string|required|max:150',
            'document' => 'required|numeric|max:999999999999999',
            'email' => 'string|nullable|max:100',
            'dependency' => 'required',
            'position' => 'required',
            'role' => 'numeric|required',
            'state' => 'required',
            'contract_admin' => 'boolean|required'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos el usuario
        $user = User::find($id);

        // // En caso de haber modificado la cedula chequeamos
        if($request->input('document') != $user->document){
            $check_document = User::where('document', $request->input('document'))->where('id', '!=', $id)->count();
            if($check_document > 0){
                $validator->errors()->add('document', 'La cédula ingresada ya se encuentra vinculada a un usuario.');
                return back()->withErrors($validator)->withInput();
            }
        }

        $user->name = $request->input('name');
        $user->lastname = $request->input('lastname');
        $user->document = $request->input('document');
        $user->email = $request->input('email');
        $user->role_id = $request->input('role');
        $user->dependency_id = $request->input('dependency');
        $user->position_id = $request->input('position');
        $user->contract_admin = $request->input('contract_admin');
        $user->state = $request->input('state');
        $user->modifier_user_id = $request->user()->id;  // usuario logueado
        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario modificado correctamente');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_pass(Request $request, $id)

    {
        $rules = array(
            'document' => ['required', 'numeric', 'max:999999999999999'],   // se admite hasta 15 cifras
            'password' => ['required', 'string', 'max:100'],
            'new_pass' => ['required', 'string', 'max:100'],
            'sec_pass' => ['required', 'string', 'max:100'],
            // 'old_pass' => 'required|max:100'
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // chequeamos si los datos enviados corresponden a algun usuario
        $credentials = $request->only('document', 'password');
        if(Auth::attempt($credentials)){    // intentamos iniciar sesion
            // En caso de inicio de sesión exitoso retornamos a la ruta
            // intentada previamente por el usuario (en caso de no haber ruta intentada redirigimos a home)
            //return redirect()->intended('/');
        }else{
            $validator->errors()->add('bad_credentials', 'Password actual incorrecto');
            return back()->withErrors($validator)->withInput();
        }

        // obtenemos el usuario
        $user = User::find($id);

        // var_dump($user->password);exit();

        // VERIFICA DOBLE ESCRITURA DE PASSWORD
        $pass1 = $request->input('new_pass');
        $pass2 = $request->input('sec_pass');

        if($pass1 == $pass2){

        }else{
            $validator->errors()->add('sec_pass', 'Los datos del nuevo password no coinciden, reintente');
            return back()->withErrors($validator)->withInput();
        }

        // ALMACENA EL CONTENIDO DE NUEVO PASSWORD
        $user->password = Hash::make($request->input('sec_pass'));
        $user->creator_user_id = $request->user()->id;  // usuario logueado
        $user->save();

        //RETORNA A VISTA HOME
        return redirect()->route('home')->with('success', 'Password modificado correctamente');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reset_pass(Request $request, $id)

    {
        // obtenemos el usuario
        $user = User::find($id);
        $document = $user->document;

        // var_dump($user->password);exit();

        // ALMACENA COMO PASSWORD EL NUMERO DE DOUMENTO DEL USUARIO
        $user->password = Hash::make($document);
        $user->creator_user_id = $request->user()->id;  // usuario logueado
        $user->save();

        //RETORNA A VISTA INDEX
        return redirect()->route('users.index')->with('success', 'Password reseteado correctamente');

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
        if(!$request->user()->hasPermission(['admin.users.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }


        // var_dump($id);exit();
        // print_r($id);exit;
        // $usuario = Usuario::find($request->input('usuario_id'));


        // $orders = Order::find($id);
        // var_dump($orders);exit();

        // $validator =  Validator::make($request->input());
        // if ($validator->fails()) {
        //     return back()->withErrors($validator)->withInput();
        // }



        $user = User::find($id);

        // CONTROL DE DUPLICIDAD DE N° LLAMADO - preguntamos si ya existe el Numero de LLamado
        // $check_user = Order::where('creator_user_id', '==', $user)->count();

        // if($check_user > 0){
        //     // $validator->errors()->add('number', 'Número de Llamado ya se encuentra vinculado a un pedido.');
        //     // return back()->withErrors($validator)->withInput();
        //     return response()->json(['status' => 'success', 'message' => 'NO Se ha eliminado el usuario ' . $user->getFullName(), 'code' => 200], 200);
        // }

        // var_dump($user->name);exit();
        // var_dump($user->name);


        //Chequeamos si existen usuarios referenciando a departamentos
        // if($user->id > 0){
        //     return response()->json(['status' => 'error', 'message' => 'No se ha podido eliminar el distrito debido a que se encuentra vinculada a Departamentos ', 'code' => 200], 200);
        // // }else{
        // //     return response()->json(['status' => 'error', 'message' => 'No entró ', 'code' => 200], 200);
        // }


        // Eliminamos el usuario
        //$user->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el usuario ' . $user->getFullName(), 'code' => 200], 200);
    }
}
