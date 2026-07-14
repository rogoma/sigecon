<?php

namespace App\Http\Controllers\contract;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Contract;
use App\Models\File;
use App\Models\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\Models\SubItem;
use App\Models\Rubro;
use App\Models\ItemContract;



class ContractsFilesController extends Controller
{
    protected $postMaxSize;
    protected $postMaxSize5;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $index_permissions = ['admin.files.index','contracts.files.index'];
        $create_permissions = ['admin.files.create','contracts.files.create'];
        $show_permissions = ['admin.files.show', 'contracts.files.show'];
        $download_permissions = ['admin.files.download',
                            'contracts.files.download'];
        $update_permissions = ['admin.files.update', 'contracts.files.update'];

        $this->middleware('checkPermission:'.implode(',',$index_permissions))->only('index'); // Permiso para index
        $this->middleware('checkPermission:'.implode(',',$create_permissions))->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:'.implode(',',$show_permissions))->only(['show', 'view']);   // Permiso para show/view
        $this->middleware('checkPermission:'.implode(',',$download_permissions))->only(['download']);   // Permiso para download
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

        //MÁXIMO PERMITIDO 5 MEGAS POR CADA ARCHIVO
        $this->postMaxSize5 = 1048576 * 5;

    }

    /**
     * Chequea el tipo MIME real del archivo (detectado por su contenido, no por la extensión
     * que envía el cliente) para rechazar archivos ejecutables/script disfrazados con otra extensión.
     */
    private function hasDangerousMimeType($file)
    {
        $dangerousMimes = [
            'text/x-php', 'application/x-php', 'application/x-httpd-php',
            'text/html', 'application/javascript', 'text/javascript',
            'application/x-sh', 'application/x-executable', 'application/x-msdownload',
            'application/x-msdos-program', 'application/x-bat',
        ];
        return in_array($file->getMimeType(), $dangerousMimes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);
        $post_max_size = $this->postMaxSize;
        $post_max_size5 = $this->postMaxSize5;

        return view('contract.files.create_pol', compact('contract', 'post_max_size','post_max_size5'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_con(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);
        $post_max_size = $this->postMaxSize;
        $post_max_size5 = $this->postMaxSize5;

        return view('contract.files.create_con', compact('contract', 'post_max_size','post_max_size5'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_eval(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);
        $post_max_size = $this->postMaxSize;
        $post_max_size5 = $this->postMaxSize5;

        // Chequeamos que haya Fiscal asignado para proceder
        // if($contract->fiscal1_id != null ){

        // }else{
        //     return back()->with('error', 'Para generar una Evaluación debe asignar un Fiscal');
        // }

        return view('contract.files.create_eval', compact('contract', 'post_max_size','post_max_size5'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadExcelRubros(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);
        $components = Component::orderBy('id')->get();//ordenado por id componente
        

        // return view('contract.files.create_rubros', compact('contract', 'components','post_max_size'));
        return view('contract.files.uploadExcel', compact('contract', 'components'));
    }

        
    /**
     * Formulario de agregacion de ítems Archivo Excel a un CONTRATO ABIERTO.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_rubros(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);
        
        //capturamos el id del contrato para enviar a la vista show de contrato al finalizar
        // $contract_id = $order->contract_id;

        //VERIFICAMOS SI HAY ITEM EN EL PEDIDO, SI EXISTE ASUME VALOR 1, SINO EXISTE ASUME VALOR 0
        // $cant_item = 0;
        // if ($order->items->count() > 0){
        //     $cant_item = 1;
        // }
        
        if($request->hasFile('excel')){
            // chequeamos la extension del archivo subido
            if($request->file('excel')->getClientOriginalExtension() != 'xls' && $request->file('excel')->getClientOriginalExtension() != 'xlsx'){
                $validator = Validator::make($request->input(), []); // Creamos un objeto validator
                $validator->errors()->add('excel', 'El archivo introducido debe ser un excel de tipo: xls o xlsx'); // Agregamos el error
                return back()->withErrors($validator)->withInput();
            }

            // creamos un array de indices de las columnas
            $header = array('component_id','subItem_id','rubro_id', 'item_number','rubro','quantity', 
            'unid','unit_price_mo','unit_price_mat', 'tot_price_mo', 'tot_price_mat');

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
            // $order_amount_items = 0;
            // $tot_tot_price_mo = 0;
            // $tot_tot_price_mat = 0;

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
                    'component_id' => [
                    'numeric','required','max:2147483647',
                        Rule::unique('items_contracts')->where(function ($query) use ($contract_id) {
                        return $query->where('contract_id', $contract_id);
                        })
                    ],
                    // 'component_id' => 'numeric|required',                    
                    'subItem_id' => 'numeric|required',
                    'rubro_id' => 'numeric|required',
                    'item_number' => 'numeric|required',                    
                    'quantity' => 'numeric|required',
                    'unid' => 'string|required',
                    'unit_price_mo' => 'numeric|required|max:2147483647',                    
                    'unit_price_mat' => 'numeric|required|max:2147483647',
                    // 'tot_price_mo' => 'numeric|required|max:2147483647',
                    // 'tot_price_mat' => 'numeric|required|max:2147483647'
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
                $compo = intval($request->component_id);
                $compo2 = intval($item['component_id']);                
                // var_dump($compo);
                // var_dump($item['component_id']);exit();

                //Controlamos que si $compo = 9999 permita agregar archivo masivo de excel
                if ($compo == 9999) {                
                    //no controla si el componente es igual al componente del formulario
                }else{
                    if ($compo !== $compo2) {
                        $validator->errors()->add('component', 'Componente del Archivo Excel no es igual a Componente del Formulario, verifique que el valor del Componente en la planilla sea numérica....');
                        return back()->withErrors($validator)->withInput()->with('fila', $row);
                    }
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
                // $tot_tot_price_mo = $tot_tot_price_mo + $item['tot_price_mo'];
                // $tot_tot_price_mat = $tot_tot_price_mat + $item['tot_price_mat'];                
            }
           
            // En caso de haber pasado todas las validaciones guardamos los datos
            foreach ($items as $item) {
                $new_item = new ItemContract; 
                $new_item->contract_id = $contract_id;
                $new_item->component_id = $item['component_id'];
                // $new_item->batch = empty($item['batch'])? NULL : $item['batch'];
                $new_item->item_number = empty($item['item_number'])? NULL : $item['item_number'];
                $new_item->rubro_id = $item['rubro_id'];

                if ($item['rubro_id'] == 9999){
                    $new_item->subitem_id = $item['subItem_id'];
                }else{
                    $new_item->subitem_id = NULL;
                }                 
                
                $new_item->quantity = $item['quantity'];
                $new_item->unit_price_mo = intval($item['unit_price_mo']);
                $new_item->unit_price_mat = intval($item['unit_price_mat']);
                // $new_item->tot_price_mo = $item['tot_price_mo'];
                // $new_item->tot_price_mat = $item['tot_price_mat'];
                $new_item->creator_user_id = $request->user()->id;  // usuario logueado
                $new_item->save();
            }       
            
            // GRABAMOS COMO TOTAL EN ORDERS LA SUMATORIA DE ITEMS + EL MONTO TOTAL DEL PEDIDO ANTES DE AGREGAR LOS NUEVOS REGISTROS DEL EXCEL           
            
             // COMPARA EL MONTO TOTAL DEL PEDIDO VERSUS EL MONTO TOTAL DE LOS ÍTEMS
            //  $order = Order::findOrFail($order_id);
             //CALCULA EL TOTAL GRAL PARA GRABAR EN ORDERS
            //  $order->total_amount = $tot_tot_price_mo + $tot_tot_price_mat;
            //  $order->save(); 

            return redirect()->route('contracts.show', $contract_id)->with('success', 'Archivo de rubros importado correctamente'); // Caso usuario posee rol pedidos            

        }else{
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('excel', 'El campo es requerido');
            return back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Funcionalidad de agregar de archivo.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);

        $rules = array(
            'description' => 'string|required|max:500',
        );

        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if(!$request->hasFile('file')){
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('file', 'El campo es requerido, debe ingresar un archivo WORD, PDF o EXCEL.');
            return back()->withErrors($validator)->withInput();
        }

        // chequeamos la extension del archivo subido
        $extension = $request->file('file')->getClientOriginalExtension();
        if(!in_array($extension, array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'dwg'))){
            $validator = Validator::make($request->input(), []); // Creamos un objeto validator
            $validator->errors()->add('file', 'El archivo introducido debe tener formato: doc, docx, pdf, xls, xlsx, dwg'); // Agregamos el error
            return back()->withErrors($validator)->withInput();
        }

        if($this->hasDangerousMimeType($request->file('file'))){
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('file', 'El contenido del archivo no corresponde a un formato de documento válido.');
            return back()->withErrors($validator)->withInput();
        }

        // Pasó todas las validaciones, guardamos el archivo
        // $fileName = time().'-contract-file.'.$extension; // nombre a guardar
        // $fileName = 'contrato_nro_'.$contract->number_year.'.'.$extension; // nombre a guardar
        $fileName = 'contrato_nro_'.$contract->number_year.$contract->year.'.'.time().$extension; // nombre a guardar
        // Cargamos el archivo en disco privado (storage/app/files), sin acceso público directo
        $path = $request->file('file')->storeAs('files', $fileName);

        $file = new File;
        $file->description = $request->input('description');
        $file->file = $fileName;
        $file->file_type = 1;//pólizas
        $file->contract_id = $contract_id;
        $file->contract_state_id = $contract->contract_state_id;
        $file->user_id = $request->user()->id;  // usuario logueado
        $file->dependency_id = $request->user()->dependency_id;  // dependencia del usuario
        $file->save();

        return redirect()->route('contracts.show', $contract_id);
    }

    /**
     * Funcionalidad para agregar archivo pestaña Archivos.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_con(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);

        $rules = array(
            'description' => 'string|required|max:500',
        );

        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if(!$request->hasFile('file')){
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('file', 'El campo es requerido, debe ingresar un archivo WORD, PDF o EXCEL.');
            return back()->withErrors($validator)->withInput();
        }

        // chequeamos la extension del archivo subido
        $extension = $request->file('file')->getClientOriginalExtension();
        if(!in_array($extension, array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'dwg'))){
            $validator = Validator::make($request->input(), []); // Creamos un objeto validator
            $validator->errors()->add('file', 'El archivo introducido debe corresponder a alguno de los siguientes formatos: doc, docx, pdf, xls, xlsx.'); // Agregamos el error
            return back()->withErrors($validator)->withInput();
        }

        if($this->hasDangerousMimeType($request->file('file'))){
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('file', 'El contenido del archivo no corresponde a un formato de documento válido.');
            return back()->withErrors($validator)->withInput();
        }

        // Pasó todas las validaciones, guardamos el archivo
        // $fileName = time().'-contract-file.'.$extension; // nombre a guardar
        // $fileName = 'contrato_nro_'.$request->input($contract->number_year).'.'.$extension; // nombre a guardar
        $fileName = 'contrato'.time().'.'.$extension; // nombre a guardar
        // Cargamos el archivo en disco privado (storage/app/files), sin acceso público directo
        $path = $request->file('file')->storeAs('files', $fileName);

        $file = new File;
        $file->description = $request->input('description');
        $file->file = $fileName;
        $file->file_type = 3;//contratos
        $file->file_state = 1;//activo
        $file->contract_id = $contract_id;
        $file->contract_state_id = $contract->contract_state_id;
        $file->user_id = $request->user()->id;  // usuario logueado
        $file->dependency_id = $request->user()->dependency_id;  // dependencia del usuario
        $file->save();

        return redirect()->route('contracts.show', $contract_id);
    }



    /**
     * Funcionalidad de agregar de archivo.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_eval(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);

        $rules = array(
            'description' => 'string|required|max:500',
        );

        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if(!$request->hasFile('file')){
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('file', 'El campo es requerido, debe ingresar un archivo WORD, PDF o EXCEL.');
            return back()->withErrors($validator)->withInput();
        }

        // chequeamos la extension del archivo subido
        $extension = $request->file('file')->getClientOriginalExtension();
        if(!in_array($extension, array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'dwg'))){
            $validator = Validator::make($request->input(), []); // Creamos un objeto validator
            $validator->errors()->add('file', 'El archivo introducido debe corresponder a alguno de los siguientes formatos: doc, docx, pdf, xls, xlsx.'); // Agregamos el error
            return back()->withErrors($validator)->withInput();
        }

        if($this->hasDangerousMimeType($request->file('file'))){
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('file', 'El contenido del archivo no corresponde a un formato de documento válido.');
            return back()->withErrors($validator)->withInput();
        }

        // Pasó todas las validaciones, guardamos el archivo
        // $fileName = time().'-contract-file.'.$extension; // nombre a guardar
        // $fileName = 'contrato_nro_'.$request->input($contract->number_year).'.'.$extension; // nombre a guardar
        $fileName = 'evaluación'.time().'.'.$extension; // nombre a guardar
        // Cargamos el archivo en disco privado (storage/app/files), sin acceso público directo
        $path = $request->file('file')->storeAs('files', $fileName);

        $file = new File;
        $file->description = $request->input('description');
        $file->file = $fileName;
        $file->file_type = 6;//evaluaciones
        $file->file_state = 1;//activo
        $file->contract_id = $contract_id;
        $file->contract_state_id = $contract->contract_state_id;
        $file->user_id = $request->user()->id;  // usuario logueado
        $file->dependency_id = $request->user()->dependency_id;  // dependencia del usuario
        $file->save();

        return redirect()->route('contracts.show', $contract_id);
    }

    

    //Para mostrar Planillas EXCEL Región Oriental guardado en el Proyecto con formato ZIP
    public function ArchivoPedido(){
        header("Content-type: application/zip");
        header("Content-Disposition: inline; filename=Planillas Región Oriental.zip");        
        readfile("files/Planillas Región Oriental.zip");
    }

    //Para mostrar Planillas EXCEL Región Occidental guardado en el Proyecto con formato ZIP
    public function ArchivoPedido2(){
        header("Content-type: application/zip");
        header("Content-Disposition: inline; filename=Planillas Región Occidental.zip");        
        readfile("files/Planillas Región Occidental.zip");
    }

    //Para mostrar Planilla EXCEL Región Oriental con todos los rubros
    public function ArchivoPedido3(){
        header("Content-type: application/xlsx");
        header("Content-Disposition: inline; filename=Todos los Componentes Reg. Oriental.xlsx");        
        readfile("files/Todos los Componentes Reg. Oriental.xlsx");
    }

    //Para mostrar Planilla EXCEL Región Occidental con todos los rubros
    public function ArchivoPedido4(){
        header("Content-type: application/xlsx");
        header("Content-Disposition: inline; filename=Todos los Componentes Reg. Occidental.xlsx");        
        readfile("files/Todos los Componentes Reg. Occidental.xlsx");
    }
    
    

    /**
     * Funcionalidad para descargar archivo.
     *
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request, $file_id)
    {
        $file = File::findOrFail($file_id);
        // Eliminamos caracteres especiales de la descripcion y pasamos como nombre del archivo
        $name = Str::slug($file->description);
        //Separamos nombre y extensión de Storagela descripción del archivo
        $filename = explode(".", $file->file);
        //Obtenemos extensión del archivo
        $extension = $filename[1];
        return Storage::download('files/'.$file->file, $name .'.'. $extension);
    }

    /**
     * Sirve el archivo inline (para previsualizar en el navegador), respetando los mismos permisos que download().
     *
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request, $file_id)
    {
        $file = File::findOrFail($file_id);
        return Storage::response('files/'.$file->file);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $file_id)
    {
        // Chequeamos que el usuario actual disponga permisos de anular
        if(!$request->user()->hasPermission(['admin.files.delete', 'contracts.files.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $file = File::find($file_id);

        // Capturamos nombre del archivo almacenado en la tabla
        $filename = $file->file;

        // Eliminamos el archivo
        // Storage::delete('files/'.$filename);

        // Eliminamos el registro del archivo
        // $file->delete();

        $file->file_state = 2;//inactivo
        $file->save();

        return response()->json(['status' => 'success', 'message' => 'Se ha anulado el archivo ', 'code' => 200], 200);
    }
}
