<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\FileOrder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrdersFilesController extends Controller
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
        $index_permissions = ['admin.files.index', 'orders.files.index'];
        $create_permissions = ['admin.files.create', 'orders.files.create'];
        $show_permissions = ['admin.files.show', 'orders.files.show'];
        $download_permissions = ['admin.files.download', 'orders.files.download'];
        $update_permissions = ['admin.files.update', 'orders.files.update'];

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


    public function index(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        $contract = $order->contract; // Accedemos a la relación contract
        $components = $order->components; // Obtenemos los componentes del pedido

        // Obtenemos los eventos del pedido
        $files = $order->files;
                
        return view('contract.orders.files', compact('order', 'contract', 'components', 'files'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $order_id)
    {
        // $order = Order::findOrFail($order_id);
        $order = Order::with('files')->findOrFail($order_id);
        $files = $order->files;                

        $contract = $order->contract; // Accedemos a la relación contract        
        $post_max_size = $this->postMaxSize;
        $post_max_size5 = $this->postMaxSize5;

        return view('contract.orders.create_files', compact('order', 'post_max_size','contract', 'files', 'post_max_size5'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_filedncp(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        $post_max_size = $this->postMaxSize;
        return view('order.files.create_filedncp', compact('order', 'post_max_size'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_filedncp_con(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        $post_max_size = $this->postMaxSize;
        return view('order.files.create_filedncp_con', compact('order', 'post_max_size'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_cuadro_compar(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        $post_max_size = $this->postMaxSize;
        return view('order.files.create_cuadro_compar', compact('order', 'post_max_size'));
    }


    /**
     * Funcionalidad de agregar de archivo.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        
        $rules = array(
            'description' => 'string|required|max:500',
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
        if(!in_array($extension, array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'dwg'))){
            $validator = Validator::make($request->input(), []); // Creamos un objeto validator
            $validator->errors()->add('file', 'El archivo introducido debe tener formato: doc, docx, xls, xlsx, pdf, dwg'); // Agregamos el error
            return back()->withErrors($validator)->withInput();
        }

        if($this->hasDangerousMimeType($request->file('file'))){
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('file', 'El contenido del archivo no corresponde a un formato de documento válido.');
            return back()->withErrors($validator)->withInput();
        }

        // Pasó todas las validaciones, guardamos el archivo        
        $fileName = 'file_order_'.$order->id.'.'.$extension; // nombre a guardar
        // Cargamos el archivo en disco privado (storage/app/files), sin acceso público directo
        $path = $request->file('file')->storeAs('files', $fileName);

        $file = new FileOrder;
        $file->order_id = $order_id;
        $file->description = $request->input('description');
        $file->file = $fileName;
        $file->file_state = 1;        
        $file->user_id = $request->user()->id;  // usuario logueado        
        $file->save();
        
        return redirect()->route('orders.order.files', $order_id)->with('success', 'Archivo agregado correctamente');
        
    }

    /**
     * Funcionalidad de agregar de archivo.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_filedncp(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        
        $rules = array(
            'description' => 'string|required|max:100',
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (!$request->hasFile('file')) {
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('file', 'El campo es requerido, debe ingresar un archivo WORD, PDF o EXCEL.');
            return back()->withErrors($validator)->withInput();
        }

        // chequeamos la extension del archivo subido
        $extension = $request->file('file')->getClientOriginalExtension();
        if (!in_array($extension, array('doc', 'docx', 'pdf', 'xls', 'xlsx'))) {
            $validator = Validator::make($request->input(), []); // Creamos un objeto validator
            $validator->errors()->add('file', 'El archivo introducido debe corresponder a alguno de los siguientes formatos: doc, docx, pdf, xls, xlsx.'); // Agregamos el error
            return back()->withErrors($validator)->withInput();
        }

        if ($this->hasDangerousMimeType($request->file('file'))) {
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('file', 'El contenido del archivo no corresponde a un formato de documento válido.');
            return back()->withErrors($validator)->withInput();
        }

        // Pasó todas las validaciones, guardamos el archivo
        $fileName = time().'-order-file.'.$extension; // nombre a guardar
        // Cargamos el archivo en disco privado (storage/app/files), sin acceso público directo
        $path = $request->file('file')->storeAs('files', $fileName);

        $file = new FileOrder;
        $file->description = $request->input('description');
        $file->file = $fileName;
        // *** filetype 1 = archivos de reparos // filetype 4 = Addendas ****        
        if ($request->user()->dependency->id == 60) {
            $file->file_type = 4;
        }else{
            $file->file_type = 1;        
        }
        $file->order_id = $order_id;
        $file->order_state_id = $order->actual_state;
        $file->creator_user_id = $request->user()->id;  // usuario logueado
        $file->dependency_id = $request->user()->dependency_id;  // dependencia del usuario
        $file->save();

        // Dependiendo del modulo direccionamos a la vista del pedido
        if($request->user()->hasPermission(['plannings.files.create'])){
            return redirect()->route('plannings.show', $order_id)->with('success', 'Archivo agregado correctamente');
        }elseif($request->user()->hasPermission(['tenders.files.create'])){
            return redirect()->route('tenders.show', $order_id);  
        }elseif($request->user()->hasPermission(['minor_purchases.files.create'])){
            return redirect()->route('minor_purchases.show', $order_id);         
        }elseif($request->user()->hasPermission(['awards.files.create'])){
            return redirect()->route('awards.show', $order_id);
        }elseif($request->user()->hasPermission(['exceptions.files.create'])){
            return redirect()->route('exceptions.show', $order_id);
        }elseif($request->user()->hasPermission(['contracts.files.create'])){
            return redirect()->route('contracts.show', $order_id);        
        }elseif($request->user()->hasPermission(['utas.files.create'])){
            return redirect()->route('utas.show', $order_id);
        }elseif($request->user()->hasPermission(['legal_advices.files.create'])){
            return redirect()->route('legal_advices.show', $order_id);
        }elseif($request->user()->hasPermission(['comites.files.create'])){
            return redirect()->route('comites.show', $order_id);            
        }elseif($request->user()->hasPermission(['coordinations.files.create'])){
            return redirect()->route('coordinations.show', $order_id);
        }elseif($request->user()->hasPermission(['dgafs.files.create'])){
            return redirect()->route('dgafs.show', $order_id);
        }elseif($request->user()->hasPermission(['documentals.files.create'])){
            return redirect()->route('documentals.show', $order_id);    
        }else{
            return redirect()->route('orders.show', $order_id)->with('success', 'Archivo agregado correctamente');
        }
    }

    /**
     * Funcionalidad de agregar de archivo.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_filedncp_con(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        
        $rules = array(
            'description' => 'string|required|max:100',
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (!$request->hasFile('file')) {
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('file', 'El campo es requerido, debe ingresar un archivo WORD, PDF o EXCEL.');
            return back()->withErrors($validator)->withInput();
        }

        // chequeamos la extension del archivo subido
        $extension = $request->file('file')->getClientOriginalExtension();
        if (!in_array($extension, array('doc', 'docx', 'pdf', 'xls', 'xlsx'))) {
            $validator = Validator::make($request->input(), []); // Creamos un objeto validator
            $validator->errors()->add('file', 'El archivo introducido debe corresponder a alguno de los siguientes formatos: doc, docx, pdf, xls, xlsx.'); // Agregamos el error
            return back()->withErrors($validator)->withInput();
        }

        if ($this->hasDangerousMimeType($request->file('file'))) {
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('file', 'El contenido del archivo no corresponde a un formato de documento válido.');
            return back()->withErrors($validator)->withInput();
        }

        // Pasó todas las validaciones, guardamos el archivo
        $fileName = time().'-order-file.'.$extension; // nombre a guardar
        // Cargamos el archivo en disco privado (storage/app/files), sin acceso público directo
        $path = $request->file('file')->storeAs('files', $fileName);

        $file = new FileOrder;
        $file->description = $request->input('description');
        $file->file = $fileName;
        // *** filetype 6 = archivos de consultas DNCP ****        
        $file->file_type = 6;        
        $file->order_id = $order_id;
        $file->order_state_id = $order->actual_state;
        $file->creator_user_id = $request->user()->id;  // usuario logueado
        $file->dependency_id = $request->user()->dependency_id;  // dependencia del usuario
        $file->save();

        // Dependiendo del modulo direccionamos a la vista del pedido
        if($request->user()->hasPermission(['plannings.files.create'])){
            return redirect()->route('plannings.show', $order_id)->with('success', 'Archivo agregado correctamente');
        }elseif($request->user()->hasPermission(['tenders.files.create'])){
            return redirect()->route('tenders.show', $order_id);  
        }elseif($request->user()->hasPermission(['minor_purchases.files.create'])){
            return redirect()->route('minor_purchases.show', $order_id);         
        }elseif($request->user()->hasPermission(['awards.files.create'])){
            return redirect()->route('awards.show', $order_id);
        }elseif($request->user()->hasPermission(['exceptions.files.create'])){
            return redirect()->route('exceptions.show', $order_id);
        }elseif($request->user()->hasPermission(['contracts.files.create'])){
            return redirect()->route('contracts.show', $order_id);        
        }elseif($request->user()->hasPermission(['utas.files.create'])){
            return redirect()->route('utas.show', $order_id);
        }elseif($request->user()->hasPermission(['legal_advices.files.create'])){
            return redirect()->route('legal_advices.show', $order_id);
        }elseif($request->user()->hasPermission(['comites.files.create'])){
            return redirect()->route('comites.show', $order_id);            
        }elseif($request->user()->hasPermission(['coordinations.files.create'])){
            return redirect()->route('coordinations.show', $order_id);
        }elseif($request->user()->hasPermission(['dgafs.files.create'])){
            return redirect()->route('dgafs.show', $order_id);
        }elseif($request->user()->hasPermission(['documentals.files.create'])){
            return redirect()->route('documentals.show', $order_id);    
        }else{
            return redirect()->route('orders.show', $order_id)->with('success', 'Archivo agregado correctamente');
        }
    }

    /**
     * Funcionalidad de agregar cuadro comparativo.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_cuadro_compar(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        
        $rules = array(
            'description' => 'string|required|max:100',
        );
        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (!$request->hasFile('file')) {
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('file', 'El campo es requerido, debe ingresar un archivo WORD, PDF o EXCEL.');
            return back()->withErrors($validator)->withInput();
        }

        // chequeamos la extension del archivo subido
        $extension = $request->file('file')->getClientOriginalExtension();
        if (!in_array($extension, array('doc', 'docx', 'pdf', 'xls', 'xlsx'))) {
            $validator = Validator::make($request->input(), []); // Creamos un objeto validator
            $validator->errors()->add('file', 'El archivo introducido debe corresponder a alguno de los siguientes formatos: doc, docx, pdf, xls, xlsx.'); // Agregamos el error
            return back()->withErrors($validator)->withInput();
        }

        if ($this->hasDangerousMimeType($request->file('file'))) {
            $validator = Validator::make($request->input(), []);
            $validator->errors()->add('file', 'El contenido del archivo no corresponde a un formato de documento válido.');
            return back()->withErrors($validator)->withInput();
        }

        // Pasó todas las validaciones, guardamos el archivo
        $fileName = time().'-order-file.'.$extension; // nombre a guardar
        // Cargamos el archivo en disco privado (storage/app/files), sin acceso público directo
        $path = $request->file('file')->storeAs('files', $fileName);

        $file = new FileOrder;
        $file->description = $request->input('description');
        $file->file = $fileName;
        // *** filetype 7 = cuadro comparativos
        $file->file_type = 7;

        $file->order_id = $order_id;
        $file->order_state_id = $order->actual_state;
        $file->creator_user_id = $request->user()->id;  // usuario logueado
        $file->dependency_id = $request->user()->dependency_id;  // dependencia del usuario
        $file->save();

        // Dependiendo del modulo direccionamos a la vista del pedido
        if($request->user()->hasPermission(['plannings.files.create'])){
            return redirect()->route('plannings.show', $order_id)->with('success', 'Archivo agregado correctamente');
        }elseif($request->user()->hasPermission(['tenders.files.create'])){
            return redirect()->route('tenders.show', $order_id);  
        }elseif($request->user()->hasPermission(['minor_purchases.files.create'])){
            return redirect()->route('minor_purchases.show', $order_id);         
        }elseif($request->user()->hasPermission(['awards.files.create'])){
            return redirect()->route('awards.show', $order_id);
        }elseif($request->user()->hasPermission(['exceptions.files.create'])){
            return redirect()->route('exceptions.show', $order_id);
        }elseif($request->user()->hasPermission(['contracts.files.create'])){
            return redirect()->route('contracts.show', $order_id);        
        }elseif($request->user()->hasPermission(['utas.files.create'])){
            return redirect()->route('utas.show', $order_id);
        }elseif($request->user()->hasPermission(['legal_advices.files.create'])){
            return redirect()->route('legal_advices.show', $order_id);
        }elseif($request->user()->hasPermission(['comites.files.create'])){
            return redirect()->route('comites.show', $order_id);            
        }elseif($request->user()->hasPermission(['coordinations.files.create'])){
            return redirect()->route('coordinations.show', $order_id);
        }elseif($request->user()->hasPermission(['dgafs.files.create'])){
            return redirect()->route('dgafs.show', $order_id);
        }elseif($request->user()->hasPermission(['documentals.files.create'])){
            return redirect()->route('documentals.show', $order_id);
        }else{
            return redirect()->route('orders.show', $order_id)->with('success', 'Archivo agregado correctamente');
        }
        
    }

    /**
     * Funcionalidad para descargar archivo.
     *
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request, $file_id)
    {
        $file = FileOrder::findOrFail($file_id);
        // Eliminamos caracteres especiales de la descripcion y pasamos como nombre del archivo
        $name = Str::slug($file->description);
        //Separamos nombre y extensión de la descripción del archivo
        $filename = explode(".", $file->file);
        //Obteemos extensión del archivo
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
        $file = FileOrder::findOrFail($file_id);
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
        // Chequeamos que el usuario actual disponga de permisos de eliminacion
        if(!$request->user()->hasPermission(['admin.files.delete', 'orders.files.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $file = FileOrder::find($file_id);
        // Eliminamos el archivo
        Storage::delete('files/'.$file->file);
        
        // Eliminamos el registro del archivo
        $file->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el archivo ', 'code' => 200], 200);
    }
}