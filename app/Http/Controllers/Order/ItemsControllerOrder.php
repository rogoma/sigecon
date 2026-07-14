<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\Item;
use App\Models\Level5CatalogCode;
use App\Models\OrderPresentation;
use App\Models\OrderMeasurementUnit;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\DB;

class ItemsControllerOrder extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $index_permissions = ['admin.items.index',
                            'orders.items.index',
                            'process_orders.items.index',
                            'derive_orders.items.index',
                            'plannings.items.index'];
        $create_permissions = ['admin.items.create',
                            'orders.items.create'];
        $update_permissions = ['admin.items.update',
                            'orders.items.update'];

        $this->middleware('checkPermission:'.implode(',',$index_permissions))->only('index'); // Permiso para index
        $this->middleware('checkPermission:'.implode(',',$create_permissions))->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:'.implode(',',$update_permissions))->only(['edit', 'update']);   // Permiso para update
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
    public function create(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.items.create']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        $level5_catalog_codes = Level5CatalogCode::all();
        $order_presentations = OrderPresentation::all();
        $order_measurement_units = OrderMeasurementUnit::all();
        return view('order.items.create', compact('order','level5_catalog_codes', 'order_presentations', 'order_measurement_units'));
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
    public function store(Request $request, $order_id)
    {
        $rules = array(
            'batch' => 'numeric|nullable|max:2147483647',
            'item_number' => 'numeric|nullable|max:2147483647',
            'level5_catalog_code_id' => 'numeric|required|max:2147483647',
            'technical_specifications' => 'string|required|max:100',
            'order_presentation_id' => 'numeric|required|max:32767',
            'order_measurement_unit_id' => 'numeric|required|max:32767',
            'unit_price' => 'numeric|required|max:2147483647',
            'min_quantity' => 'numeric|required|max:2147483647',
            'max_quantity' => 'numeric|required|max:2147483647',
            'total_amount_min' => 'numeric|required|max:9223372036854775807',
            'total_amount' => 'numeric|required|max:9223372036854775807',
        );

        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $item = new Item;
        $item->order_id = $order_id;
        $item->batch = $request->input('batch');
        $item->item_number = $request->input('item_number');
        $item->level5_catalog_code_id = $request->input('level5_catalog_code_id');
        $item->technical_specifications = $request->input('technical_specifications');
        $item->order_presentation_id = $request->input('order_presentation_id');
        $item->order_measurement_unit_id = $request->input('order_measurement_unit_id');
        $item->unit_price = $request->input('unit_price');
        $item->min_quantity = $item['min_quantity'];
        $item->max_quantity = $item['max_quantity'];
        $item->total_amount_min = $item['total_amount_min'];
        $item->total_amount = $item['total_amount'];
        $item->creator_user_id = $request->user()->id;  // usuario logueado
        $item->save();

        return redirect()->route('orders.show', $order_id)->with('success', 'Ítem agregado correctamente'); // Caso usuario posee rol pedidos
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

    /**
     * Formulario de modificacion de pedido
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $order_id, $item_id)
    {
        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.items.update']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        $item = Item::findOrFail($item_id);
        $level5_catalog_codes = Level5CatalogCode::all();
        $order_presentations = OrderPresentation::all();
        $order_measurement_units = OrderMeasurementUnit::all();
        return view('order.items.update', compact('order', 'item','level5_catalog_codes', 'order_presentations','order_measurement_units'));
    }

    /**
     * Funcionalidad de modificacion del pedido CUANDO ESTIPO CONTRATO 1 = ABIERTO
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update1(Request $request, $order_id, $item_id)
    {
        $order = Order::findOrFail($order_id);
        $item = Item::findOrFail($item_id);

        $rules = array(
            'batch' => 'numeric|nullable|max:2147483647',
            'item_number' => 'numeric|nullable|max:2147483647',
            'level5_catalog_code_id' => 'numeric|required|max:2147483647',
            'technical_specifications' => 'string|required|max:250',
            'order_presentation_id' => 'numeric|required|max:32767',
            'order_measurement_unit_id' => 'numeric|required|max:32767',
            'unit_price' => 'numeric|required|max:2147483647',
            'min_quantity' => 'numeric|required|max:2147483647',
            'max_quantity' => 'numeric|required|max:2147483647',
            'total_amount_min' => 'numeric|required|max:9223372036854775807',
            'total_amount' => 'numeric|required|max:9223372036854775807',
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $item->batch = $request->input('batch');
        $item->item_number = $request->input('item_number');
        $item->level5_catalog_code_id = $request->input('level5_catalog_code_id');
        $item->technical_specifications = $request->input('technical_specifications');
        $item->order_presentation_id = $request->input('order_presentation_id');
        $item->order_measurement_unit_id = $request->input('order_measurement_unit_id');
        $item->unit_price = $request->input('unit_price');
        $item->min_quantity = $request->input('min_quantity');
        $item->max_quantity = $request->input('max_quantity');
        $item->total_amount_min = $request->input('total_amount_min');
        $item->total_amount = $request->input('total_amount');
        $item->modifier_user_id = $request->user()->id;  // usuario logueado
        $item->save();

        // Si usuario es de Plannings direcciona a plannings.show sino direcciona a orders
        if(($request->user()->dependency_id == 59)){
            return redirect()->route('plannings.show', $order_id)->with('success', 'Ítem modificado correctamente'); // Caso usuario posee rol pedidos
        }else{
            return redirect()->route('orders.show', $order_id)->with('success', 'Ítem modificado correctamente'); // Caso usuario posee rol pedidos
        }
    }

    /**
     * Funcionalidad de modificacion del pedido CUANDO ESTIPO CONTRATO 2 = CERRADO
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $order_id, $item_id)
    {
        $order = Order::findOrFail($order_id);
        $item = Item::findOrFail($item_id);

        //CONTROLAR SI AL CAMBIAR EL MONTO DE ITEM A MODIFICAR SOBREPASA MONTO CDP (SI YA TIENE CDP)
        // $cdp_amount = $order->cdp_amount;
        // if ($total_amountitems > $cdp_amount) {
        //     $validator = Validator::make($request->input(), []); // Creamos un objeto validator
        //     $validator->errors()->add('order_measurement_unit', 'Con este cambio Monto total de Ítems: '.$total_amountitems.', es MAYOR a: '.$cdp_amount.' monto de CDP del Pedido, VERIFIQUE...');
        //     return back()->withErrors($validator)->withInput();
        // }


        // ACTUALIZA TIPO DE CONTRATO 1 ABIERTO
        if ($order->open_contract == 1){
            $rules = array(
                'batch' => 'numeric|nullable|max:2147483647',
                'item_number' => 'numeric|nullable|max:2147483647',
                'level5_catalog_code_id' => 'numeric|required|max:2147483647',
                'technical_specifications' => 'string|required|max:250',
                'order_presentation_id' => 'numeric|required|max:32767',
                'order_measurement_unit_id' => 'numeric|required|max:32767',
                'min_quantity' => 'numeric|required|max:2147483647',
                'max_quantity' => 'numeric|required|max:2147483647',
                'total_amount_min' => 'numeric|required|max:9223372036854775807',
                'unit_price' => 'numeric|required|max:2147483647',
                'total_amount' => 'numeric|required|max:9223372036854775807'
            );
            $validator =  Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $item->batch = $request->input('batch');
            $item->item_number = $request->input('item_number');
            $item->level5_catalog_code_id = $request->input('level5_catalog_code_id');
            $item->technical_specifications = $request->input('technical_specifications');
            $item->order_presentation_id = $request->input('order_presentation_id');
            $item->order_measurement_unit_id = $request->input('order_measurement_unit_id');

            $item->min_quantity = $request->input('min_quantity');
            $item->max_quantity = $request->input('max_quantity');
            $item->total_amount_min = $request->input('total_amount_min');
            $item->unit_price = $request->input('unit_price');
            $item->total_amount = $request->input('total_amount');

            $item->modifier_user_id = $request->user()->id;  // usuario logueado
            $item->save();
        }

        // ACTUALIZA TIPO DE CONTRATO 2 CERRADO
        if ($order->open_contract == 2){
            $rules = array(
                'batch' => 'numeric|nullable|max:2147483647',
                'item_number' => 'numeric|nullable|max:2147483647',
                'level5_catalog_code_id' => 'numeric|required|max:2147483647',
                'technical_specifications' => 'string|required|max:250',
                'order_presentation_id' => 'numeric|required|max:32767',
                'order_measurement_unit_id' => 'numeric|required|max:32767',
                // 'min_quantity' => 'numeric|required|max:2147483647',
                // 'max_quantity' => 'numeric|required|max:2147483647',
                // 'total_amount_min' => 'numeric|required|max:9223372036854775807',
                'quantity' => 'numeric|required|max:2147483647',
                'unit_price' => 'numeric|required|max:2147483647',
                'total_amount' => 'numeric|required|max:9223372036854775807'
            );
            $validator =  Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $item->batch = $request->input('batch');
            $item->item_number = $request->input('item_number');
            $item->level5_catalog_code_id = $request->input('level5_catalog_code_id');
            $item->technical_specifications = $request->input('technical_specifications');
            $item->order_presentation_id = $request->input('order_presentation_id');
            $item->order_measurement_unit_id = $request->input('order_measurement_unit_id');
            // $item->min_quantity = $request->input('min_quantity');
            // $item->max_quantity = $request->input('max_quantity');
            // $item->total_amount_min = $request->input('total_amount_min');
            $item->quantity = $request->input('quantity');
            $item->unit_price = $request->input('unit_price');
            $item->total_amount = $request->input('total_amount');
            $item->modifier_user_id = $request->user()->id;  // usuario logueado
            $item->save();
        }

        // ACTUALIZA TIPO DE CONTRATO 3 ABIERTOMM
        if ($order->open_contract == 3){
            $rules = array(
                'batch' => 'numeric|nullable|max:2147483647',
                'item_number' => 'numeric|nullable|max:2147483647',
                'level5_catalog_code_id' => 'numeric|required|max:2147483647',
                'technical_specifications' => 'string|required|max:250',
                'order_presentation_id' => 'numeric|required|max:32767',
                'order_measurement_unit_id' => 'numeric|required|max:32767',

                'quantity' => 'numeric|required|max:2147483647',
                'unit_price' => 'numeric|required|max:2147483647',
                // 'min_quantity' => 'numeric|required|max:2147483647',
                // 'max_quantity' => 'numeric|required|max:2147483647',
                'total_amount_min' => 'numeric|required|max:9223372036854775807',
                'total_amount' => 'numeric|required|max:9223372036854775807'
            );
            $validator =  Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $item->batch = $request->input('batch');
            $item->item_number = $request->input('item_number');
            $item->level5_catalog_code_id = $request->input('level5_catalog_code_id');
            $item->technical_specifications = $request->input('technical_specifications');
            $item->order_presentation_id = $request->input('order_presentation_id');
            $item->order_measurement_unit_id = $request->input('order_measurement_unit_id');

            $item->quantity = $request->input('quantity');
            $item->unit_price = $request->input('unit_price');
            // $item->min_quantity = $request->input('min_quantity');
            // $item->max_quantity = $request->input('max_quantity');
            $item->total_amount_min = $request->input('total_amount_min');
            $item->total_amount = $request->input('total_amount');
            $item->modifier_user_id = $request->user()->id;  // usuario logueado
            $item->save();
        }


        // AQUI RECORRER LOS ITEMS DEL PEDIDO Y CARGAR COMO NUEVO TOTAL_AMOUNT EN ORDERS COMO PLURIANUAL
        $total_amountitems = 0;
        for ($i = 0; $i < count($order->items); $i++){
            $total_amountitems += $order->items[$i]->total_amount;
        }

        //CERAMOS VALOR DEL MONTO DE ORDER Y CARGAMOS VALOR NUEVO
        $order->total_amount = 0;
        $order->total_amount = $total_amountitems;
        $order->save();


        //CONTROLAMOS PARA AVISAR QUE MONTO DE SUMATORIA DE ITEMS SOBREPASA MONTO CDP (SI YA TIENE CDP)
        $cdp_amount = $order->cdp_amount;
        if ($cdp_amount > 0) {
            if ($total_amountitems > $cdp_amount) {
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('order_measurement_unit', 'Con este cambio Monto total de Ítems: '.$total_amountitems.', es MAYOR a: '.$cdp_amount.' monto de CDP del Pedido, DEBE ACTUALIZAR CDP...');
                return back()->withErrors($validator)->withInput();
            }
        }


        // Si usuario es de Plannings direcciona a plannings.show sino direcciona a orders
        if(($request->user()->dependency_id == 59)){
            return redirect()->route('plannings.show', $order_id)->with('success', 'Ítem modificado en PAC correctamente'); // Caso usuario posee rol pedidos
        }else{
            return redirect()->route('orders.show', $order_id)->with('success', 'Ítem modificado en PEDIDOS correctamente'); // Caso usuario posee rol pedidos
        }
    }


    /**
     * Funcionalidad de modificacion del pedido CUANDO ESTIPO CONTRATO 3 = ABIERTO MM
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update3(Request $request, $order_id, $item_id)
    {
        $order = Order::findOrFail($order_id);
        $item = Item::findOrFail($item_id);

        $rules = array(
            'batch' => 'numeric|nullable|max:2147483647',
            'item_number' => 'numeric|nullable|max:2147483647',
            'level5_catalog_code_id' => 'numeric|required|max:2147483647',
            'technical_specifications' => 'string|required|max:250',
            'order_presentation_id' => 'numeric|required|max:32767',
            'order_measurement_unit_id' => 'numeric|required|max:32767',
            'unit_price' => 'numeric|required|max:2147483647',
            'min_quantity' => 'numeric|required|max:2147483647',
            'max_quantity' => 'numeric|required|max:2147483647',
            'total_amount_min' => 'numeric|required|max:9223372036854775807',
            'total_amount' => 'numeric|required|max:9223372036854775807',
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $item->batch = $request->input('batch');
        $item->item_number = $request->input('item_number');
        $item->level5_catalog_code_id = $request->input('level5_catalog_code_id');
        $item->technical_specifications = $request->input('technical_specifications');
        $item->order_presentation_id = $request->input('order_presentation_id');
        $item->order_measurement_unit_id = $request->input('order_measurement_unit_id');
        $item->unit_price = $request->input('unit_price');
        $item->min_quantity = $request->input('min_quantity');
        $item->max_quantity = $request->input('max_quantity');
        $item->total_amount_min = $request->input('total_amount_min');
        $item->total_amount = $request->input('total_amount');
        $item->modifier_user_id = $request->user()->id;  // usuario logueado
        $item->save();

        // Si usuario es de Plannings direcciona a plannings.show sino direcciona a orders
        if(($request->user()->dependency_id == 59)){
            return redirect()->route('plannings.show', $order_id)->with('success', 'Ítem modificado correctamente'); // Caso usuario posee rol pedidos
        }else{
            return redirect()->route('orders.show', $order_id)->with('success', 'Ítem modificado correctamente'); // Caso usuario posee rol pedidos
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $order_id, $item_id)
    {
        $order = Order::findOrFail($order_id);
        $item = Item::find($item_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.items.delete']) &&
        $item->order->dependency_id != $request->user()->dependency_id){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        // Chequeamos si existen item_award_histories referenciando al item
        if($item->itemAwardHistories->count() > 0){
            return response()->json(['status' => 'error', 'message' => 'No se ha podido eliminar el item debido a que se encuentra vinculado con históricos de precios referenciales, debe eliminarlos primero para continuar. ', 'code' => 200], 200);
        }

        // Eliminamos en caso de no existir registros referenciando al item
        $item->delete();

        // AQUI RECORRER LOS ITEMS DEL PEDIDO Y CARGAR COMO NUEVO TOTAL_AMOUNT
        $total_amountitems = 0;
        for ($i = 0; $i < count($order->items); $i++){
            $total_amountitems += $order->items[$i]->total_amount;
        }

        // AQUI RECORRER LOS ITEMS DEL PEDIDO Y CARGAR COMO NUEVO TOTAL_AMOUNT EN ORDERS COMO PLURIANUAL

        //CONTROLAMOS SI MONTO DE SUMATORIA DE ITEMS NO SOBREPASA MONTO CDP (SI YA TIENE CDP)
        // $validator = Validator::make($request->input(), []);
        // $cdp_amount = $order->cdp_amount;
        // if ($total_amountitems > $cdp_amount) {
        //     $validator->errors()->add('order_measurement_unit', 'Monto de Ítems: '.$total_amountitems.', es MAYOR a monto de CDP del Pedido, VERIFIQUE...');
        //     return back()->withErrors($validator)->withInput();
        // }



        //CONTROLAMOS PARA AVISAR QUE MONTO DE SUMATORIA DE ITEMS SOBREPASA MONTO CDP (SI YA TIENE CDP)
        $cdp_amount = $order->cdp_amount;
        if ($cdp_amount > 0) {
            if ($total_amountitems > $cdp_amount) {
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('order_measurement_unit', 'Con este cambio Monto total de Ítems: '.$total_amountitems.', es MAYOR a: '.$cdp_amount.' monto de CDP del Pedido, DEBE ACTUALIZAR CDP...');
                return back()->withErrors($validator)->withInput();
            }
        }


        //CERAMOS VALOR DEL MONTO DE ORDER Y CARGAMOS VALOR NUEVO
        $order->total_amount = 0;
        $order->total_amount = $total_amountitems;

        $order->save();

        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el ítem ', 'code' => 200], 200);
    }
}
