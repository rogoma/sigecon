<?php

// namespace App\Http\Controllers\contract;
namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use Illuminate\Validation\Rule;
use App\Models\Level5CatalogCode;
use App\Models\OrderPresentation;
use App\Models\OrderMeasurementUnit;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\DB;
use App\Models\ItemOrder;
use App\Models\Rubro;
use App\Models\Component;
use App\Models\SubItem;
use App\Models\Contract;

class ItemsOrdersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $index_permissions = ['admin.items.index', 'orders.items.index', 'admin.orders.view', 'orders.orders.view'];
        $create_permissions = ['admin.items.create', 'orders.items.create'];
        $update_permissions = ['admin.items.update', 'orders.items.update'];

        $this->middleware('checkPermission:' . implode(',', $index_permissions))->only('index'); // Permiso para index
        $this->middleware('checkPermission:' . implode(',', $create_permissions))->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:' . implode(',', $update_permissions))->only(['edit', 'update']);   // Permiso para update
    }


    public function index(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        $contracts = $order->contracts;
        $items = $order->items;

        // Chequeamos permisos del usuario par mostrar datos de rubros en modo view only
        if (!$request->user()->hasPermission(['admin.orders.view', 'orders.items.create', 'orders.orders.view'])) {
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        return view('order.items.index', compact('order', 'items', 'contracts'));
        // return view('contract.itemscontracts.index', compact('order', 'items','contracts'));

    }

    public function store(Request $request)
    {
        // Validar los datos del request
        $data = $request->validate([
            'items' => 'required|array',
            'items.*.item_number' => 'required|integer',
            'items.*.rubro_id' => 'required|integer',
            'items.*.quantity' => 'required|numeric',
            'items.*.unit_price_mo' => 'required|numeric',
            'items.*.unit_price_mat' => 'required|numeric',
            'items.*.tot_price_mo' => 'required|numeric',
            'items.*.tot_price_mat' => 'required|numeric',
            'order_id' => 'required|integer',
            'creator_user_id' => 'required|integer',
        ]);

        // Obtener el monto actual de la orden
        $currentOrderAmount = Order::where('id', $data['order_id'])->value('total_amount');

        // Obtener el contract_id de la orden
        $contract_id = Order::where('id', $data['order_id'])->value('contract_id');

        // Restar el monto actual de la orden al compro_amount del contrato
        Contract::where('id', $contract_id)->decrement('compro_amount', $currentOrderAmount);

        // Eliminar todos los registros existentes asociados a la orden
        ItemOrder::where('order_id', $data['order_id'])->delete();

        $total_amount = 0;

        // Insertar los nuevos registros
        foreach ($data['items'] as $item) {
            ItemOrder::create([
                'item_number' => $item['item_number'],
                'rubro_id' => $item['rubro_id'],
                'quantity' => $item['quantity'],
                'unit_price_mo' => $item['unit_price_mo'],
                'unit_price_mat' => $item['unit_price_mat'],
                'item_state' => 1,
                'order_id' => $data['order_id'],
                'creator_user_id' => $data['creator_user_id'],
            ]);
            $total_mo = round($item['quantity'] * $item['unit_price_mo']);
            $total_mat = round($item['quantity'] * $item['unit_price_mat']);
            $total_amount += ($total_mo + $total_mat);
        }

        // Obtener el estado actual de la orden
        $currentOrderState = Order::where('id', $data['order_id'])->value('order_state_id');
        $sign_dateOrder = Order::where('id', $data['order_id'])->value('sign_date');

        // Determinar el nuevo valor de order_state_id
        if ($currentOrderState == 11) {
            $newOrderState = 10; // Si el estado actual es 11, cambiarlo a 10otros casos
        }

        if ($currentOrderState == 10 && $sign_dateOrder !== null) {        
            $newOrderState = 1; // Si el estado actual es 10, cambiarlo a 1
        }else{
            $newOrderState = 10; // Si el estado actual es 10 y no tiene acuse
        }

        if ($currentOrderState == 22 && $sign_dateOrder !== null) {        
            $newOrderState = 1; // Si el estado actual es 22, cambiarlo a 1
        }else{
            $newOrderState = 10; // Si el estado actual es 10 y no tiene acuse
        }

        if ($currentOrderState == 1 && $sign_dateOrder !== null) {        
            $newOrderState = 1; // Si el estado actual es 10, cambiarlo a 1    
        }


        // Actualizar el estado de la orden y el monto total
        Order::where('id', $data['order_id'])->update([
            'order_state_id' => $newOrderState,
            'total_amount' => $total_amount
        ]);

        // Acumular el nuevo monto en compro_amount del contrato
        Contract::where('id', $contract_id)->increment('compro_amount', $total_amount);

        return response()->json(['redirect_url' => route('orders.show', $data['order_id'])]);
    }

    

    /**
     * Funcionalidad de guardado del pedido de ítemes Contrato Abierto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_orig(Request $request, $order_id)
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

        $item = new ItemOrder;
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

        //capturamos el id del contrato para enviar a la vista show de contrato al finalizar
        $contract_id = $order->contract_id;

        //VERIFICAMOS SI HAY ITEM EN EL PEDIDO, SI EXISTE ASUME VALOR 1, SINO EXISTE ASUME VALOR 0
        $cant_item = 0;
        if ($order->items->count() > 0) {
            $cant_item = 1;
        }

        if ($request->hasFile('excel')) {
            // chequeamos la extension del archivo subido
            if ($request->file('excel')->getClientOriginalExtension() != 'xls' && $request->file('excel')->getClientOriginalExtension() != 'xlsx') {
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('excel', 'El archivo introducido debe ser un excel de tipo: xls o xlsx'); // Agregamos el error
                return back()->withErrors($validator)->withInput();
            }

            // creamos un array de indices de las columnas
            $header = array(
                'component_id',
                'subItem_id',
                'rubro_id',
                'item_number',
                'rubro',
                'quantity',
                'unid',
                'unit_price_mo',
                'unit_price_mat',
                'tot_price_mo',
                'tot_price_mat'
            );

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

            //ceramos la variable que guarda la suma de los totales de los ítems
            $order_amount_items = 0;
            $tot_tot_price_mo = 0;
            $tot_tot_price_mat = 0;

            for ($row = 2; $row <= $rows; ++$row) {
                $data = $spreadsheet->getActiveSheet()->rangeToArray(
                    'A' . $row . ':' . $last_column . $row, //Ej: A2:L2 The worksheet range that we want to retrieve
                    NULL,        // Value that should be returned for empty cells
                    TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                    TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                    TRUE         // Should the array be indexed by cell row and cell column
                );

                // Manejando BUG de la librería phpspreadsheet para archivos con formato xlsx
                if (empty(trim(implode("", $data[$row])))) {
                    continue;
                }

                // creamos un array con indices igual al array de columnas y valores igual a los obtenidos en el archivo excel
                $item = array_combine($header, $data[$row]);

                // creamos las reglas de validacion
                $rules = array(
                    'component_id' => 'numeric|required',
                    'subItem_id' => 'numeric|required',
                    'rubro_id' => 'numeric|required',
                    'item_number' => 'numeric|required',
                    'quantity' => 'numeric|required',
                    'unid' => 'string|required',
                    'unit_price_mo' => 'numeric|required|max:2147483647',
                    'unit_price_mat' => 'numeric|required|max:2147483647',
                    'tot_price_mo' => 'numeric|required|max:2147483647',
                    'tot_price_mat' => 'numeric|required|max:2147483647'
                );
                // validamos los datos
                $validator = Validator::make($item, $rules); // Creamos un objeto validator
                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }


                // Chequea si existe el código o id del componente
                $component = Component::where('id', $item['component_id'])->get()->first();
                if (is_null($component)) {
                    $validator->errors()->add('component', 'No existe id de Componente ingresado. Por favor ingrese un componenente registrado en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                // Chequea si existe el código o id del SubItem
                $subItem = SubItem::where('id', $item['subItem_id'])->get()->first();
                if (is_null($subItem)) {
                    $validator->errors()->add('subItem', 'No existe SubItem ingresado. Por favor ingrese un subItem registrado en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                // Chequea si el código del componente del excel sea el mismo de la orden
                $compo = $order->component->id;
                $compo_2 = (int) $item['component_id'];
                // $item['component_id'];
                // var_dump($compo);
                // var_dump($item);exit();
                //dsdsd

                if ($compo !== $compo_2) {
                    $validator->errors()->add('component', 'Componente del Archivo Excel no es igual a Componente de la Orden de Ejecución, verifique....');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                // Chequea si existe el código o id del rubro
                $rubro = Rubro::where('id', $item['rubro_id'])->get()->first();
                if (is_null($rubro)) {
                    $validator->errors()->add('rubro', 'No existe id de rubro ingresado. Por favor ingrese un rubro registrado en el sistema.');
                    return back()->withErrors($validator)->withInput()->with('fila', $row);
                }

                $item['rubro_id'] = $rubro->id;
                // agregamos la fila al array de pedidos
                $items[] = $item;

                //ACUMULA LOS TOTALES DE PRECIOS DE ITEMES
                $tot_tot_price_mo = $tot_tot_price_mo + $item['tot_price_mo'];
                $tot_tot_price_mat = $tot_tot_price_mat + $item['tot_price_mat'];
            }

            // En caso de haber pasado todas las validaciones guardamos los datos
            foreach ($items as $item) {
                $new_item = new ItemOrder;
                $new_item->order_id = $order_id;
                // $new_item->batch = empty($item['batch'])? NULL : $item['batch'];
                $new_item->item_number = empty($item['item_number']) ? NULL : $item['item_number'];
                $new_item->rubro_id = $item['rubro_id'];

                if ($item['rubro_id'] == 9999) {
                    $new_item->subitem_id = $item['subItem_id'];
                } else {
                    $new_item->subitem_id = NULL;
                }

                $new_item->quantity = $item['quantity'];
                $new_item->unit_price_mo = $item['unit_price_mo'];
                $new_item->unit_price_mat = $item['unit_price_mat'];
                $new_item->tot_price_mo = $item['tot_price_mo'];
                $new_item->tot_price_mat = $item['tot_price_mat'];
                $new_item->creator_user_id = $request->user()->id;  // usuario logueado
                $new_item->save();
            }

            // GRABAMOS COMO TOTAL EN ORDERS LA SUMATORIA DE ITEMS + EL MONTO TOTAL DEL PEDIDO ANTES DE AGREGAR LOS NUEVOS REGISTROS DEL EXCEL

            // COMPARA EL MONTO TOTAL DEL PEDIDO VERSUS EL MONTO TOTAL DE LOS ÍTEMS
            $order = Order::findOrFail($order_id);
            //CALCULA EL TOTAL GRAL PARA GRABAR EN ORDERS
            $order->total_amount = $tot_tot_price_mo + $tot_tot_price_mat;
            $order->save();

            return redirect()->route('contracts.show', $contract_id)->with('success', 'Archivo de rubros importado correctamente'); // Caso usuario posee rol pedidos

        } else {
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
        if ($order->items->count() > 0) {
            $cant_item = 1;
        }

        // var_dump($order->items->count());exit();

        if ($request->hasFile('excel')) {
            // chequeamos la extension del archivo subido
            if ($request->file('excel')->getClientOriginalExtension() != 'xls' && $request->file('excel')->getClientOriginalExtension() != 'xlsx') {
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('excel', 'El archivo introducido debe ser un excel de tipo: xls o xlsx'); // Agregamos el error
                return back()->withErrors($validator)->withInput();
            }

            // creamos un array de indices de las columnas
            $header = array(
                'type',
                'batch',
                'item_number',
                'level5_catalog_code',
                'technical_specifications',
                'order_presentation',
                'order_measurement_unit',
                'quantity',
                'unit_price',
                'total_amount'
            );
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
                    'A' . $row . ':' . $last_column . $row, //Ej: A2:L2 The worksheet range that we want to retrieve
                    NULL,        // Value that should be returned for empty cells
                    TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                    TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                    TRUE         // Should the array be indexed by cell row and cell column
                );

                // Manejando BUG de la librería phpspreadsheet para archivos con formato xlsx
                if (empty(trim(implode("", $data[$row])))) {
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
                if ($item['type'] <> 2) {
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
                $new_item = new ItemOrder;
                $new_item->order_id = $order_id;
                $new_item->batch = empty($item['batch']) ? NULL : $item['batch'];
                $new_item->item_number = empty($item['item_number']) ? NULL : $item['item_number'];
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
            if ($cant_item == 1) {
                $order->total_amount = $order_amount + $order_amount_items;
                $order->save();
            } else {
                $order->total_amount = $order_amount_items;
                $order->save();
            }

            return redirect()->route('orders.show', $order_id)->with('success', 'Archivo de ítems importado correctamente'); // Caso usuario posee rol pedidos

        } else {
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
        if ($order->items->count() > 0) {
            $cant_item = 1;
        }

        if ($request->hasFile('excel')) {
            // chequeamos la extension del archivo subido
            if ($request->file('excel')->getClientOriginalExtension() != 'xls' && $request->file('excel')->getClientOriginalExtension() != 'xlsx') {
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('excel', 'El archivo introducido debe ser un excel de tipo: xls o xlsx'); // Agregamos el error
                return back()->withErrors($validator)->withInput();
            }

            // creamos un array de indices de las columnas
            $header = array(
                'type',
                'batch',
                'item_number',
                'level5_catalog_code',
                'technical_specifications',
                'order_presentation',
                'order_measurement_unit',
                'quantity',
                'unit_price',
                'total_amount_min',
                'total_amount'
            );
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
                    'A' . $row . ':' . $last_column . $row, //Ej: A2:L2 The worksheet range that we want to retrieve
                    NULL,        // Value that should be returned for empty cells
                    TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                    TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                    TRUE         // Should the array be indexed by cell row and cell column
                );

                // Manejando BUG de la librería phpspreadsheet para archivos con formato xlsx
                if (empty(trim(implode("", $data[$row])))) {
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
                if ($item['type'] <> 3) {
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
                $new_item = new ItemOrder;
                $new_item->order_id = $order_id;
                $new_item->batch = empty($item['batch']) ? NULL : $item['batch'];
                $new_item->item_number = empty($item['item_number']) ? NULL : $item['item_number'];
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
            if ($cant_item == 1) {
                $order->total_amount = $order_amount + $order_amount_items;
                $order->save();
            } else {
                $order->total_amount = $order_amount_items;
                $order->save();
            }

            return redirect()->route('orders.show', $order_id)->with('success', 'Archivo de ítems importado correctamente'); // Caso usuario posee rol pedidos

        } else {
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
        if (
            !$request->user()->hasPermission(['admin.items.update']) &&
            $order->dependency_id != $request->user()->dependency_id
        ) {
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        $item = ItemOrder::findOrFail($item_id);
        $level5_catalog_codes = Level5CatalogCode::all();
        $order_presentations = OrderPresentation::all();
        $order_measurement_units = OrderMeasurementUnit::all();
        return view('order.items.update', compact('order', 'item', 'level5_catalog_codes', 'order_presentations', 'order_measurement_units'));
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
        $item = ItemOrder::findOrFail($item_id);

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
        if (($request->user()->dependency_id == 59)) {
            return redirect()->route('plannings.show', $order_id)->with('success', 'Ítem modificado correctamente'); // Caso usuario posee rol pedidos
        } else {
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
        $item = ItemOrder::findOrFail($item_id);

        //CONTROLAR SI AL CAMBIAR EL MONTO DE ITEM A MODIFICAR SOBREPASA MONTO CDP (SI YA TIENE CDP)
        // $cdp_amount = $order->cdp_amount;
        // if ($total_amountitems > $cdp_amount) {
        //     $validator = Validator::make($request->input(), []); // Creamos un objeto validator
        //     $validator->errors()->add('order_measurement_unit', 'Con este cambio Monto total de Ítems: '.$total_amountitems.', es MAYOR a: '.$cdp_amount.' monto de CDP del Pedido, VERIFIQUE...');
        //     return back()->withErrors($validator)->withInput();
        // }


        // ACTUALIZA TIPO DE CONTRATO 1 ABIERTO
        if ($order->open_contract == 1) {
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
        if ($order->open_contract == 2) {
            $rules = array(
                'batch' => 'numeric|nullable|max:2147483647',
                'item_number' => 'numeric|nullable|max:2147483647',
                'level5_catalog_code_id' => 'numeric|required|max:2147483647',
                'technical_specifications' => 'string|required|max:250',
                'order_presentation_id' => 'numeric|required|max:32767',
                'order_measurement_unit_id' => 'numeric|required|max:32767',
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
            $item->quantity = $request->input('quantity');
            $item->unit_price = $request->input('unit_price');
            $item->total_amount = $request->input('total_amount');
            $item->modifier_user_id = $request->user()->id;  // usuario logueado
            $item->save();
        }

        // ACTUALIZA TIPO DE CONTRATO 3 ABIERTOMM
        if ($order->open_contract == 3) {
            $rules = array(
                'batch' => 'numeric|nullable|max:2147483647',
                'item_number' => 'numeric|nullable|max:2147483647',
                'level5_catalog_code_id' => 'numeric|required|max:2147483647',
                'technical_specifications' => 'string|required|max:250',
                'order_presentation_id' => 'numeric|required|max:32767',
                'order_measurement_unit_id' => 'numeric|required|max:32767',
                'quantity' => 'numeric|required|max:2147483647',
                'unit_price' => 'numeric|required|max:2147483647',
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
            $item->total_amount_min = $request->input('total_amount_min');
            $item->total_amount = $request->input('total_amount');
            $item->modifier_user_id = $request->user()->id;  // usuario logueado
            $item->save();
        }


        // AQUI RECORRER LOS ITEMS DEL PEDIDO Y CARGAR COMO NUEVO TOTAL_AMOUNT EN ORDERS COMO PLURIANUAL
        $total_amountitems = 0;
        for ($i = 0; $i < count($order->items); $i++) {
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
                $validator->errors()->add('order_measurement_unit', 'Con este cambio Monto total de Ítems: ' . $total_amountitems . ', es MAYOR a: ' . $cdp_amount . ' monto de CDP del Pedido, DEBE ACTUALIZAR CDP...');
                return back()->withErrors($validator)->withInput();
            }
        }


        // Si usuario es de Plannings direcciona a plannings.show sino direcciona a orders
        if (($request->user()->dependency_id == 59)) {
            return redirect()->route('plannings.show', $order_id)->with('success', 'Ítem modificado en PAC correctamente'); // Caso usuario posee rol pedidos
        } else {
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
        $item = ItemOrder::findOrFail($item_id);

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
        if (($request->user()->dependency_id == 59)) {
            return redirect()->route('plannings.show', $order_id)->with('success', 'Ítem modificado correctamente'); // Caso usuario posee rol pedidos
        } else {
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
        $item = ItemOrder::find($item_id);

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if (
            !$request->user()->hasPermission(['admin.items.delete']) &&
            $item->order->dependency_id != $request->user()->dependency_id
        ) {
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        // Chequeamos si existen item_award_histories referenciando al item
        if ($item->itemAwardHistories->count() > 0) {
            return response()->json(['status' => 'error', 'message' => 'No se ha podido eliminar el item debido a que se encuentra vinculado con históricos de precios referenciales, debe eliminarlos primero para continuar. ', 'code' => 200], 200);
        }

        // Eliminamos en caso de no existir registros referenciando al item
        $item->delete();

        // AQUI RECORRER LOS ITEMS DEL PEDIDO Y CARGAR COMO NUEVO TOTAL_AMOUNT
        $total_amountitems = 0;
        for ($i = 0; $i < count($order->items); $i++) {
            $total_amountitems += $order->items[$i]->total_amount;
        }

        //CONTROLAMOS PARA AVISAR QUE MONTO DE SUMATORIA DE ITEMS SOBREPASA MONTO CDP (SI YA TIENE CDP)
        $cdp_amount = $order->cdp_amount;
        if ($cdp_amount > 0) {
            if ($total_amountitems > $cdp_amount) {
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('order_measurement_unit', 'Con este cambio Monto total de Ítems: ' . $total_amountitems . ', es MAYOR a: ' . $cdp_amount . ' monto de CDP del Pedido, DEBE ACTUALIZAR CDP...');
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
