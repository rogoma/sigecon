<?php

namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Order;
use App\Models\Contract;
use App\Models\Policy;
use App\Models\Item;
use App\Models\File;
use App\Models\Level5CatalogCode;
use App\Models\OrderPresentation;
use App\Models\OrderMeasurementUnit;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\DB;

class ItemsController extends Controller
{
    protected $postMaxSize;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $index_permissions = ['admin.items.index',
                            'contracts.items.index',
                            'process_contracts.items.index',
                            'derive_contracts.items.index',
                            'plannings.items.index'];
        $create_permissions = ['admin.items.create',
                            'contracts.items.create'];
        $update_permissions = ['admin.items.update',
                            'contracts.items.update'];

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
     * Listado de todos los ítems de un pedido.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.items.index','process_orders.items.index',
        'derive_orders.items.index','plannings.items.index']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        // Obtenemos los items del pedido
        $items = $order->items;
        return view('order.items.index', compact('order', 'items'));
    }

    /**
     * BUscar codigos de catalogo 5
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // obtenemos los parametros del request
        $search = $request->input('search');

        // en caso de no haberse enviado nada retornamos
        if(empty($search)){
            return response()->json([]);
        }

        // definimos la consulta sql y enlazamos el parametro
        $search = '%'.$search.'%';
        $sql = "SELECT * FROM level5_catalog_codes WHERE code LIKE :search OR lower(description) LIKE lower(:search) LIMIT 10";
        $bindings = array("search" => $search);
        $codigos = DB::select($sql, $bindings);

        // retornamos
        return response()->json($codigos);
    }

    /**
     * BUscar codigos de catalogo 4
     *
     * @return \Illuminate\Http\Response
     */
    public function search4(Request $request)
    {
        // obtenemos los parametros del request
        $search4 = $request->input('search4');

        // en caso de no haberse enviado nada retornamos
        if(empty($search4)){
            return response()->json([]);
        }

        // definimos la consulta sql y enlazamos el parametro
        $search4 = '%'.$search4.'%';
        $sql = "SELECT * FROM level4_catalog_codes WHERE code LIKE :search4 OR lower(description) LIKE lower(:search4) LIMIT 10";
        $bindings = array("search4" => $search4);
        $codigos = DB::select($sql, $bindings);

        // retornamos
        return response()->json($codigos);
    }

    /**
     * Formulario de agregacion de pedido.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);
        $post_max_size = $this->postMaxSize;

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if($request->user()->hasPermission(['admin.items.create', 'contracts.items.create']) || $contract->dependency_id == $request->user()->dependency_id){
            // return view('contract.contracts.show', compact('contract','user_files_pol','user_files_con','other_files_pol','other_files_con'));
        }else{
            return back()->with('error', 'No tiene los suficientes permisos para agregar pólizas.');
        }

        $policies = Policy::all();

        return view('contract.items.create', compact('contract','policies','post_max_size'));
    }

    /**
     * Formulario de agregacion de items cargando archivo excel.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadExcel(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.items.create']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        return view('order.items.uploadExcel', compact('order'));
    }


    /**
     * Funcionalidad de guardado del pedido de ítemes Contrato Abierto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $contract_id)
    {
        $rules = array(
            // 'policy_id' => 'numeric|required|max:2147483647|unique:items,policy_id',
            'policy_id' => [
            'numeric','required','max:2147483647',
            Rule::unique('items')->where(function ($query) use ($contract_id) {
                return $query->where('contract_id', $contract_id);
                })
            ],
            'number_policy' => 'string|required|unique:items,number_policy',
            'item_from' => 'date_format:d/m/Y',
            'item_to' => 'required|date_format:d/m/Y',
            'amount' => 'nullable|string|max:9223372036854775807',
            'comments' => 'nullable|max:300'
        );

        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // var_dump($request->hasFile('file'));exit;

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
        // $fileName = time().'-policy-file.'.$extension; // nombre a guardar
        $fileName = 'poliza_nro_'.$request->input('number_policy').'.'.$extension; // nombre a guardar
        // Cargamos el archivo en disco privado (storage/app/files), sin acceso público directo
        $path = $request->file('file')->storeAs('files', $fileName);

        $item = new Item;
        $item->contract_id = $contract_id;
        $item->policy_id = $request->input('policy_id');
        $item->number_policy = $request->input('number_policy');
        $item->item_from = date('Y-m-d', strtotime(str_replace("/", "-", $request->input('item_from'))));
        $item->item_to = date('Y-m-d', strtotime(str_replace("/", "-", $request->input('item_to'))));

        $amount = str_replace('.', '',($request->input('amount')));
        if ($amount === '' ) {
            $validator->errors()->add('amount', 'Ingrese Monto');
            return back()->withErrors($validator)->withInput();
        }

        if ($amount < 0 ) {
            $validator->errors()->add('amount', 'Monto no puede ser negativo');
            return back()->withErrors($validator)->withInput();
        }else{
            $item->amount = $amount;
        }
        $item->comments = $request->input('comments');
        $item->file = $fileName;
        $item->file_type = 1;//póliza
        $item->creator_user_id = $request->user()->id;  // usuario logueado
        $item->save();
        return redirect()->route('contracts.show', $contract_id)->with('success', 'Póliza agregada correctamente'); // Caso usuario posee rol pedidos
    }


    /**
     * Formulario de modificacion de pedido
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $contract_id, $item_id)
    {
        $contract = Contract::findOrFail($contract_id);
        $post_max_size = $this->postMaxSize;

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.items.update','contracts.items.update']) &&  $contract->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        $item = Item::findOrFail($item_id);
        $policies = Policy::all();
        return view('contract.items.update', compact('contract','item','policies','post_max_size'));
    }


    /**
     * Funcionalidad de modificacion del pedido CUANDO ESTIPO CONTRATO 2 = CERRADO
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $contract_id, $item_id)
    {
        $contract = Contract::findOrFail($contract_id);
        $item = Item::findOrFail($item_id);

        $rules = array(
            'policy_id' => [
            'numeric','required','max:2147483647',
            Rule::unique('items')->ignore($item->id)->where(function ($query) use ($contract_id) {
                return $query->where('contract_id', $contract_id);
                })
            ],
            'number_policy' => [
                'string',
                'required',
                Rule::unique('items')->ignore($item->id),
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
        $item->update($validatedData);

        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Muestra desde la vista el nombre del archivo que está en un label
        $filename = $request->input('filename');
        // var_dump($filename);exit;

        if ($request->hasFile('file')) {
            // Obtén la extensión del archivo (omite validación)
            $extension = $request->file('file')->getClientOriginalExtension();
            if(!in_array($extension, array('doc', 'docx', 'pdf'))){
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('file', 'El archivo introducido debe corresponder a alguno de los siguientes formatos: doc, docx, pdf'); // Agregamos el error
                return back()->withErrors($validator)->withInput();
            }

            // Guarda el archivo con un nombre único
            // $fileName = time().'-policy-file.'.$extension;
            $fileName = 'poliza_nro_'.$request->input('number_policy').'.'.$extension; // nombre a guardar
            $path = $request->file('file')->storeAs('files', $fileName);

            // Capturamos nombre del archivo almacenado en la tabla
            $filename = $item->file;            

            // Eliminamos el archivo del public/files
            // Storage::delete('public/files/'.$filename);
        }

        $item->policy_id = $request->input('policy_id');
        $item->number_policy = $request->input('number_policy');
        $item->item_from = date('Y-m-d', strtotime(str_replace("/", "-", $request->input('item_from'))));
        $item->item_to = date('Y-m-d', strtotime(str_replace("/", "-", $request->input('item_to'))));

        $amount = str_replace('.', '',($request->input('amount')));
        if ($amount === '') {
            $validator->errors()->add('amount', 'Ingrese Monto');
            return back()->withErrors($validator)->withInput();
        }

        if ($amount < 0 ) {
            $validator->errors()->add('amount', 'Monto no puede ser negativo');
            return back()->withErrors($validator)->withInput();
        }else{
            $item->amount = $amount;
        }
        $item->comments = $request->input('comments');
        $item->file_type = 1;//pólizas
        $item->creator_user_id = $request->user()->id;  // usuario logueado
        $item->save();
        return redirect()->route('contracts.show', $contract_id)->with('success', 'Póliza modificada correctamente'); // Caso usuario posee rol pedidos
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $contract_id, $item_id)
    {
        $contract = Contract::findOrFail($contract_id);
        $item = Item::find($item_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.items.delete','contracts.items.delete']) && $item->contract->dependency_id != $request->user()->dependency_id){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para eliminar la póliza.', 'code' => 200], 200);
        }

        // Chequeamos si existen item_award_histories referenciando al item
        if($item->itemAwardHistories->count() > 0){
            return response()->json(['status' => 'error', 'message' => 'Póliza no puede eliminarse, posee detalle en endosos, verificar ', 'code' => 200], 200);
        }

        // Capturamos nombre del archivo almacenado en la tabla
        $filename = $item->file;
        // var_dump($filename);exit;

        // Eliminamos el archivo del disco privado
        Storage::delete('files/'.$filename);

        // Eliminamos en caso de no existir registros referenciando al item
        $item->delete();
        session()->flash('status', 'success');
        session()->flash('message', 'Se ha eliminado la póliza ' . $item->number_policy);

        return response()->json([
            'status' => 'success',
            'message' => 'Se ha eliminado la póliza'. $item->number_policy,
            'code' => 200
        ], 200);

        //return redirect()->route('contracts.show', $contract_id)->with('success', 'Póliza eliminada correctamente'); // Caso usuario posee rol pedidos
        // return response()->json(['status' => 'success', 'message' => 'Póliza eliminada correctamente', 'code' => 200], 200);
        //return redirect()->route('contracts.show', $contract_id)->with('success', 'Póliza modificada correctamente'); // Caso usuario posee rol pedidos
    }

    /**
     * Sirve el archivo de la póliza inline (para previsualizar en el navegador), respetando los mismos permisos que index.
     *
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        return Storage::response('files/'.$item->file);
    }

    /**
     * Formulario de agregacion de ítems Archivo Excel de CONTRATO ABIERTO.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeExcel(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        //VERIFICAMOS SI HAY ITEM EN EL PEDIDO, SI EXISTE ASUME VALOR 1, SINO EXISTE ASUME VALOR 0
        $cant_item = 0;
        if ($order->items->count() > 0){
            $cant_item = 1;
        }

        if($request->hasFile('excel')){
            // chequeamos la extension del archivo subido
            if($request->file('excel')->getClientOriginalExtension() != 'xls' && $request->file('excel')->getClientOriginalExtension() != 'xlsx'){
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('excel', 'El archivo introducido debe ser un excel de tipo: xls o xlsx'); // Agregamos el error
                return back()->withErrors($validator)->withInput();
            }

            // creamos un array de indices de las columnas
            $header = array('type','batch', 'item_number', 'level5_catalog_code',
            'technical_specifications', 'order_presentation', 'order_measurement_unit',
            'unit_price','min_quantity','max_quantity','total_amount_min','total_amount');

            // accedemos al archivo excel cargado
            $reader = IOFactory::createReader(ucfirst($request->file('excel')->getClientOriginalExtension())); // pasamos la extension xls o xlsx
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);
            $spreadsheet = $reader->load($request->excel->path());  // cargamos el archivo
            // variable que guarda la plantilla activa
            $worksheet = $spreadsheet->getActiveSheet();

            $rows = $worksheet->getHighestRow();    // cantidad de filas
            $columns = count($header);  // cantidad de columnas que debe tener el archivo
            $last_column = Coordinate::stringFromColumnIndex($columns);

            // Recorremos cada fila del archivo excel y sumamos el total de los totales de ítemes
            $order_amount_items = 0;
            for ($row = 2; $row <= $rows; ++$row) {
                $data = $spreadsheet->getActiveSheet()->rangeToArray(
                    'A'.$row.':'.$last_column.$row, //Ej: A2:L2 The worksheet range that we want to retrieve
                    NULL,        // Value that should be returned for empty cells
                    TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                    TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                    TRUE         // Should the array be indexed by cell row and cell column
                );

                // Manejando BUG de la librería phpspreadsheet para archivos con formato xlsx
                if(empty(trim(implode("", $data[$row])))){
                    continue;
                }

                // creamos un array con indices igual al array de columnas y valores igual a los obtenidos en el archivo excel
                $item = array_combine($header, $data[$row]);

                // creamos las reglas de validacion
                $rules = array(
                    // 'type' => 'numeric|required|max:1',
                    'batch' => 'numeric|nullable|max:2147483647',
                    'item_number' => 'numeric|nullable|max:2147483647',
                    'technical_specifications' => 'string|required|max:250',
                    'order_presentation' => 'string|required|max:100',
                    'order_measurement_unit' => 'string|required|max:100',
                    'unit_price' => 'numeric|required|max:2147483647',
                    'min_quantity' => 'numeric|required|max:2147483647',
                    'max_quantity' => 'numeric|required|max:2147483647',
                    'total_amount_min' => 'numeric|required|max:9223372036854775807',
                    'total_amount' => 'numeric|required|max:9223372036854775807',
                );
                // validamos los datos
                $validator = Validator::make($item, $rules); // Creamos un objeto validator
                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                //VERIFICAMOS EL TIPO DE CONTRATO EN EL EXCEL
                if ($item['type'] <> 1){
                    $validator->errors()->add('type', 'VERIFIQUE PLANILLA DE TIPO CONTRATO ABIERTO');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                // Chequea si existe el código de catálogo5
                $level5_catalog_code = Level5CatalogCode::where('code', $item['level5_catalog_code'])->get()->first();
                if (is_null($level5_catalog_code)) {
                    $validator->errors()->add('level5_catalog_code', 'No existe código de catálogo igual al ingresado. Por favor ingrese uno de los códigos de catálogo registrados en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }
                $order_presentation = OrderPresentation::where('description', $item['order_presentation'])->get()->first();
                if (is_null($order_presentation)) {
                    $validator->errors()->add('order_presentation', 'No existe Presentación igual a la ingresada. Por favor ingrese una de las registradas en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }
                $order_measurement_unit = OrderMeasurementUnit::where('description', $item['order_measurement_unit'])->get()->first();
                if (is_null($order_measurement_unit)) {
                    $validator->errors()->add('order_measurement_unit', 'No existe unidad de medidad igual a la ingresada. Por favor ingrese una de las registrados en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                $item['level5_catalog_code_id'] = $level5_catalog_code->id;
                $item['order_presentation_id'] = $order_presentation->id;
                $item['order_measurement_unit_id'] = $order_measurement_unit->id;
                // agregamos la fila al array de pedidos
                $items[] = $item;

                //ACUMULAR LOS TOTALES DE ITEMES
                $order_amount_items = $order_amount_items + $item['total_amount'];
            }

            // COMPARA EL MONTO TOTAL DEL PEDIDO VERSUS EL MONTO TOTAL DE LOS ÍTEMS
            $order = Order::findOrFail($order_id);
            // $order_amount = $order->total_amount;

            // CONTROLAMOS SI MONTO DE TOTAL ES IGUAL A TOTAL SUMATORIA DE ITEMS
            // if ($order_amount <> $order_amount_items) {
            //     $validator->errors()->add('order_measurement_unit', 'Monto de Ítems: '.$order_amount_items.', no es igual a monto del Pedido, VERIFIQUE ARCHIVO EXCEL');
            //     return back()->withErrors($validator)->withInput()->with('fila', $row);
            // }

            // En caso de haber pasado todas las validaciones guardamos los datos
            foreach ($items as $item) {
                $new_item = new Item;
                $new_item->order_id = $order_id;
                $new_item->batch = empty($item['batch'])? NULL : $item['batch'];
                $new_item->item_number = empty($item['item_number'])? NULL : $item['item_number'];
                $new_item->level5_catalog_code_id = $item['level5_catalog_code_id'];
                $new_item->technical_specifications = $item['technical_specifications'];
                $new_item->order_presentation_id = $item['order_presentation_id'];
                $new_item->order_measurement_unit_id = $item['order_measurement_unit_id'];
                $new_item->unit_price = $item['unit_price'];
                $new_item->min_quantity = $item['min_quantity'];
                $new_item->max_quantity = $item['max_quantity'];
                $new_item->total_amount_min = $item['total_amount_min'];
                $new_item->total_amount = $item['total_amount'];
                $new_item->creator_user_id = $request->user()->id;  // usuario logueado
                $new_item->save();
            }

            // GRABAMOS COMO TOTAL EN ORDERS LA SUMATORIA DE ITEMS + EL MONTO TOTAL DEL PEDIDO ANTES DE AGREGAR LOS NUEVOS REGISTROS DEL EXCEL

            //capturamos valor del pedido
            $order_amount = $order->total_amount;
            // var_dump($order['total_amount']);exit();

            //verificamos la variable capturada si hay valores en items al comenzar el método  $cant_item
            if ($cant_item == 1){
                $order->total_amount = $order_amount + $order_amount_items;
                $order->save();
            }else{
                $order->total_amount = $order_amount_items;
                //SI ITEM ES AGREGADO DESPUÉS DE PEDIDO EL MONTO DE ITEM QUEDA COMO MONTO TOTAL Y OG1
                $order->amount1 = $order_amount_items;
                $order->save();
            }

            return redirect()->route('orders.show', $order_id)->with('success', 'Archivo de ítems importado correctamente'); // Caso usuario posee rol pedidos

        }else{
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('excel', 'El campo es requerido');
            return back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Formulario de agregacion de ítems Archivo Excel de CONTRATO CERRADO.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeExcel2(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        //VERIFICAMOS SI HAY ITEM EN EL PEDIDO, SI EXISTE ASUME VALOR 1, SINO EXISTE ASUME VALOR 0
        $cant_item = 0;
        if ($order->items->count() > 0){
            $cant_item = 1;
        }

        // var_dump($order->items->count());exit();

        if($request->hasFile('excel')){
            // chequeamos la extension del archivo subido
            if($request->file('excel')->getClientOriginalExtension() != 'xls' && $request->file('excel')->getClientOriginalExtension() != 'xlsx'){
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('excel', 'El archivo introducido debe ser un excel de tipo: xls o xlsx'); // Agregamos el error
                return back()->withErrors($validator)->withInput();
            }

            // creamos un array de indices de las columnas
            $header = array('type','batch', 'item_number', 'level5_catalog_code',
            'technical_specifications', 'order_presentation','order_measurement_unit',
            'quantity', 'unit_price', 'total_amount');
            // 'unit_price','min_quantity','max_quantity','total_amount_min','total_amount');


            // accedemos al archivo excel cargado
            $reader = IOFactory::createReader(ucfirst($request->file('excel')->getClientOriginalExtension())); // pasamos la extension xls o xlsx
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);
            $spreadsheet = $reader->load($request->excel->path());  // cargamos el archivo
            // variable que guarda la plantilla activa
            $worksheet = $spreadsheet->getActiveSheet();

            $rows = $worksheet->getHighestRow();    // cantidad de filas
            $columns = count($header);  // cantidad de columnas que debe tener el archivo
            $last_column = Coordinate::stringFromColumnIndex($columns);

            // Recorremos cada fila del archivo excel y sumamos el total de los totales de ítemes
            $order_amount_items = 0;
            for ($row = 2; $row <= $rows; ++$row) {
                $data = $spreadsheet->getActiveSheet()->rangeToArray(
                    'A'.$row.':'.$last_column.$row, //Ej: A2:L2 The worksheet range that we want to retrieve
                    NULL,        // Value that should be returned for empty cells
                    TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                    TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                    TRUE         // Should the array be indexed by cell row and cell column
                );

                // Manejando BUG de la librería phpspreadsheet para archivos con formato xlsx
                if(empty(trim(implode("", $data[$row])))){
                    continue;
                }

                // creamos un array con indices igual al array de columnas y valores igual a los obtenidos en el archivo excel
                $item = array_combine($header, $data[$row]);

                // creamos las reglas de validacion
                $rules = array(
                    // 'type' => 'numeric|required|max:2',
                    'batch' => 'numeric|nullable|max:2147483647',
                    'item_number' => 'numeric|nullable|max:2147483647',
                    'level5_catalog_code' => 'string|required|max:200',
                    'technical_specifications' => 'string|required|max:250',
                    'order_presentation' => 'string|required|max:100',
                    'order_measurement_unit' => 'string|required|max:100',
                    'quantity' => 'numeric|required|max:2147483647',
                    'unit_price' => 'numeric|required|max:2147483647',
                    'total_amount' => 'numeric|required|max:9223372036854775807',
                );
                // validamos los datos
                $validator = Validator::make($item, $rules); // Creamos un objeto validator
                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                //VERIFICAMOS EL TIPO DE CONTRATO EN EL EXCEL
                if ($item['type'] <> 2){
                    $validator->errors()->add('type', 'VERIFIQUE PLANILLA DE TIPO CONTRATO CERRADO');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                $level5_catalog_code = Level5CatalogCode::where('code', $item['level5_catalog_code'])->get()->first();
                if (is_null($level5_catalog_code)) {
                    $validator->errors()->add('level5_catalog_code', 'No existe código de catálogo igual al ingresado. Por favor ingrese uno de los códigos de catálogo registrados en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }
                $order_presentation = OrderPresentation::where('description', $item['order_presentation'])->get()->first();
                if (is_null($order_presentation)) {
                    $validator->errors()->add('order_presentation', 'No existe Presentación igual a la ingresada. Por favor ingrese una de las registradas en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }
                $order_measurement_unit = OrderMeasurementUnit::where('description', $item['order_measurement_unit'])->get()->first();
                if (is_null($order_measurement_unit)) {
                    $validator->errors()->add('order_measurement_unit', 'No existe unidad de medidad igual a la ingresada. Por favor ingrese una de las registrados en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                $item['level5_catalog_code_id'] = $level5_catalog_code->id;
                $item['order_presentation_id'] = $order_presentation->id;
                $item['order_measurement_unit_id'] = $order_measurement_unit->id;
                // agregamos la fila al array de pedidos
                $items[] = $item;

                //ACUMULAR LOS TOTALES DE ITEMES
                $order_amount_items = $order_amount_items + $item['total_amount'];
            }

            // En caso de haber pasado todas las validaciones guardamos los datos
            foreach ($items as $item) {
                $new_item = new Item;
                $new_item->order_id = $order_id;
                $new_item->batch = empty($item['batch'])? NULL : $item['batch'];
                $new_item->item_number = empty($item['item_number'])? NULL : $item['item_number'];
                $new_item->level5_catalog_code_id = $item['level5_catalog_code_id'];
                $new_item->technical_specifications = $item['technical_specifications'];
                $new_item->order_presentation_id = $item['order_presentation_id'];
                $new_item->order_measurement_unit_id = $item['order_measurement_unit_id'];
                $new_item->quantity = $item['quantity'];
                $new_item->unit_price = $item['unit_price'];
                $new_item->total_amount = $item['total_amount'];
                $new_item->creator_user_id = $request->user()->id;  // usuario logueado
                $new_item->save();
            }

            // GRABAMOS COMO TOTAL EN ORDERS LA SUMATORIA DE ITEMS + EL MONTO TOTAL DEL PEDIDO ANTES DE AGREGAR LOS NUEVOS REGISTROS DEL EXCEL

            //capturamos valor del pedido
            $order_amount = $order->total_amount;
            // var_dump($order['total_amount']);exit();

            //verificamos la variable capturada si hay valores en items al comenzar el método  $cant_item
            if ($cant_item == 1){
                $order->total_amount = $order_amount + $order_amount_items;
                $order->save();
            }else{
                $order->total_amount = $order_amount_items;
                $order->save();
            }

            return redirect()->route('orders.show', $order_id)->with('success', 'Archivo de ítems importado correctamente'); // Caso usuario posee rol pedidos

        }else{
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('excel', 'El campo es requerido');
            return back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Formulario de agregacion de ítems Archivo Excel de CONTRATO CERRADO MMIN Y MMAX.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeExcel3(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        //VERIFICAMOS SI HAY ITEM EN EL PEDIDO, SI EXISTE ASUME VALOR 1, SINO EXISTE ASUME VALOR 0
        $cant_item = 0;
        if ($order->items->count() > 0){
            $cant_item = 1;
        }

        if($request->hasFile('excel')){
            // chequeamos la extension del archivo subido
            if($request->file('excel')->getClientOriginalExtension() != 'xls' && $request->file('excel')->getClientOriginalExtension() != 'xlsx'){
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('excel', 'El archivo introducido debe ser un excel de tipo: xls o xlsx'); // Agregamos el error
                return back()->withErrors($validator)->withInput();
            }

            // creamos un array de indices de las columnas
            $header = array('type','batch', 'item_number', 'level5_catalog_code',
            'technical_specifications', 'order_presentation','order_measurement_unit',
            'quantity', 'unit_price', 'total_amount_min','total_amount');
            // max_quuantity es igual a quantity


            // accedemos al archivo excel cargado
            $reader = IOFactory::createReader(ucfirst($request->file('excel')->getClientOriginalExtension())); // pasamos la extension xls o xlsx
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);
            $spreadsheet = $reader->load($request->excel->path());  // cargamos el archivo
            // variable que guarda la plantilla activa
            $worksheet = $spreadsheet->getActiveSheet();

            $rows = $worksheet->getHighestRow();    // cantidad de filas
            $columns = count($header);  // cantidad de columnas que debe tener el archivo
            $last_column = Coordinate::stringFromColumnIndex($columns);

            // Recorremos cada fila del archivo excel y sumamos el total de los totales de ítemes
            $order_amount_items = 0;
            for ($row = 2; $row <= $rows; ++$row) {
                $data = $spreadsheet->getActiveSheet()->rangeToArray(
                    'A'.$row.':'.$last_column.$row, //Ej: A2:L2 The worksheet range that we want to retrieve
                    NULL,        // Value that should be returned for empty cells
                    TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                    TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                    TRUE         // Should the array be indexed by cell row and cell column
                );

                // Manejando BUG de la librería phpspreadsheet para archivos con formato xlsx
                if(empty(trim(implode("", $data[$row])))){
                    continue;
                }

                // creamos un array con indices igual al array de columnas y valores igual a los obtenidos en el archivo excel
                $item = array_combine($header, $data[$row]);

                // creamos las reglas de validacion
                $rules = array(
                    // 'type' => 'numeric|required|max:3',
                    'batch' => 'numeric|nullable|max:2147483647',
                    'item_number' => 'numeric|nullable|max:2147483647',
                    'level5_catalog_code' => 'string|required|max:200',
                    'technical_specifications' => 'string|required|max:250',
                    'order_presentation' => 'string|required|max:100',
                    'order_measurement_unit' => 'string|required|max:100',
                    'quantity' => 'numeric|required|max:2147483647',
                    'unit_price' => 'numeric|required|max:2147483647',
                    'total_amount_min' => 'numeric|required|max:2147483647',
                    'total_amount' => 'numeric|required|max:9223372036854775807',
                );
                // validamos los datos
                $validator = Validator::make($item, $rules); // Creamos un objeto validator
                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                //VERIFICAMOS EL TIPO DE CONTRATO EN EL EXCEL
                if ($item['type'] <> 3){
                    $validator->errors()->add('type', 'VERIFIQUE PLANILLA DE TIPO CONTRATO CERRADO MMIN Y MMAX.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                $level5_catalog_code = Level5CatalogCode::where('code', $item['level5_catalog_code'])->get()->first();
                if (is_null($level5_catalog_code)) {
                    $validator->errors()->add('level5_catalog_code', 'No existe código de catálogo igual al ingresado. Por favor ingrese uno de los códigos de catálogo registrados en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }
                $order_presentation = OrderPresentation::where('description', $item['order_presentation'])->get()->first();
                if (is_null($order_presentation)) {
                    $validator->errors()->add('order_presentation', 'No existe Presentación igual a la ingresada. Por favor ingrese una de las registradas en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }
                $order_measurement_unit = OrderMeasurementUnit::where('description', $item['order_measurement_unit'])->get()->first();
                if (is_null($order_measurement_unit)) {
                    $validator->errors()->add('order_measurement_unit', 'No existe unidad de medidad igual a la ingresada. Por favor ingrese una de las registrados en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                $item['level5_catalog_code_id'] = $level5_catalog_code->id;
                $item['order_presentation_id'] = $order_presentation->id;
                $item['order_measurement_unit_id'] = $order_measurement_unit->id;
                // agregamos la fila al array de pedidos
                $items[] = $item;

                //ACUMULAR LOS TOTALES DE ITEMES
                $order_amount_items = $order_amount_items + $item['total_amount'];
            }

            // COMPARA EL MONTO TOTAL DEL PEDIDO VERSUS EL MONTO TOTAL DE LOS ÍTEMS
            $order = Order::findOrFail($order_id);
            // $order_amount = $order->total_amount;

            // CONTROLAMOS SI MONTO DE TOTAL ES IGUAL A TOTAL SUMATORIA DE ITEMS
            // if ($order_amount <> $order_amount_items) {
            //     $validator->errors()->add('order_measurement_unit', 'Monto de Ítems: '.$order_amount_items.', no es igual a monto del Pedido, VERIFIQUE ARCHIVO EXCEL');
            //     return back()->withErrors($validator)->withInput()->with('fila', $row);
            // }

            // En caso de haber pasado todas las validaciones guardamos los datos
            foreach ($items as $item) {
                $new_item = new Item;
                $new_item->order_id = $order_id;
                $new_item->batch = empty($item['batch'])? NULL : $item['batch'];
                $new_item->item_number = empty($item['item_number'])? NULL : $item['item_number'];
                $new_item->level5_catalog_code_id = $item['level5_catalog_code_id'];
                $new_item->technical_specifications = $item['technical_specifications'];
                $new_item->order_presentation_id = $item['order_presentation_id'];
                $new_item->order_measurement_unit_id = $item['order_measurement_unit_id'];
                $new_item->quantity = $item['quantity'];
                $new_item->unit_price = $item['unit_price'];
                $new_item->total_amount_min = $item['total_amount_min'];
                $new_item->total_amount = $item['total_amount'];
                $new_item->creator_user_id = $request->user()->id;  // usuario logueado
                $new_item->save();
            }

            // GRABAMOS COMO TOTAL EN ORDERS LA SUMATORIA DE ITEMS + EL MONTO TOTAL DEL PEDIDO ANTES DE AGREGAR LOS NUEVOS REGISTROS DEL EXCEL

            //capturamos valor del pedido
            $order_amount = $order->total_amount;
            // var_dump($order['total_amount']);exit();

            //verificamos la variable capturada si hay valores en items al comenzar el método  $cant_item
            if ($cant_item == 1){
                $order->total_amount = $order_amount + $order_amount_items;
                $order->save();
            }else{
                $order->total_amount = $order_amount_items;
                $order->save();
            }

            return redirect()->route('orders.show', $order_id)->with('success', 'Archivo de ítems importado correctamente'); // Caso usuario posee rol pedidos

        }else{
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('excel', 'El campo es requerido');
            return back()->withErrors($validator)->withInput();
        }
    }

}
