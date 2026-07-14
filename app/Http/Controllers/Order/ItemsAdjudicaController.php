<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ItemAwards;
use App\Models\Level5CatalogCode;
use App\Models\OrderPresentation;
use App\Models\OrderMeasurementUnit;
use App\Models\Provider;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\DB;
use App\Exports\ItemAwardsExport2;
use App\Exports\ItemAwardsExport3;
use Maatwebsite\Excel\Facades\Excel;

class ItemsAdjudicaController extends Controller
{
    /**
     * Create a new controller instance. 
     *
     * @return void
     */
    public function __construct()
    {
        $index_permissions = ['admin.items_adjudica.index',
                            'orders.items_adjudica.index',
                            'process_orders.items_adjudica.index',
                            'derive_orders.items_adjudica.index',
                            'plannings.items_adjudica.index'];
        $create_permissions = ['admin.items_adjudica.create',
                            'orders.items_adjudica.create'];
        $update_permissions = ['admin.items_adjudica.update',
                            'orders.items_adjudica.update'];

        $this->middleware('checkPermission:'.implode(',',$index_permissions))->only('index'); // Permiso para index 
        $this->middleware('checkPermission:'.implode(',',$create_permissions))->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:'.implode(',',$update_permissions))->only(['edit', 'update']);   // Permiso para update
    }

    
    //Para exportar a Excel
    public function exportExcel(Request $request, int $order_id)
    {     
        return Excel::download(new ItemAwardsExport2($request->order_id), 'Planilla_items_empresas.xlsx');
        
    }

    //Para exportar a Excel
    public function exportExcel2()
    {     
        return Excel::download(new ItemAwardsExport3, 'items.xlsx');
        
    }

    //Para exportar a Excel en Contracts
    public function exportExcel3()
    {     
        return Excel::download(new ItemAwardsExport3, 'items.xlsx');
        
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
        if(!$request->user()->hasPermission(['admin.items_adjudica.index','process_orders.items_adjudica.index',
        'derive_orders.items_adjudica.index','plannings.items_adjudica.index']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        // Obtenemos los items_adjudica del pedido
        $items2 = $order->itemsAdju;
        return view('order.items_adjudica.index', compact('order', 'items2'));
    }

    /**
     * Visualización de un pedido
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $order_id)
    {
        // $order = Order::findOrFail($order_id);
        // $user_dependency = $request->user()->dependency_id;        
        // // Obtenemos los simese cargados por otras dependencias
        // $related_simese = $order->simese()->where('dependency_id', '!=', $user_dependency)->get();
        // // Obtenemos los simese cargados por la dependencia del usuario
        // $related_simese_user = $order->simese()->where('dependency_id', $user_dependency)->get();   
        
        // // Obtenemos los archivos cargados por otras dependencias y que no sean de reparo
        // $other_files = $order->files()->where('dependency_id', '!=', $user_dependency)
        //                                     ->whereIn('file_type', [3, 4, 5, 7])//0-antecedentes 3-contratos 4-addendas  5-dictamenes
        //                                     ->orderBy('created_at','asc')
        //                                     ->get();
        
        // // Obtenemos los archivos cargados por usuario con tipo de archivos que no sean 1 (reparos dncp)
        // $user_files = $order->files()->where('dependency_id', $user_dependency)->where('file_type', '=', 0)->get();
                        
        // // chequeamos que el usuario tenga permisos para visualizar el pedido
        // if($request->user()->hasPermission(['admin.orders.show', 'process_orders.orders.show', 'derive_orders.orders.index']) || 
        //     $order->dependency_id == $request->user()->dependency_id){
        //     return view('order.orders.show', compact('order', 'related_simese', 'related_simese_user', 'other_files', 'user_files'));
        // }else{
        //     return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        // }
    }

    /**
     * BUscar codigos de catalogo
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
     * Formulario de agregacion de pedido.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.items_adjudica.create']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        $level5_catalog_codes = Level5CatalogCode::all();
        $order_presentations = OrderPresentation::all();
        $order_measurement_units = OrderMeasurementUnit::all();
        $providers = Provider::all();
        return view('order.items_adjudica.create', compact('order','level5_catalog_codes', 'order_presentations','order_measurement_units', 'providers'));
    }

    /**
     * Funcionalidad de guardado del pedido de ítemes Contrato Abierto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        // $item = ItemAwards::all();
        
        $rules = array(
            'batch' => 'numeric|nullable|max:2147483647',
            'item_number' => 'numeric|required|max:2147483647',
            'level5_catalog_code_id' => 'numeric|required|max:2147483647',            
            'order_presentation_id' => 'numeric|required|max:32767',
            'order_measurement_unit_id' => 'numeric|required|max:32767',
            'trademark' => 'string|required|max:250',            
            'origin' => 'string|required|max:250',
            'maker' => 'string|required|max:250',
            'quantity' => 'string|required|max:2147483647',
            'unit_price' => 'string|required|max:2147483647', 
            'total_amount' => 'string|required|max:9223372036854775807',
            'provider_id' => 'numeric|required|max:32767'
        );

        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        //VERIFICAMOS QUE MONTO NO SEA SUPERIOR AL SALDO O MONTO TOTAL DE ADJUDICACIÓN        
        $monto_total_adjudica = $order->total_amount_award;
        $monto_item = str_replace('.', '',$request->input('total_amount'));
        // var_dump($monto_total_adjudica);
        // var_dump($monto_adjudica);exit();                
        if ($monto_item > $monto_total_adjudica){
            $validator->errors()->add('', 'MONTO INGRESADO SUPERA MONTO TOTAL DE ADJUDICACIÓN');
            return back()->withErrors($validator)->withInput(); 
        }        

        //SELECCIONAMOS LOS VALORES DE LOS ITEMS ADJUDICADOS QUE TENGAN MONTO MAYOR A 0                                        
        $items_budget = ItemAwards::where ('order_id','=',$order_id)
                                        ->where('total_amount', '>', 0)                                        
                                        ->get();                
        
       //VERIFICAMOS QUE MONTO INGRESADO + SUMATORIA DEL MONTO DE OTRAS EMPRESAS NO SEA SUPERIOR AL MONTO TOTAL DE ADJUDICACIÓN                
         $suma_amount_items = 0;
        
        //SUMAMOS LOS VALORES DE LOS ITEMS + EL NUEVO ITEM AGREGADO
         for ($i = 0; $i < count($items_budget); $i++){        
            $suma_amount_items += $items_budget[$i]->total_amount;
            // var_dump($suma_amount_adjudi);                            
         }
        //  var_dump($suma_amount_adjudi);exit();          
        $suma_total = $monto_item + $suma_amount_items;

        if ($suma_total > $monto_total_adjudica){
            $validator->errors()->add('', 'SUMATORIA DE MONTO DE ITEM INGRESADO SUPERA MONTO TOTAL DE ADJUDICACIÓN');
            return back()->withErrors($validator)->withInput(); 
        }
        

        $quantity = $request->input('quantity');
        $unit_price = $request->input('unit_price');
        $total_amount = $request->input('total_amount');

        $item = new ItemAwards; 
        $item->order_id = $order_id;
        $item->batch = $request->input('batch');
        $item->item_number = $request->input('item_number');
        $item->level5_catalog_code_id = $request->input('level5_catalog_code_id');        
        $item->order_presentation_id = $request->input('order_presentation_id');
        $item->order_measurement_unit_id = $request->input('order_measurement_unit_id');        
        $item->trademark = $request->input('trademark');
        $item->origin = $request->input('origin');
        $item->maker = $request->input('maker');        
        $item->quantity = str_replace('.', '',$quantity);
        $item->unit_price = str_replace('.', '',$unit_price); 
        $item->total_amount = str_replace('.', '',$total_amount);
        $item->provider_id = $request->input('provider_id');

        $item->creator_user_id = $request->user()->id;  // usuario logueado
        $item->save();

        return redirect()->route('awards.show', $order_id)->with('success', 'Ítem agregado correctamente'); // Caso usuario posee rol pedidos
    }

    /**
     * Formulario de agregacion de items_adjudica cargando archivo excel.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadExcelAw(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.items_adjudica.create']) &&
        $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        return view('order.items_adjudica.uploadExcel', compact('order'));
    }

    /**
     * Formulario de agregacion de ítems_adjudica STANDAR VÁLIDO PARA TODOS LOS TIPOS DE CONTRATOS (ABIERTO/CERRADO/ABIERTO MM-MX)
     *
     * @return \Illuminate\Http\Response
     */
    public function storeExcelAw(Request $request, $order_id)
    {
        if($request->hasFile('excel')){
            // chequeamos la extension del archivo subido
            if($request->file('excel')->getClientOriginalExtension() != 'xls' && $request->file('excel')->getClientOriginalExtension() != 'xlsx'){
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('excel', 'El archivo introducido debe ser un excel de tipo: xls o xlsx'); // Agregamos el error
                return back()->withErrors($validator)->withInput();
            }

            // creamos un array de indices de las columnas
            $header = array('batch', 'item_number', 'level5_catalog_code', 
            'order_presentation','order_measurement_unit',             
            'unit_price','quantity', 'total_amount','trademark','origin', 'maker','provider');
            // 'quantity', 'unit_price', 'total_amount','provider_code','provider');
            
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
                    'batch' => 'numeric|nullable|max:2147483647',
                    'item_number' => 'numeric|nullable|max:2147483647',
                    'level5_catalog_code' => 'string|required|max:200',                    
                    'order_presentation' => 'string|required|max:100',
                    'order_measurement_unit' => 'string|required|max:100',
                    'quantity' => 'numeric|required|max:2147483647',
                    'unit_price' => 'numeric|required|max:2147483647',
                    'total_amount' => 'numeric|required|max:9223372036854775807',                     
                    'trademark'=>'string|required|max:250',
                    'origin' =>'string|required|max:250',
                    'maker'=>'string|required|max:250',
                    //COLUMNA DE EMPRESA
                    // 'provider_code' => 'numeric|required|max:2147483647',

                    //COLUMNA DE EMPRESA
                    'provider' => 'string|required|max:200'                    

                );
                // validamos los datos
                $validator = Validator::make($item, $rules); // Creamos un objeto validator
                if ($validator->fails()) {
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

                $provider = Provider::where('description', $item['provider'])->get()->first();
                if (is_null($provider)) {
                    $validator->errors()->add('provider', 'No existe la empresa ingresada. Por favor ingrese una de las registradas en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                $item['level5_catalog_code_id'] = $level5_catalog_code->id;
                $item['order_presentation_id'] = $order_presentation->id;
                $item['order_measurement_unit_id'] = $order_measurement_unit->id;

                //OBTENEMOS DATOS DE PROVEEDORES
                $item['provider_id'] = $provider->id;
                $item['provider'] = $provider->description;                

                // agregamos la fila al array de pedidos
                $items_adjudica[] = $item;

                //ACUMULAR LOS TOTALES DE ITEMES                
                $order_amount_items = $order_amount_items + $item['total_amount'];
            }

            // COMPARA EL MONTO TOTAL DEL PEDIDO VERSUS EL MONTO TOTAL DE LOS ÍTEMS
            $order = Order::findOrFail($order_id);
            $order_amount = $order->total_amount;

            // CONTROLAMOS SI MONTO DE TOTAL ADJUDICADO ES IGUAL A TOTAL DE SUMATORIA DE ITEMS ADJUDICADO
            if ($order_amount <> $order_amount_items) {
                $validator->errors()->add('order_measurement_unit', 'Monto total de Ítems del archivo Excel: '.$order_amount_items.', no es igual a monto total de items adjudicado'.$order_amount.' , VERIFIQUE ARCHIVO EXCEL');
                return back()->withErrors($validator)->withInput()->with('fila', $row);                
            }  
            
            // En caso de haber pasado todas las validaciones guardamos los datos
            foreach ($items_adjudica as $item) {
                $new_item = new ItemAwards; 
                $new_item->order_id = $order_id;
                $new_item->batch = empty($item['batch'])? NULL : $item['batch'];
                $new_item->item_number = empty($item['item_number'])? NULL : $item['item_number'];
                $new_item->level5_catalog_code_id = $item['level5_catalog_code_id'];
                $new_item->order_presentation_id = $item['order_presentation_id'];
                $new_item->order_measurement_unit_id = $item['order_measurement_unit_id'];                
                $new_item->quantity = $item['quantity'];
                $new_item->unit_price = $item['unit_price'];
                $new_item->total_amount = $item['total_amount'];                                
                $new_item->trademark = $item['trademark'];
                $new_item->origin = $item['origin'];
                $new_item->maker = $item['maker'];                
                $new_item->provider_id = $item['provider_id'];
                // $new_item->provider_description = $item['provider'];
                $new_item->creator_user_id = $request->user()->id;  // usuario logueado
                $new_item->save();
            }

            // GRABAMOS COMO "TOTAL ADJUDICADO" EN ORDERS LA SUMATORIA DE ITEMS                        
            $order->total_amount_award = $order->total_amount_award + $order_amount_items;
            $order->save();

            return redirect()->route('awards.show', $order_id)->with('success', 'Archivo de ítems importado correctamente'); // Caso usuario posee rol pedidos

        }else{
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('excel', 'El campo es requerido');
            return back()->withErrors($validator)->withInput();
        }
    }

    
    /**
     * Formulario de agregacion de ítems Archivo Excel de Contrato Abierto.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeExcel1(Request $request, $order_id)
    {
        if($request->hasFile('excel')){
            // chequeamos la extension del archivo subido
            if($request->file('excel')->getClientOriginalExtension() != 'xls' && $request->file('excel')->getClientOriginalExtension() != 'xlsx'){
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('excel', 'El archivo introducido debe ser un excel de tipo: xls o xlsx'); // Agregamos el error
                return back()->withErrors($validator)->withInput();
            }

            // creamos un array de indices de las columnas
            $header = array('batch', 'item_number', 'level5_catalog_code', 
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

            // Recorremos cada fila del archivo excel
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
                    'batch' => 'numeric|nullable|max:2147483647',
                    'item_number' => 'numeric|nullable|max:2147483647',
                    // Se comenta código de catálogo momentáneamente
                    // 'level5_catalog_code' => 'string|required|max:200',
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
                $items_adjudica[] = $item;
            }
            
            // En caso de haber pasado todas las validaciones guardamos los datos
            foreach ($items_adjudica as $item) {
                $new_item = new ItemAwards; 
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
            return redirect()->route('awards.show', $order_id)->with('success', 'Archivo de ítems importado correctamente'); // Caso usuario posee rol pedidos

        }else{
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('excel', 'El campo es requerido');
            return back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Formulario de agregacion de ítems Archivo Excel de Contrato Cerrado.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeExcel2(Request $request, $order_id)
    {
        if($request->hasFile('excel')){
            // chequeamos la extension del archivo subido
            if($request->file('excel')->getClientOriginalExtension() != 'xls' && $request->file('excel')->getClientOriginalExtension() != 'xlsx'){
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('excel', 'El archivo introducido debe ser un excel de tipo: xls o xlsx'); // Agregamos el error
                return back()->withErrors($validator)->withInput();
            }

            // creamos un array de indices de las columnas
            $header = array('batch', 'item_number', 'level5_catalog_code', 
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

            // Recorremos cada fila del archivo excel
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
                $items_adjudica[] = $item;
            }
            
            // En caso de haber pasado todas las validaciones guardamos los datos
            foreach ($items_adjudica as $item) {
                $new_item = new ItemAwards; 
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

            return redirect()->route('orders.show', $order_id)->with('success', 'Archivo de ítems importado correctamente'); // Caso usuario posee rol pedidos

        }else{
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('excel', 'El campo es requerido');
            return back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Formulario de agregacion de ítems Archivo Excel de Contrato Abierto MMin y Mmax.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeExcel3(Request $request, $order_id)
    {
        if($request->hasFile('excel')){
            // chequeamos la extension del archivo subido
            if($request->file('excel')->getClientOriginalExtension() != 'xls' && $request->file('excel')->getClientOriginalExtension() != 'xlsx'){
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('excel', 'El archivo introducido debe ser un excel de tipo: xls o xlsx'); // Agregamos el error
                return back()->withErrors($validator)->withInput();
            }

            // creamos un array de indices de las columnas
            $header = array('batch', 'item_number', 'level5_catalog_code', 
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

            // Recorremos cada fila del archivo excel
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
                $items_adjudica[] = $item;
            }
            
            // En caso de haber pasado todas las validaciones guardamos los datos
            foreach ($items_adjudica as $item) {
                $new_item = new ItemAwards; 
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
        if(!$request->user()->hasPermission(['admin.items_adjudica.update']) && $order->dependency_id != $request->user()->dependency_id){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        $item = ItemAwards::findOrFail($item_id);
        $level5_catalog_codes = Level5CatalogCode::all();
        $order_presentations = OrderPresentation::all();
        $order_measurement_units = OrderMeasurementUnit::all();        
        $providers = Provider::all();

        return view('order.items_adjudica.update', compact('order', 'item','level5_catalog_codes', 'order_presentations','order_measurement_units','providers'));

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
        $item = ItemAwards::findOrFail($item_id);
        
        // ACTUALIZA TODO TIPO DE CONTRATO
        $rules = array(
            'batch' => 'numeric|nullable|max:2147483647',
            'item_number' => 'numeric|nullable|max:2147483647',
            'level5_catalog_code_id' => 'numeric|required|max:2147483647',            
            'order_presentation_id' => 'numeric|required|max:32767',
            'order_measurement_unit_id' => 'numeric|required|max:32767',
            'trademark' => 'string|required|max:250',
            'origin' => 'string|required|max:250',
            'maker' => 'string|required|max:250',
            'quantity' => 'string|required|max:2147483647',
            'unit_price' => 'string|required|max:2147483647', 
            'total_amount' => 'string|required|max:9223372036854775807',
            'provider_id' => 'numeric|required|max:32767',

        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        //VERIFICAMOS QUE MONTO NO SEA SUPERIOR AL SALDO O MONTO TOTAL DE ADJUDICACIÓN        
        $monto_total_adjudica = $order->total_amount_award;
        $monto_item = str_replace('.', '',$request->input('total_amount'));
        // var_dump($monto_total_adjudica);
        // var_dump($monto_adjudica);exit();                
        if ($monto_item > $monto_total_adjudica){
            $validator->errors()->add('', 'MONTO INGRESADO SUPERA MONTO TOTAL DE ADJUDICACIÓN');
            return back()->withErrors($validator)->withInput(); 
        }        

        //SELECCIONAMOS LOS VALORES DE LOS ITEMS ADJUDICADOS QUE TENGAN MONTO MAYOR A 0                                        
        $items_budget = ItemAwards::where ('order_id','=',$order_id)
                                        ->where('total_amount', '>', 0)                                        
                                        ->get();                
        
       //VERIFICAMOS QUE MONTO INGRESADO + SUMATORIA DEL MONTO DE OTRAS EMPRESAS NO SEA SUPERIOR AL MONTO TOTAL DE ADJUDICACIÓN                
         $suma_amount_items = 0;
        
        //SUMAMOS LOS VALORES DE LOS ITEMS + EL NUEVO ITEM AGREGADO
         for ($i = 0; $i < count($items_budget); $i++){        
            $suma_amount_items += $items_budget[$i]->total_amount;
            // var_dump($suma_amount_adjudi);                            
         }
        //  var_dump($suma_amount_adjudi);exit();          
        $suma_total = $monto_item + $suma_amount_items;

        if ($suma_total > $monto_total_adjudica){
            $validator->errors()->add('', 'SUMATORIA DE MONTO DE ITEM INGRESADO SUPERA MONTO TOTAL DE ADJUDICACIÓN');
            return back()->withErrors($validator)->withInput(); 
        }

        //SE CAPTURAN ESTOS CAMPOS PARA PODER SACAR EL FORMATO SEPARADOR DE MILES
        $quantity = $request->input('quantity');
        $unit_price = $request->input('unit_price');
        $total_amount = $request->input('total_amount');

        $item->batch = $request->input('batch');
        $item->item_number = $request->input('item_number');
        $item->level5_catalog_code_id = $request->input('level5_catalog_code_id');        
        $item->order_presentation_id = $request->input('order_presentation_id');
        $item->order_measurement_unit_id = $request->input('order_measurement_unit_id');
        $item->trademark = $request->input('trademark');
        $item->origin = $request->input('origin');
        $item->maker = $request->input('maker');
        $item->quantity = str_replace('.', '',$quantity);
        $item->unit_price = str_replace('.', '',$unit_price); 
        $item->total_amount = str_replace('.', '',$total_amount);
        $item->provider_id = $request->input('provider_id');
        
        $item->modifier_user_id = $request->user()->id;  // usuario logueado        
        $item->save();


        // AQUI RECORRER LOS ITEMS DEL PEDIDO Y CARGAR COMO NUEVO TOTAL_AMOUNT EN ORDERS COMO PLURIANUAL
        $total_amountitems = 0;
        for ($i = 0; $i < count($order->itemAwards); $i++){                            
            $total_amountitems += $order->itemAwards[$i]->total_amount;                           
        }

        //CERAMOS VALOR DEL MONTO DE ORDER Y CARGAMOS VALOR NUEVO
        $order->total_amount_award = 0;
        $order->total_amount_award = $total_amountitems;
        $order->save();


        // REDIRECIONA A AWARDS        
        return redirect()->route('awards.show', $order_id)->with('success', 'Ítem modificado en ADJUDICACIONES'); // Caso usuario posee rol pedidos

        // ACTUALIZA TIPO DE CONTRATO 1 ABIERTO
        // if ($order->open_contract == 1){
        //     $rules = array(
        //         'batch' => 'numeric|nullable|max:2147483647',
        //         'item_number' => 'numeric|nullable|max:2147483647',
        //         'level5_catalog_code_id' => 'numeric|required|max:2147483647',
        //         // 'technical_specifications' => 'string|required|max:250',
        //         'order_presentation_id' => 'numeric|required|max:32767',
        //         'order_measurement_unit_id' => 'numeric|required|max:32767',

        //         'trademark' => 'string|required|max:250',
        //         'origin' => 'string|required|max:250',
        //         'quantity' => 'numeric|required|max:2147483647',

        //         // 'min_quantity' => 'numeric|required|max:2147483647',
        //         // 'max_quantity' => 'numeric|required|max:2147483647',                    
        //         // 'total_amount_min' => 'numeric|required|max:9223372036854775807',                
        //         'unit_price' => 'numeric|required|max:2147483647', 
        //         'total_amount' => 'numeric|required|max:9223372036854775807'
        //     );
        //     $validator =  Validator::make($request->input(), $rules);
        //     if ($validator->fails()) {
        //         return back()->withErrors($validator)->withInput();
        //     }

        //     $item->batch = $request->input('batch');
        //     $item->item_number = $request->input('item_number');
        //     $item->level5_catalog_code_id = $request->input('level5_catalog_code_id');
        //     //$item->technical_specifications = $request->input('technical_specifications');
        //     $item->order_presentation_id = $request->input('order_presentation_id');
        //     $item->order_measurement_unit_id = $request->input('order_measurement_unit_id');    
            
        //     $item->trademark = $request->input('trademark');
        //     $item->origin = $request->input('origin');
        //     $item->quantity = $request->input('quantity');

        //     // $item->min_quantity = $request->input('min_quantity');
        //     // $item->max_quantity = $request->input('max_quantity');
        //     // $item->total_amount_min = $request->input('total_amount_min');
            
        //     $item->unit_price = $request->input('unit_price'); 
        //     $item->total_amount = $request->input('total_amount');

        //     $item->modifier_user_id = $request->user()->id;  // usuario logueado        
        //     $item->save();
        // }    

        // ACTUALIZA TIPO DE CONTRATO 2 CERRADO
        // if ($order->open_contract == 2){
        //     $rules = array(
        //         'batch' => 'numeric|nullable|max:2147483647',
        //         'item_number' => 'numeric|nullable|max:2147483647',
        //         'level5_catalog_code_id' => 'numeric|required|max:2147483647',
        //         //'technical_specifications' => 'string|required|max:250',
        //         'order_presentation_id' => 'numeric|required|max:32767',
        //         'order_measurement_unit_id' => 'numeric|required|max:32767',
        //         // 'min_quantity' => 'numeric|required|max:2147483647',
        //         // 'max_quantity' => 'numeric|required|max:2147483647',                    
        //         // 'total_amount_min' => 'numeric|required|max:9223372036854775807',
        //         'quantity' => 'numeric|required|max:2147483647',
        //         'unit_price' => 'numeric|required|max:2147483647', 
        //         'total_amount' => 'numeric|required|max:9223372036854775807'
        //     );
        //     $validator =  Validator::make($request->input(), $rules);
        //     if ($validator->fails()) {
        //         return back()->withErrors($validator)->withInput();
        //     }

        //     $item->batch = $request->input('batch');
        //     $item->item_number = $request->input('item_number');
        //     $item->level5_catalog_code_id = $request->input('level5_catalog_code_id');
        //     //$item->technical_specifications = $request->input('technical_specifications');
        //     $item->order_presentation_id = $request->input('order_presentation_id');
        //     $item->order_measurement_unit_id = $request->input('order_measurement_unit_id');
            
        //     $item->trademark = $request->input('trademark');
        //     $item->origin = $request->input('origin');

        //     // $item->min_quantity = $request->input('min_quantity');
        //     // $item->max_quantity = $request->input('max_quantity');
        //     // $item->total_amount_min = $request->input('total_amount_min');
        //     $item->quantity = $request->input('quantity');
        //     $item->unit_price = $request->input('unit_price');
        //     $item->total_amount = $request->input('total_amount');            
        //     $item->modifier_user_id = $request->user()->id;  // usuario logueado        
        //     $item->save();
        // }    

        // ACTUALIZA TIPO DE CONTRATO 3 ABIERTOMM
        // if ($order->open_contract == 3){
        //     $rules = array(
        //         'batch' => 'numeric|nullable|max:2147483647',
        //         'item_number' => 'numeric|nullable|max:2147483647',
        //         'level5_catalog_code_id' => 'numeric|required|max:2147483647',
        //         //'technical_specifications' => 'string|required|max:250',
        //         'order_presentation_id' => 'numeric|required|max:32767',
        //         'order_measurement_unit_id' => 'numeric|required|max:32767',

        //         'quantity' => 'numeric|required|max:2147483647',
        //         'unit_price' => 'numeric|required|max:2147483647',                 
        //         // 'min_quantity' => 'numeric|required|max:2147483647',
        //         // 'max_quantity' => 'numeric|required|max:2147483647',                    
        //         'total_amount_min' => 'numeric|required|max:9223372036854775807',
        //         'total_amount' => 'numeric|required|max:9223372036854775807'
        //     );
        //     $validator =  Validator::make($request->input(), $rules);
        //     if ($validator->fails()) {
        //         return back()->withErrors($validator)->withInput();
        //     }

        //     $item->batch = $request->input('batch');
        //     $item->item_number = $request->input('item_number');
        //     $item->level5_catalog_code_id = $request->input('level5_catalog_code_id');
        //     //$item->technical_specifications = $request->input('technical_specifications');
        //     $item->order_presentation_id = $request->input('order_presentation_id');
        //     $item->order_measurement_unit_id = $request->input('order_measurement_unit_id'); 

        //     $item->trademark = $request->input('trademark');
        //     $item->origin = $request->input('origin');
            
        //     $item->quantity = $request->input('quantity');                   
        //     $item->unit_price = $request->input('unit_price');             
        //     // $item->min_quantity = $request->input('min_quantity');
        //     // $item->max_quantity = $request->input('max_quantity');
        //     $item->total_amount_min = $request->input('total_amount_min');            
        //     $item->total_amount = $request->input('total_amount');

        //     $item->modifier_user_id = $request->user()->id;  // usuario logueado        
        //     $item->save();
        // } 

        // // Si usuario es de Plannings direcciona a plannings.show sino direcciona a orders
        // if(($request->user()->dependency_id == 64)){
        //     return redirect()->route('awards.show', $order_id)->with('success', 'Ítem modificado en ADJUDICACIONES'); // Caso usuario posee rol pedidos
        // }else{
        //     return redirect()->route('orders.show', $order_id)->with('success', 'Ítem modificado en PEDIDOS correctamente'); // Caso usuario posee rol pedidos
        // }
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
        $item = ItemAwards::findOrFail($item_id);

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
     * Funcionalidad de modificacion del pedido CUANDO ESTIPO CONTRATO 3 = ABIERTO MM
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update3(Request $request, $order_id, $item_id)
    {
        $order = Order::findOrFail($order_id);
        $item = ItemAwards::findOrFail($item_id);

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
        $item = ItemAwards::find($item_id);        

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.items_adjudica.delete']) &&
        $item->order->dependency_id != $request->user()->dependency_id){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        // Chequeamos si existen item_award_histories referenciando al item
        // if($item->itemAwardHistories->count() > 0){
        //     return response()->json(['status' => 'error', 'message' => 'No se ha podido eliminar el item debido a que se encuentra vinculado con históricos de precios referenciales, debe eliminarlos primero para continuar. ', 'code' => 200], 200);
        // }
        
        // Eliminamos en caso de no existir registros referenciando al item
        $item->delete();

        // AQUI RECORRER LOS ITEMS DEL PEDIDO Y CARGAR COMO NUEVO TOTAL_AMOUNT EN ORDERS COMO PLURIANUAL
        $total_amountitems = 0;
        for ($i = 0; $i < count($order->itemAwards); $i++){                            
            $total_amountitems += $order->itemAwards[$i]->total_amount;                           
        }

        //CERAMOS VALOR DEL MONTO DE ORDER Y CARGAMOS VALOR NUEVO
        $order->total_amount_award = 0;
        $order->total_amount_award = $total_amountitems;
        $order->save();

        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el ítem ', 'code' => 200], 200);        
    }
}