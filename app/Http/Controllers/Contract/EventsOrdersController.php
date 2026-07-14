<?php

// namespace App\Http\Controllers\contract;
namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Item;
use App\Models\ItemAwardHistory;

use Illuminate\Validation\Rule;
use Carbon\Carbon;

class EventsOrdersController extends Controller
{
    protected $postMaxSize;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $index_permissions = ['admin.orders_events.index', 'orders.orders_events.index'];
        $create_permissions = ['admin.orders_events.create', 'orders.orders_events.create'];
        $update_permissions = ['admin.orders_events.update', 'orders.orders_events.update'];

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
    public function index(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        $contract = $order->contract; // Accedemos a la relación contract
        $components = $order->components; // Obtenemos los componentes del pedido

        // Obtenemos los eventos del pedido
        $events = $order->events;
        $event_types = EventType::all();

                
        return view('contract.orders.events', compact('order', 'events', 'contract', 
        'components', 'event_types'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $order_id)
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $order_id)
    {
        // $order = Order::findOrFail($order_id);
        $order = Order::with('events')->findOrFail($order_id);
        $events = $order->events;        

        $contract = $order->contract; // Accedemos a la relación contract        
        $post_max_size = $this->postMaxSize;
        $event_types = EventType::all();

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        // if(!$request->user()->hasPermission(['admin.events_orders.create', 'contracts.events_orders.create']) &&
        //     $item->contract->dependency_id != $request->user()->dependency_id){
        //     // return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        // }

        // return view('contract.events_orders.create', compact('item'));
        return view('contract.orders.create_events', compact('order', 'post_max_size', 
        'contract', 'event_types', 'events'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $order_id)
    {
        $rules = array(
            'event_type_id' => 'numeric|required|max:2147483647',            
            'event_date' => 'date_format:d/m/Y',
            'event_days' => 'string|max:9223372036854775807',            
            'comments' => 'nullable|max:300'
        );

        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // var_dump(
        //     $request->number_policy,
        //     $request->event_days,
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
        $fileName = 'evento_'.$request->input('event_date').'.'.$extension; // nombre a guardar
        // Cargamos el archivo en disco privado (storage/app/files), sin acceso público directo
        $path = $request->file('file')->storeAs('files', $fileName);

        $order = Order::findOrFail($order_id);
        $event = new Event;
        $event->order_id = $order_id;        
        $event->event_type_id = $request->input('event_type_id');
        
        // Convertir la fecha de entrada a formato Y-m-d
        $event_date = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace("/", "-", $request->input('event_date')))));

        // Obtener los días del evento
        $event_days = (int) $request->input('event_days');        

        // Verificar si la orden ya tiene al menos un evento
        if (Event::where('order_id', $order_id)->exists()) {
            // Si ya tiene eventos, calcular la fecha final sumando la fecha del último evento más los días al evento
            $last_event = Event::where('order_id', $order_id)->latest()->first();
            $event_date_fin = \Carbon\Carbon::parse($last_event->event_date_fin)->addDays($event_days);
        } else {
            // Si no tiene eventos, usar la fecha de acuse del contratista (order_sign_date) más el plazo mas los días del evento
            $event_date_fin = \Carbon\Carbon::parse($order->sign_date)->addDays($order->plazo + $event_days);
            
        }

        // Guardar en el modelo
        $event->event_date = $event_date;
        $event->event_days = $event_days;
        $event->event_date_fin = $event_date_fin; // Asegúrate de tener esta columna en la base de datos
        
        $event_days = str_replace('.', '',($request->input('event_days')));
        
        if ($event_days === '' ) {
            $validator->errors()->add('event_days', 'Ingrese días');
            return back()->withErrors($validator)->withInput();
        }

        if ($event_days < 0 ) {
            $validator->errors()->add('event_days', 'Días no puede ser negativo');
            return back()->withErrors($validator)->withInput();
        }else{
            $event->event_days = $event_days;
        }
        $event->comments = $request->input('comments');
        $event->file = $fileName;
        $event->file_type = 7;//evento
        $event->state_id = 1;// activo
        $event->creator_user_id = $request->user()->id;  // usuario logueado
        $event->save();

        // $order = Order::findOrFail($order_id);
        // $order->state_id = 2; // Cambiamos el estado del pedido a "En Proceso"
        // $order->save();


        return redirect()->route('orders.order.events', $order_id)->with('success', 'Evento agregado correctamente'); // Caso usuario posee rol pedidos
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $event_id)
    {
        $event = Event::findOrFail($event_id);
        $order = $event->order; // Accedemos a la relación order
        $contract = $order->contract; // Accedemos a la relación contract
        $event_types = EventType::all();

        return view('contract.orders.update_events', compact('event', 'order', 'contract', 'event_types'));
    }

    /**
 * Update the specified resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $order_id
 * @param  int  $event_id
 * @return \Illuminate\Http\Response
 */
public function update(Request $request, $order_id, $event_id)
{
    // Encontrar los modelos correspondientes o lanzar una excepción
    $item = Item::findOrFail($order_id);
    $event = ItemAwardHistory::findOrFail($event_id);

    // Reglas de validación
    $rules = [
        'item_award_type_id' => 'numeric|required|max:2147483647',
        'number_policy' => [
            'string',
            'required',
            Rule::unique('events_orders')->ignore($event->id),
            Rule::unique('items'),
        ],
        'item_from' => 'date_format:d/m/Y',
        'item_to' => 'required|date_format:d/m/Y',
        'event_days' => 'nullable|string|max:9223372036854775807',
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
        $event->file = $fileName;
    }

    // Convertir las fechas al formato adecuado
    $event->item_award_type_id = $validatedData['item_award_type_id'];
    $event->number_policy = $validatedData['number_policy'];
    $event->item_from = date('Y-m-d', strtotime(str_replace("/", "-", $validatedData['item_from'])));
    $event->item_to = date('Y-m-d', strtotime(str_replace("/", "-", $validatedData['item_to'])));

    // Manejar el campo event_days
    $event_days = str_replace('.', '', $validatedData['event_days']);
    if ($event_days === '') {
        return back()->withErrors(['event_days' => 'Ingrese Monto'])->withInput();
    }
    $event->event_days = $event_days;

    // Actualizar otros campos
    $event->comments = $validatedData['comments'];
    $event->file_type = 2; // endoso
    $event->state_id = 1;
    $event->creator_user_id = $request->user()->id; // usuario logueado

    // Guardar los cambios
    $event->save();

    // Redirigir con mensaje de éxito
    return redirect()->route('items.events_orders.index', $order_id)
                     ->with('success', 'Se ha modificado exitosamente el endoso de la póliza');
}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $order_id
     * @param  int  $event_id
     * @return \Illuminate\Http\Respons
     */
    public function update_orig(Request $request, $order_id, $event_id)
    {
        $item = Item::findOrFail($order_id);
        $event = ItemAwardHistory::findOrFail($event_id);

        $rules = array(
            'item_award_type_id' => 'numeric|required|max:2147483647',
            'number_policy' => [
                'string',
                'required',
                Rule::unique('events_orders')->ignore($event->id),
                Rule::unique('items'),                
            ],
            'item_from' => 'date_format:d/m/Y',
            'item_to' => 'required|date_format:d/m/Y',
            'event_days' => 'nullable|string|max:9223372036854775807',
            'file' => 'nullable|file|max:2040', // Ejemplo para archivo de hasta 2 MB
            'comments' => 'nullable|max:300'
        );

        // Valida los datos de entrada
        $validatedData = $request->validate($rules);

        // Actualiza el item con los datos validados
        $event->update($validatedData);


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
            $filename = $event->file;            
        }        
        $event->item_award_type_id = $request->input('item_award_type_id');
        $event->number_policy = $request->input('number_policy');
        $event->item_from = date('Y-m-d', strtotime(str_replace("/", "-", $request->input('item_from'))));
        $event->item_to = date('Y-m-d', strtotime(str_replace("/", "-", $request->input('item_to'))));
        $event_days = str_replace('.', '',($request->input('event_days')));
        if ($event_days === '' ) {
            $validator->errors()->add('event_days', 'Ingrese Monto');
            return back()->withErrors($validator)->withInput();
        }
        $event->event_days = $event_days;
        $event->comments = $request->input('comments');        
        $event->file_type = 2;//endoso
        $event->state_id = 1;
        $event->creator_user_id = $request->user()->id;  // usuario logueado
        $event->save();
        return redirect()->route('items.events_orders.index',$order_id)->with('success', 'Se ha modificado exitosamente el endoso de la póliza'); // Caso usuario posee rol pedidos
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $order_id, $event_id)
    {
        $item = Item::findOrFail($order_id);

        $event = ItemAwardHistory::findOrFail($event_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.events_orders.delete']) &&
        ($item->contract->dependency_id != $request->user()->dependency_id && $request->user()->hasPermission(['contracts.events_orders.delete'])) ){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        // Capturamos nombre del archivo almacenado en la tabla
        $filename = $event->file;
        // var_dump($filename);exit;

        // Eliminamos el archivo del disco privado
        Storage::delete('files/'.$filename);


        // Eliminamos en caso de no existir registros referenciando al item
        $event->delete();
        session()->flash('status', 'success');
        session()->flash('message', 'Se ha eliminado el endoso ' . $event->number_policy);

        return response()->json([
            'status' => 'success',
            'message' => 'Se ha eliminado el endoso'. $event->number_policy,
            'code' => 200
        ], 200);


        // $request->session()->flash('success', 'Se ha eliminado el endoso referencial a la póliza');
        // return response()->json(['status' => 'success', 'code' => 200], 200);
    }

    /**
     * Sirve el archivo del evento inline (para previsualizar en el navegador), respetando los mismos permisos que index.
     *
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request, $event_id)
    {
        $event = Event::findOrFail($event_id);
        return Storage::response('files/'.$event->file);
    }
}
