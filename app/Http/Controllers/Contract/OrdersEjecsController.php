<?php

namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use App\Models\Order;
use App\Models\Contract;
use App\Models\Department;
use App\Models\District;
use App\Models\Locality;
use App\Models\OrderState;
use App\Models\Component;
use App\Models\ItemContract;
use App\Exports\OrdersExport;
use App\Exports\OrdersExport2;

use Maatwebsite\Excel\Facades\Excel;



class OrdersEjecsController extends Controller
{
    protected $postMaxSize;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $index_permissions = ['admin.orders.index', 'orders.orders.index','orders.orders.events'];
        $create_permissions = ['admin.orders.create', 'orders.orders.create'];
        $update_permissions = ['admin.orders.update', 'orders.orders.update'];

        $this->middleware('checkPermission:' . implode(',', $index_permissions))->only(['index', 'view']); // Permiso para index/view
        $this->middleware('checkPermission:' . implode(',', $create_permissions))->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:' . implode(',', $update_permissions))->only(['edit', 'update']);   // Permiso para update

        // obtenemos el tamaño permitido de subida de archivos del servidor
        if (is_numeric(ini_get('post_max_size'))) {
            $postMaxSize = ini_get('post_max_size');
        } else {
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
        //MÁXIMO PERMITIDO 5 MEGAS POR CADA ARCHIVO
        $this->postMaxSize = 1048576 * 5;
    }

    //Para exportar Ordenes de un contrato a Excel
    public function exportarorders(Request $request, int $contract_id)
    {
        return Excel::download(new OrdersExport($request->contract_id), 'Ordenes.xlsx');
    }

    public function exportarorders2(Request $request)
    {
        return Excel::download(new OrdersExport2, 'Total_Ordenes.xlsx');
    }

    /**
     * Listado de todos los ítems de un pedido.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        $locality = $order->locality;

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if (
            !$request->user()->hasPermission(['admin.items.index', 'process_orders.items.index', 'derive_orders.items.index', 'plannings.items.index']) &&
            $order->dependency_id != $request->user()->dependency_id
        ) {
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        // Obtenemos los items del pedido
        $items = $order->items;
        return view('order.items.index', compact('order', 'items', 'locality'));
    }

    /**
     * Listado de todos los ítems de un pedido.
     *
     * @return \Illuminate\Http\Response
     */
    // public function events(Request $request, $order_id)
    // {
    //     $order = Order::findOrFail($order_id);

    //     // Obtenemos los eventos del pedido
    //     $events = $order->events;
        
    //     if (!$request->user()->hasPermission(['admin.orders.index', 'orders.orders.index']) ){
    //         return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
    //     }
                
    //     return view('contract.orders.events', compact('order', 'events'));
    // }


    public function show(Request $request, $order_id)
    {
        $order = Order::with('contract')->findOrFail($order_id);        
        $contract = $order->contract;
        

        if (!$contract) {
            return redirect()->back()->with('error', 'No se encontró un contrato para esta orden.');
        }
        
        return redirect()->route('contracts.show', $contract->id)->with('success', 'Rubros generados');                
    }
    
    /**
     * Formulario de agregacion de pedido.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);

        // PARA NUMERAR ORDENES DE ACUERDO A LA CANTIDAD
        $order = Order::where('contract_id', $contract_id)->count();

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        // if($request->user()->hasPermission(['admin.orders.create', 'orders.orders.create']) || $contract->dependency_id == $request->user()->dependency_id){
        if ($request->user()->hasPermission(['admin.orders.create', 'orders.orders.create'])) {
            // return view('contract.contracts.show', compact('contract','user_files_pol','user_files_con','other_files_pol','other_files_con'));
        } else {
            return back()->with('error', 'No tiene los suficientes permisos para agregar órdenes.');
        }

        // Obtener los component_id asociados al contrato desde ItemContract
        $componentIds = ItemContract::where('contract_id', $contract_id)
            ->pluck('component_id'); // Extrae solo los IDs de los componentes

        // Obtener los componentes filtrados por los IDs obtenidos
        if ($request->user()->position->id == 11) { //Fiscal de Geología id 11
            $components = Component::whereIn('id', $componentIds)
                ->whereIn('id', [1, 2]) // Filtra los IDs 1 y 2 (Componentes 1.1 Pozo A y 1.2 Pozo B)
                ->orderBy('id')
                ->get();
        } else { //Otros Fiscales (Obras y Electromecánica)
            $components = Component::whereIn('id', $componentIds) //Muestra todos los componentes
                ->whereNotIn('id', [1, 2]) // Excluye los IDs 1 y 2
                ->orderBy('id')
                ->get();
        }

        $order_states = OrderState::all();
        $departments = Department::all();
        $districts = District::all();
        $localities = Locality::all();
        $item_contract = ItemContract::where('contract_id', $contract_id)->get();        

        return view('contract.orders.create', compact('contract', 'order_states',
            'components','departments','districts', 'localities'));
    }

    // PARA ANIDAR COMBOS
    public function fetchDistricts(Request $request)
    {
        $districts = District::where('department_id', $request->department_id)->get();
        return response()->json($districts);
    }

    public function fetchLocalities(Request $request)
    {        
        $localities = Locality::where('district_id', $request->district_id)->get();
        return response()->json($localities);
    }

    /**
     * Funcionalidad de guardado del pedido de ítemes Contrato Abierto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $contract_id)
    {
        $request->validate([
            'component_id' => 'required|integer',
            'plazo' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($request) {
                    if (in_array($request->input('component_id'), [1, 2, 3, 4, 5, 6, 7, 10, 11, 14, 15, 16, 17]) && $value > 30) {
                        $fail('El plazo no puede ser mayor a 30 días para este Sub-componente');
                    }

                    if (in_array($request->input('component_id'), [12, 13]) && $value > 45) {
                        $fail('El plazo no puede ser mayor a 45 días para este Sub-componente');
                    }

                    if (in_array($request->input('component_id'), [8, 9]) && $value > 60) {
                        $fail('El plazo no puede ser mayor a 60 días para este Sub-componente');
                    }
                },
            ],
        ]);

        $rules = array(
            'number' => 'required|integer|min:1', // Asegúrate de que sea un número válido
            // 'total_amount' => 'nullable|string|max:9223372036854775807',
            // 'sign_date' => 'date_format:d/m/Y',
            'department_id' => 'required|numeric',
            'district_id' => 'required|numeric',
            'locality_id' => 'required||numeric',
            'component_id' => 'required|numeric',
            // 'order_state_id'=> 'required|numeric',                        
            'reference' => 'nullable|max:500',
            'comments' => 'nullable|max:500',
            'plazo' => 'required|numeric'            
        );

        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $order = new Order;
        $order->contract_id = $contract_id;
        $order->component_id = $request->input('component_id');
        $component = Component::find($order->component_id);  // Assuming you have a Component model
        $componentCode = $component ? $component->code : null; // Handle the case where the component is not found                
        $order->component_code = $componentCode;
        $order->number = $request->input('number');
        $order->sign_date = $request->filled('sign_date') ? date('Y-m-d', strtotime(str_replace("/", "-", $request->input('sign_date')))) : null;        
        $order->component_id = $request->input('component_id');
        //CUANDO SE GRABA POR VEZ PRIMERA ASUME ESTADO 10= Pendiente Fecha Acuse recibo Contratista

        // Si tiene estado en curso no hace nada
        // $order->order_state_id = 11; // toma estado pendiente de carga de rubros

        if ($order->order_state_id === null) {
            $order->order_state_id = 11; // toma estado pendiente de carga de rubros        
        }

        $order->total_amount = 0;
        $order->reference = $request->input('reference');
        $order->comments = $request->input('comments');
        $order->plazo = $request->input('plazo');
        $order->district_id = $request->input('district_id');
        $order->locality_id = $request->input('locality_id');
        $order->creator_user_id = $request->user()->id;  // usuario logueado
        $order->save();
        return redirect()->route('contracts.show', $contract_id)->with('success', 'Datos de Orden agregado correctamente'); // Caso usuario posee rol pedidos
    }

    //PARA CALCULA NUMERO DE ORDEN DE ACUERDO AL COMPONENTE y LOCALIDAD
    public function getMaxNumber(Request $request)
    {
        $componentId = $request->input('component_id');
        $locality = $request->input('locality_id');

        // Buscar el número máximo solo de los registros con la misma localidad
        $maxNumber = Order::where('component_id', $componentId)
            ->where('locality_id', $locality) // Filtra por localidad
            ->max('number');

        return response()->json([
            'success' => true,
            'number' => $maxNumber ?? 0 // Si no hay registros, devuelve 0
        ]);
    }

    // public function getMaxNumber(Request $request)
    // {
    //     $componentId = $request->query('component_id');

    //     if (!$componentId) {
    //         return response()->json(['success' => false, 'message' => 'Component ID no proporcionado.']);
    //     }

    //     $maxNumber = Order::where('component_id', $componentId)->max('number');


    //     return response()->json([
    //         'success' => true,
    //         'number' => $maxNumber,
    //     ]);
    // }

    /**
     * Formulario de modificacion de pedido
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $contract_id, $order_id)
    {
        $contract = Contract::findOrFail($contract_id);
        $post_max_size = $this->postMaxSize;

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if (!$request->user()->hasPermission(['admin.orders.update', 'orders.orders.update']) &&  $contract->dependency_id != $request->user()->dependency_id) {
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        // Obtener los component_id asociados al contrato desde ItemContract
        $componentIds = ItemContract::where('contract_id', $contract_id)
            ->pluck('component_id'); // Extrae solo los IDs de los componentes

        // Obtener los componentes filtrados por los IDs obtenidos
        if ($request->user()->position->id == 11) { //Fiscal de Geología id 11
            $components = Component::whereIn('id', $componentIds)
                ->whereIn('id', [1, 2]) // Filtra los IDs 1 y 2 (Componentes 1.1 Pozo A y 1.2 Pozo B)
                ->orderBy('id')
                ->get();
        } else { //Otros Fiscales (Obras y Electromecánica)
            $components = Component::whereIn('id', $componentIds) //Muestra todos los componentes
                ->whereNotIn('id', [1, 2]) // Excluye los IDs 1 y 2
                ->orderBy('id')
                ->get();
        }

        $order = Order::findOrFail($order_id);
        // $components = Component::orderBy('id')->get(); //ordenado por id componente
        $order_states = OrderState::all();
        $departments = Department::all();
        $districts = District::all();
        $localities = Locality::all();
        $items = $order->items;        

        return view('contract.orders.update', compact('contract', 'order', 'components', 
        'order_states', 'departments', 'districts', 'localities', 'items', 'post_max_size')); // Se pasa el tamaño máximo permitido para el archivo
    }


    /**
     * Funcionalidad de modificacion del pedido CUANDO ESTIPO CONTRATO 2 = CERRADO
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $contract_id, $order_id)
    {    

        $request->validate([
            'component_id' => 'required|integer',
            'plazo' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($request) {
                    if (in_array($request->input('component_id'), [1, 2, 3, 4, 5, 6, 7, 10, 11, 14, 15, 16, 17]) && $value > 30) {
                        $fail('El plazo no puede ser mayor a 30 días para este Sub-componente');
                    }

                    if (in_array($request->input('component_id'), [12, 13]) && $value > 45) {
                        $fail('El plazo no puede ser mayor a 45 días para este Sub-componente');
                    }

                    if (in_array($request->input('component_id'), [8, 9]) && $value > 60) {
                        $fail('El plazo no puede ser mayor a 60 días para este Sub-componente');
                    }
                },
            ],
        ]);

        $contract = Contract::findOrFail($contract_id);
        $order = Order::findOrFail($order_id);
        $post_max_size = $this->postMaxSize;

        $rules = [
            'component_id' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($request, $order) {
                    $existingRecord = DB::table('orders')
                        ->where('component_id', $request->input('component_id'))
                        ->where('number', $request->input('number'))
                        ->where('locality_id', $request->input('locality_id'))
                        ->where('id', '!=', $order->id) // Permite ignorar el registro actual si se está editando
                        ->exists();

                    if ($existingRecord) {
                        $fail('Ya existe un una Orden:  Localidad/Sub-Componente/Nro, verifique');
                    }
                }
            ],           
            'reference' => 'nullable|string|max:500',
            'comments' => 'nullable|string|max:500',
            'plazo' => 'required|numeric',            
            'district_id' => 'required|numeric',
            'locality_id' => 'required||numeric',        
        ];
        
        // Valida los datos de entrada
        $validatedData = $request->validate($rules);

        // Actualiza la orden con los datos validados
        $order->update($validatedData);

        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        //MANEJO DE ARCHIVO DE FINALIZACIÓN DE LA ORDEN-VERIFICAMOS QUE FIN DE ORDEN NO ESTE VACIO        
        if (!empty($request->input('sign_date_fin')))
        {
            if(!$request->hasFile('file')){
                $validator = Validator::make($request->input(), []);
                $validator->errors()->add('file', 'El campo es requerido, debe ingresar un archivo PDF o WORD');
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
            $fileName = 'fin_orden_'.$order->component_code.'.'.$order->number.'_'.$request->input('sign_date_fin').'.'.$extension; // nombre a guardar
            // Cargamos el archivo en disco privado (storage/app/files), sin acceso público directo
            $path = $request->file('file')->storeAs('files', $fileName);

            $order->file = $fileName; // Guardamos el nombre del archivo en la base de datos
        }

        

        // SI ESTADO = (11) PENDIENTE DE CARGA DE RUBROS Y ACUSE CONTRATISTA Y FINALIZADO SON VACIOS
        if (is_null($request->input('sign_date') && (is_null($request->input('sign_date_fin')))) && $order->order_state_id = 11) {
            $order->order_state_id = 11;        
        }

        if (is_null($request->input('sign_date') && (is_null($request->input('sign_date_fin')))) && $order->order_state_id = 10) {
            $order->order_state_id = 10;        
        }

        if (($request->input('sign_date') && (is_null($request->input('sign_date_fin')))) && $order->order_state_id = 10) {
            $order->order_state_id = 1;        
        }
        
        // CONTROLA QUE ESTE EN ESTADO FINALIZADO Y QUE ESTE CARGADO FECHA DE FINALIZACIÓN
        if ($request->filled('sign_date_fin')) {
            $order->sign_date_fin = date('Y-m-d', strtotime(str_replace("/", "-", $request->input('sign_date_fin'))));
            $order->sign_date = date('Y-m-d', strtotime(str_replace("/", "-", $request->input('sign_date'))));
            $order->order_state_id = 4;            
        } else {
            $order->sign_date = date('Y-m-d', strtotime(str_replace("/", "-", $request->input('sign_date'))));            
            $order->sign_date_fin = null;
        }       

        $order->locality_id = $request->input('locality_id');
        $order->component_id = $request->input('component_id');
        $component = Component::find($order->component_id);  // Assuming you have a Component model
        $componentCode = $component ? $component->code : null; // Handle the case where the component is not found                       
        $order->component_code = $componentCode;

        // Si componente cambia, se cambia el estado de la orden a 22 y se eliminan los items asociados a la orden
        $contract = Contract::findOrFail($contract_id);

        if ($order->isDirty('component_id')) {           
            // Cambiar estado de la orden
            $order->order_state_id = 22; // nuevo estado: reasignar rubros
    
            // Eliminar ítems asociados
            $order->items()->delete();

            // Descontar total de la orden del monto comprometido del contrato
            $contract->compro_amount -= $order->total_amount;
            $order->total_amount = 0; // Reiniciar el total de la orden a 0
            
            // Guardar los cambios en contrato
            $contract->save();
        }

        $order->number = $request->input('number');
        $order->reference = $request->input('reference');
        $order->comments = $request->input('comments');
        $order->plazo = $request->input('plazo');        
        $order->district_id = $request->input('district_id');
        $order->creator_user_id = $request->user()->id;  // usuario logueado
               
        // var_dump($order->order_state_id);exit;
        

        //SI ESTADO ES EN CURSO DEBE GUARDAR FECHA OCULTA created_at_hidd
        if (in_array($order->order_state_id, [1, 4])) {
           $order->created_at = date('Y-m-d', strtotime(str_replace("/", "-", $request->input('created_at_hidd')))); //fecha de la orden  
        } else {
           $order->created_at = date('Y-m-d', strtotime(str_replace("/", "-", $request->input('created_at')))); //fecha de la orden 
        }

        $order->save();
        return redirect()->route('contracts.show', $contract_id)->with('success', 'Orden modificada correctamente'); // Caso usuario posee rol pedidos
    }

    public function destroy(Request $request, $contract_id, $item_id)
    {
        $contract = Contract::findOrFail($contract_id);
        $order = Order::findOrFail($item_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if (!$request->user()->hasPermission(['admin.orders.delete', 'orders.orders.delete']) && $order->contract->dependency_id != $request->user()->dependency_id) {
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para anular la orden.', 'code' => 200], 200);
        }

        // Obtener el monto actual de la orden
        $currentOrderAmount = $order->total_amount;

        // ANULAR Cambia a estado 5 = "Anulado" si es que Estado de la orden está en 1 (En curso)
        if ($order->order_state_id >= 1) {

            $motivo = $request->input('motivo');
        // if (!$motivo) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Debe proporcionar un motivo para anular la orden.',
        //         'code' => 400
        //     ], 400);
        // }

            // Restar el monto actual de la orden al compro_amount del contrato
            $contract->decrement('compro_amount', $currentOrderAmount);

            $order->order_state_id = 5;
            $order->motivo_anule = $motivo;
            $order->save();

            session()->flash('status', 'success');
            session()->flash('message', 'Orden anulada ' . $order->number);

            return response()->json([
                'status' => 'success',
                'message' => 'Orden anulada correctamente ' . $order->number,
                'code' => 200
            ], 200);
        }
        // DESANULAR Cambia a estado 1 = "En curso" si es que Estado de la orden está en 5 (Anulado)
        elseif ($order->order_state_id == 5) {

            // Sumar el monto actual de la orden al compro_amount del contrato
            $contract->increment('compro_amount', $currentOrderAmount);

            $order->order_state_id = 1;
            $order->save();

            session()->flash('status', 'success');
            session()->flash('message', 'Orden Desanulada ' . $order->number);

            return response()->json([
                'status' => 'success',
                'message' => 'Orden Desanulada correctamente ' . $order->number,
                'code' => 200
            ], 200);
        } else {
            // Si la orden no está en estado 1 o 5, no se hace nada
            return response()->json([
                'status' => 'error',
                'message' => 'La orden no puede ser anulada o desanulada en su estado actual.',
                'code' => 200
            ], 200);
        }
    }

    /**
     * Sirve el archivo de finalización de la orden inline (para previsualizar en el navegador),
     * respetando los mismos permisos que index.
     *
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        return Storage::response('files/'.$order->file);
    }
}