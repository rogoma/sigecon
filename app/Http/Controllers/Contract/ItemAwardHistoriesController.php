<?php

// namespace App\Http\Controllers\contract;
namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Item;
use App\Models\ItemAwardHistory;
use App\Models\ItemAwardType;
use Illuminate\Validation\Rule;

class ItemAwardHistoriesController extends Controller
{
    protected $postMaxSize;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $index_permissions = ['admin.item_award_histories.index',
                            'contracts.item_award_histories.index',
                            'process_contracts.item_award_histories.index',
                            'derive_contracts.item_award_histories.index',
                            'plannings.item_award_histories.index'];
        $create_permissions = ['admin.item_award_histories.create',
                            'contracts.item_award_histories.create',
                            'plannings.item_award_histories.create'];
        $update_permissions = ['admin.item_award_histories.update',
                            'contracts.item_award_histories.update',
                            'plannings.item_award_histories.update'];

        $this->middleware('checkPermission:'.implode(',',$index_permissions))->only(['index', 'view']); // Permiso para index/view
        $this->middleware('checkPermission:'.implode(',',$create_permissions))->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:'.implode(',',$update_permissions))->only(['edit', 'update']);   // Permiso para update

         // obtenemos el tamaño permitido de subida de archivos del servidor
         if (is_numeric(ini_get('post_max_size'))) {
            $postMaxSize = ini_get('post_max_size');
        }else{
            $metric = strtoupper(substr(ini_get('post_max_size'), -1));
            $postMaxSize = (int) ini_get('post_max_size');

            switch ($metric) {
                case 'K':
                    $postMaxSize = $postMaxSize * 1024;
                    break;
                case 'M':
                    $postMaxSize = $postMaxSize * 1048576;
                    break;
                case 'G':
                    $postMaxSize = $postMaxSize * 1073741824;
                    break;
                default:
                    $postMaxSize = 8 * 1024 * 1024;
                    break;
            }
        }
        // $this->postMaxSize = $postMaxSize;
        //MÁXIMO PERMITIDO 2 MEGAS POR CADA ARCHIVO
        $this->postMaxSize = 1048576 * 2;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $item_id)
    {
        // ordenamos por fecha de creación
        $item = Item::where('id', $item_id)->orderBy('created_at', 'asc')->first();

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.item_award_histories.index','contracts.item_award_histories.index'])){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        // $item_award_histories = $item->itemAwardHistories;

        return view('contract.item_award_histories.index', compact('item'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $item_award_types = ItemAwardType::all();
        $post_max_size = $this->postMaxSize;

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.item_award_histories.create', 'contracts.item_award_histories.create']) &&
            $item->contract->dependency_id != $request->user()->dependency_id){
            // return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        // return view('contract.item_award_histories.create', compact('item'));
        return view('contract.item_award_histories.create', compact('item', 'item_award_types','post_max_size'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $item_id)
    {
        $rules = array(
            'item_award_type_id' => 'numeric|required|max:2147483647',
            'number_policy' => 'string|required|unique:items,number_policy|unique:item_award_histories,number_policy',
            'item_from' => 'date_format:d/m/Y',
            'item_to' => 'required|date_format:d/m/Y',
            'amount' => 'nullable|string|max:9223372036854775807',
            'comments' => 'nullable|max:300'
        );

        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // var_dump(
        //     $request->number_policy,
        //     $request->amount,
        //     $request->hasFile('file'),
        // );exit;

        if(!$request->hasFile('file')){
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('file', 'El campo es requerido, debe ingresar un archivo WORD o PDF');
            return back()->withErrors($validator)->withInput();
        }

        // chequeamos la extension del archivo subido
        $extension = $request->file('file')->getClientOriginalExtension();
        if(!in_array($extension, array('doc', 'docx', 'pdf'))){
            $validator = Validator::make($request->input(), []); // Creamos un objeto validator
            $validator->errors()->add('file', 'El archivo introducido debe corresponder a alguno de los siguientes formatos: doc, docx, pdf'); // Agregamos el error
            return back()->withErrors($validator)->withInput();
        }

        // Pasó todas las validaciones, guardamos el archivo
        // $fileName = time().'-addendum-file.'.$extension; // nombre a guardar
        $fileName = 'endoso_nro_'.$request->input('number_policy').'.'.$extension; // nombre a guardar
        // Cargamos el archivo en disco privado (storage/app/files), sin acceso público directo
        $path = $request->file('file')->storeAs('files', $fileName);

        $itemA = new ItemAwardHistory;
        $itemA->item_id = $item_id;
        $itemA->number_policy = $request->input('number_policy');
        $itemA->item_from = date('Y-m-d', strtotime(str_replace("/", "-", $request->input('item_from'))));
        $itemA->item_to = date('Y-m-d', strtotime(str_replace("/", "-", $request->input('item_to'))));

        $amount = str_replace('.', '',($request->input('amount')));
        // monto de póliza del form create
        $amount_poliza = $request->input('tot');

        if ($amount === '' ) {
            $validator->errors()->add('amount', 'Ingrese Monto');
            return back()->withErrors($validator)->withInput();
        }

        if ($amount < 0 ) {
            $validator->errors()->add('amount', 'Monto no puede ser negativo');
            return back()->withErrors($validator)->withInput();
        }else{
            $itemA->amount = $amount;
        }

        // consulta si tipo endoso = 1 o 2 (plazo, vigencia o monto) para cambiar a estado inactivo el registo anterior que posee mismo tipo de endoso
        $tipo_endoso_id = $request->input('item_award_type_id');

        //tipo endoso (item_award_type_id): 1 = PLAZO O VIGENCIA 2= MONTO 3= OTRAS MODIF.
        if ($tipo_endoso_id == 1 || $tipo_endoso_id == 2) {
            // se consulta si hay endosos con tipo_endoso 1 o 2 y si su estado es 1 = ACTIVO
            $check1 = ItemAwardHistory::where('item_award_type_id', '=', 1)//PLAZO O VIGENCIA
                                    ->where('item_id', '=', $item_id)
                                    ->where('state_id', '=', 1)
                                    ->first();

            $check2 = ItemAwardHistory::where('item_award_type_id', '=', 2)//MONTO
                                    ->where('item_id', '=', $item_id)
                                    ->where('state_id', '=', 1)
                                    ->first();

            //Si hay resultado de la consulta, sino se asume el item_award_type_id
            if ($check1) {
                $check1->state_id = 2; // Cambia el state_id a 2 (inactivo) del registro de la sentencia consultado
                $check1->save();
                $itemA->item_award_type_id = $request->input('item_award_type_id');
            }else{
                $itemA->item_award_type_id = $request->input('item_award_type_id');
            }

            //Si hay resultado de la consulta, sino se asume el item_award_type_id
            if ($check2) {
                $check2->state_id = 2; // Cambia el state_id a 2 (inactivo) del registro de la sentencia consultado
                $check2->save();
                $itemA->item_award_type_id = $request->input('item_award_type_id');
            }else{
                $itemA->item_award_type_id = $request->input('item_award_type_id');
            }
        //tipo endoso (item_award_type_id): 3= OTRAS MODIF.
        }else{
            $itemA->item_award_type_id = $request->input('item_award_type_id');
        }

        //SE DEJA DE CONTROLAR MONTO DE POLIZA VS MONTO DE ENDOSO
        // if ($amount > $amount_poliza) {
        //     $validator->errors()->add('amount', 'Monto no puede ser mayor a monto póliza');
        //     return back()->withErrors($validator)->withInput();
        // }else{
             $itemA->amount = $amount;
        // }

        $itemA->comments = $request->input('comments');
        $itemA->file = $fileName;
        $itemA->file_type = 2;//endoso
        $itemA->state_id = 1;
        $itemA->creator_user_id = $request->user()->id;  // usuario logueado
        $itemA->save();
        return redirect()->route('items.item_award_histories.index', $item_id)->with('success', 'Endoso agregado correctamente'); // Caso usuario posee rol pedidos
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $item_id, $itemA_id)
    {
        // $item = ItemAwardHistory::findOrFail($item_id);

        $item = Item::findOrFail($item_id);
        $item_award_types = ItemAwardType::all();
        $post_max_size = $this->postMaxSize;

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.item_award_histories.create', 'contracts.item_award_histories.create']) &&
        $item->contract->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        $itemA = ItemAwardHistory::findOrFail($itemA_id);

        return view('contract.item_award_histories.update', compact('item','itemA','item_award_types','post_max_size'));
    }

    /**
 * Update the specified resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $item_id
 * @param  int  $itemA_id
 * @return \Illuminate\Http\Response
 */
public function update(Request $request, $item_id, $itemA_id)
{
    // Encontrar los modelos correspondientes o lanzar una excepción
    $item = Item::findOrFail($item_id);
    $itemA = ItemAwardHistory::findOrFail($itemA_id);

    // Reglas de validación
    $rules = [
        'item_award_type_id' => 'numeric|required|max:2147483647',
        'number_policy' => [
            'string',
            'required',
            Rule::unique('item_award_histories')->ignore($itemA->id),
            Rule::unique('items'),
        ],
        'item_from' => 'date_format:d/m/Y',
        'item_to' => 'required|date_format:d/m/Y',
        'amount' => 'nullable|string|max:9223372036854775807',
        'file' => 'nullable|file|max:2040', // Ejemplo para archivo de hasta 2 MB
        'comments' => 'nullable|max:300'
    ];

    // Validar los datos de entrada
    $validatedData = $request->validate($rules);

    // Manejar la validación manual adicional para los archivos
    if ($request->hasFile('file')) {
        $extension = $request->file('file')->getClientOriginalExtension();
        if (!in_array($extension, ['doc', 'docx', 'pdf'])) {
            return back()->withErrors(['file' => 'El archivo debe ser de tipo: doc, docx o pdf.'])->withInput();
        }

        // Guardar el archivo con un nombre único
        $fileName = 'endoso_nro_' . $request->input('number_policy') . '.' . $extension;
        $path = $request->file('file')->storeAs('files', $fileName);

        // Guardar el nombre del archivo en la base de datos
        $itemA->file = $fileName;
    }

    // Convertir las fechas al formato adecuado
    $itemA->item_award_type_id = $validatedData['item_award_type_id'];
    $itemA->number_policy = $validatedData['number_policy'];
    $itemA->item_from = date('Y-m-d', strtotime(str_replace("/", "-", $validatedData['item_from'])));
    $itemA->item_to = date('Y-m-d', strtotime(str_replace("/", "-", $validatedData['item_to'])));

    // Manejar el campo amount
    $amount = str_replace('.', '', $validatedData['amount']);
    if ($amount === '') {
        return back()->withErrors(['amount' => 'Ingrese Monto'])->withInput();
    }
    $itemA->amount = $amount;

    // Actualizar otros campos
    $itemA->comments = $validatedData['comments'];
    $itemA->file_type = 2; // endoso
    $itemA->state_id = 1;
    $itemA->creator_user_id = $request->user()->id; // usuario logueado

    // Guardar los cambios
    $itemA->save();

    // Redirigir con mensaje de éxito
    return redirect()->route('items.item_award_histories.index', $item_id)
                     ->with('success', 'Se ha modificado exitosamente el endoso de la póliza');
}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $item_id
     * @param  int  $itemA_id
     * @return \Illuminate\Http\Respons
     */
    public function update_orig(Request $request, $item_id, $itemA_id)
    {
        $item = Item::findOrFail($item_id);
        $itemA = ItemAwardHistory::findOrFail($itemA_id);

        $rules = array(
            'item_award_type_id' => 'numeric|required|max:2147483647',
            'number_policy' => [
                'string',
                'required',
                Rule::unique('item_award_histories')->ignore($itemA->id),
                Rule::unique('items'),                
            ],
            'item_from' => 'date_format:d/m/Y',
            'item_to' => 'required|date_format:d/m/Y',
            'amount' => 'nullable|string|max:9223372036854775807',
            'file' => 'nullable|file|max:2040', // Ejemplo para archivo de hasta 2 MB
            'comments' => 'nullable|max:300'
        );

        // Valida los datos de entrada
        $validatedData = $request->validate($rules);

        // Actualiza el item con los datos validados
        $itemA->update($validatedData);


        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Muestra desde la vista el nombre del archivo que está en un label
        $filename = $request->input('filename');        

        if ($request->hasFile('file')) {
            // Obtén la extensión del archivo (omite validación)
            $extension = $request->file('file')->getClientOriginalExtension();
            if(!in_array($extension, array('doc', 'docx', 'pdf'))){
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('file', 'El archivo introducido debe corresponder a alguno de los siguientes formatos: doc, docx, pdf'); // Agregamos el error
                return back()->withErrors($validator)->withInput();
            }

            // Guarda el archivo con un nombre único           
            $fileName = 'endoso_nro_'.$request->input('number_policy').'.'.$extension; // nombre a guardar
            $path = $request->file('file')->storeAs('public/files', $fileName);

            // Capturamos nombre del archivo almacenado en la tabla
            $filename = $itemA->file;            
        }        
        $itemA->item_award_type_id = $request->input('item_award_type_id');
        $itemA->number_policy = $request->input('number_policy');
        $itemA->item_from = date('Y-m-d', strtotime(str_replace("/", "-", $request->input('item_from'))));
        $itemA->item_to = date('Y-m-d', strtotime(str_replace("/", "-", $request->input('item_to'))));
        $amount = str_replace('.', '',($request->input('amount')));
        if ($amount === '' ) {
            $validator->errors()->add('amount', 'Ingrese Monto');
            return back()->withErrors($validator)->withInput();
        }
        $itemA->amount = $amount;
        $itemA->comments = $request->input('comments');        
        $itemA->file_type = 2;//endoso
        $itemA->state_id = 1;
        $itemA->creator_user_id = $request->user()->id;  // usuario logueado
        $itemA->save();
        return redirect()->route('items.item_award_histories.index',$item_id)->with('success', 'Se ha modificado exitosamente el endoso de la póliza'); // Caso usuario posee rol pedidos
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $item_id, $itemA_id)
    {
        $item = Item::findOrFail($item_id);

        $itemA = ItemAwardHistory::findOrFail($itemA_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.item_award_histories.delete']) &&
        ($item->contract->dependency_id != $request->user()->dependency_id && $request->user()->hasPermission(['contracts.item_award_histories.delete'])) ){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        // Capturamos nombre del archivo almacenado en la tabla
        $filename = $itemA->file;
        // var_dump($filename);exit;

        // Eliminamos el archivo del disco privado
        Storage::delete('files/'.$filename);


        // Eliminamos en caso de no existir registros referenciando al item
        $itemA->delete();
        session()->flash('status', 'success');
        session()->flash('message', 'Se ha eliminado el endoso ' . $itemA->number_policy);

        return response()->json([
            'status' => 'success',
            'message' => 'Se ha eliminado el endoso'. $itemA->number_policy,
            'code' => 200
        ], 200);


        // $request->session()->flash('success', 'Se ha eliminado el endoso referencial a la póliza');
        // return response()->json(['status' => 'success', 'code' => 200], 200);
    }

    /**
     * Sirve el archivo del endoso inline (para previsualizar en el navegador), respetando los mismos permisos que index.
     *
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request, $itemA_id)
    {
        $itemA = ItemAwardHistory::findOrFail($itemA_id);
        return Storage::response('files/'.$itemA->file);
    }
}
