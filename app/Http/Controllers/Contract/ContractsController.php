<?php

namespace App\Http\Controllers\Contract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\Enviar_alertas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use App\Models\Contract;
use App\Models\Order;
use Illuminate\Validation\Rule;
use App\Models\OrderMultiYear;
use App\Models\Dependency;
use App\Models\Modality;
use App\Models\Provider;
use App\Models\SubProgram;
use App\Models\FundingSource;
use App\Models\FinancialOrganism;
use App\Models\ExpenditureObject;
use App\Models\OrderOrderState;
use App\Models\ContractState;
use App\Models\ContractType;
use App\Models\User;
use App\Models\Event;
use App\Models\ItemContract;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\DB;
// use App\Exports\OrdersExport;
use App\Exports\OrdersExport2;
use App\Exports\OrdersExport3;
use App\Exports\OrdersExport6;


use App\Rules\ValidarCalendarios;

use Maatwebsite\Excel\Facades\Excel;
// use Illuminate\Support\Carbon;
use Carbon\Carbon;


class ContractsController extends Controller
{
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $index_permissions = ['admin.contracts.index','contracts.contracts.index','contracts.items.index','derive_contracts.contracts.show'];
        $create_permissions = ['admin.contracts.create','contracts.contracts.create'];
        $update_permissions = ['admin.contracts.update', 'contracts.contracts.update'];

        $this->middleware('checkPermission:'.implode(',',$index_permissions))->only('index'); // Permiso para index
        $this->middleware('checkPermission:'.implode(',',$create_permissions))->only(['create', 'store']);   // Permiso para create
        $this->middleware('checkPermission:'.implode(',',$update_permissions))->only(['edit', 'update']);   // Permiso para update
    }

    public function calculo(Request $request)
    {
        dd($request->date);
        // Envía el resultado a la vista
        // return view('contract.contracts.create');
    }

    // /**
    //  * Listado de todos los pedidos.
    //  * @return \Illuminate\Http\Response
    //  */
    // public function index(Request $request)
    // {
    //     if($request->user()->hasPermission(['admin.contracts.index'])){
    //         //NO SE MUESTRAN LOS PEDIDOS ANULADOS
    //         $contracts = Contract::where('contract_state_id', '>=', 1)
    //                 ->orderBy('iddncp','asc')
    //                 ->get();
    //         $dependency = $request->user()->dependency_id;
    //     }else{
    //         // Para los otros roles se visualizan los pedidos de la dependencia del usuario "NO MUESTRA PEDIDOS ANULADOS"
    //         $contracts = Contract::where('dependency_id', $request->user()->dependency_id)
    //                 ->where('contract_state_id', '>=', 1)
    //                 ->orderBy('iddncp','asc')
    //                 ->get();
    //         //Se captura el id de de la dependencia para pasar este parámetro y así filtrar en Panel de llamados
    //         $dependency = $request->user()->dependency_id;
    //     }
    //     return view('contract.contracts.index', compact('contracts'));
    // }

    /**
     * Listado de todos los pedidos.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        if($request->user()->hasPermission(['admin.contracts.index', 'contracts.contracts.all'])){
        // if($request->user()->hasPermission(['admin.contracts.index'])){            
            //SE DEBEN MOSTRAR TODOS LOS PEDIDOS SI ES DE UOC NO IMPORTAN LOS ESTADOS
            //DETERMINAR QUE DEPENDENCIAS DEBEN SOLO VER CONTRATOS DE OBRAS
            $contracts = Contract::with(['dependency', 'provider', 'contractState', 'contractType'])
                    ->where('contract_state_id', '>=', 1)
                    ->where('contract_type_id', '=', 2)//solo muestra contratos de obras
                    ->where('contract_state_id', '=', 1)//solo muestra contratos en cursos
                    ->where('year_adj', '>=', 2024)//solo muestra desde año 2024
                    ->orderBy('iddncp','asc')
                    ->get();
            $dependency = $request->user()->dependency_id;
        }else{
            $contracts = Contract::with(['dependency', 'provider', 'contractState', 'contractType'])
            ->where('dependency_id', $request->user()->dependency_id)
            ->where('contract_state_id', '>=', 1)
            // ->where('contract_type_id', '=', 2)//solo muestra contratos de obras
            ->orderBy('iddncp','asc')
            ->get();

            $dependency = $request->user()->dependency_id;
        }

        //PARA MOSTRAR SE DEBEN MOSTRAR TODO SOLO A FISCALES
        // Para ver contratos no anulados asignados a usuarios fiscales
        if($request->user()->hasPermission(['contracts.contracts.fiscal'])){
                $contracts = Contract::with(['dependency', 'provider', 'contractState', 'contractType'])
                ->where(function ($query) use ($request) {
                    $query->where('fiscal1_id', $request->user()->id)
                        ->orWhere('fiscal2_id', $request->user()->id)
                        ->orWhere('fiscal3_id', $request->user()->id)
                        ->orWhere('fiscal4_id', $request->user()->id);
                })
                ->where('contract_state_id', '>=', 1)
                ->orderBy('iddncp', 'asc')
                ->get();
        }

        //PARA MOSTRAR CONTRATO A CONTRATISTA role = 4
        $role_user = $request->user()->role_id;

        if($role_user == 4){
             $contracts = Contract::with(['dependency', 'provider', 'contractState', 'contractType'])
                ->where(function ($query) use ($request) {
                    $query->where('contra_user_id', $request->user()->id);
                })
                ->where('contract_state_id', '>=', 1)
                ->get();
        }
                
        return view('contract.contracts.index', compact('contracts'));
    }

    //Para exportar a Excel pedidos encurso aún sn adjudicación
    // public function exportarExcel()
    // {
    //     return Excel::download(new OrdersExport, 'pedidos.xlsx');

    // }

    //Para exportar a Excel pedidos adjudicados
    public function exportarExcel2()
    {
        return Excel::download(new OrdersExport2, 'pedidos_adjudicados.xlsx');

    }

    //Para exportar a Excel usuarios
    public function exportarExcel3()
    {
        return Excel::download(new OrdersExport3, 'usuarios.xlsx');

    }

    //Para exportar a Excel Alerta de pólizas
    public function exportarExcel6()
    {
        return Excel::download(new OrdersExport6, 'Alertas_polizas.xlsx');

    }

    /**
     * Formulario de agregacion de pedido.     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        // $fechaActual = Carbon::now()->toDateString(); // Obtener la fecha actual en formato YYYY-MM-DD
        $dependencies = Dependency::all();
        $modalities = Modality::all();
        $sub_programs = SubProgram::all();
        $funding_sources = FundingSource::all();
        $financial_organisms = FinancialOrganism::all();
        $expenditure_objects = ExpenditureObject::where('level', 3)->get();
        $providers = Provider::all();//se podria filtrar por estado sólo activo
        $contr_states = ContractState::all();
        $contract_types = ContractType::all();        
        $users_admin = User::where('contract_admin', 1)->get();

        return view('contract.contracts.create', compact('dependencies', 'modalities','sub_programs', 'funding_sources', 'financial_organisms',
        'expenditure_objects', 'providers', 'contr_states','contract_types', 'users_admin'));
    }

    /**
     * Formulario de agregacion de pedido cargando archivo excel.     *
     * @return \Illuminate\Http\Response
     */
    public function uploadExcel()
    {
        return view('contract.contracts.uploadExcel');
    }

    /**
     * Funcionalidad de guardado del pedido.     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'description' => 'string|required|max:300',
            'iddncp' => 'string|required|max:999999|min:7',
            'linkdncp' => 'string|required|max:300',
            'number_year' => 'string|required|max:9|unique:contracts,number_year',
            'year_adj' => 'numeric|required|max:9999',
            'sign_date' => 'date_format:d/m/Y|required',
            'provider_id' => 'numeric|required|max:999999',
            'contract_state_id' => 'numeric|required|max:999999',
            'modality_id' => 'numeric|required|max:999999',
            'financial_organism_id' => 'numeric|required|max:999999',
            'contract_type_id' => 'numeric|required|max:999999',
            'minim_amount' => 'string|required|max:9223372036854775807',
            'total_amount' => 'string|required|max:9223372036854775807',            
            'open_contract' => 'numeric|required',
            'dependency_id' => 'numeric|required',
            'contract_admin_id' => 'numeric|required',
            'comments' => 'nullable|max:300',
        );

        // $messages = [
        //     'total_amount.gt' => 'El monto total debe ser mayor que el monto mínimo.',
        // ];

        // $this->validate($request, $rules, $messages);
        
        $validator =  Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $contract = new Contract;

        $contract->description=$request->input('description');

        $iddncp_fin = str_replace('.', '',($request->input('iddncp')));

        if ($iddncp_fin > 999999) {
            $validator->errors()->add('iddncp', 'Número no debe sobrepasar 999.999');
            return back()->withErrors($validator)->withInput()->with('fila');
        }else{
            $contract->iddncp = $iddncp_fin;
        }

        $contract->linkdncp=$request->input('linkdncp');
        $contract->number_year=$request->input('number_year');
        $year_adj_fin = str_replace('.', '',($request->input('year_adj')));
        $contract->year_adj = $year_adj_fin;
        $contract->sign_date = date('Y-m-d', strtotime(str_replace("/", "-", $request->input('sign_date'))));
        $contract->provider_id=$request->input('provider_id');
        $contract->contract_state_id=$request->input('contract_state_id');
        $contract->modality_id=$request->input('modality_id');
        $contract->financial_organism_id=$request->input('financial_organism_id');
        $contract->contract_type_id=$request->input('contract_type_id');

        $minim_amount_fin = str_replace('.', '',($request->input('minim_amount')));
        if ($minim_amount_fin <= 0 ) {
            $validator->errors()->add('minim_amount', 'Monto no puede ser cero ni negativo');
            return back()->withErrors($validator)->withInput();
        }else{
            $contract->minim_amount = $minim_amount_fin;
        }
        
        $total_amount_fin = str_replace('.', '',($request->input('total_amount')));
        if ($total_amount_fin <= 0 ) {
            $validator->errors()->add('total_amount', 'Monto no puede ser cero ni negativo');
            return back()->withErrors($validator)->withInput();
        }else{
            $contract->total_amount = $total_amount_fin;
        }

        //controlamos que monto máximo sea mayor a montominimo
        $total_amount_fin = str_replace('.', '',($request->input('total_amount')));
        $minim_amount_fin = str_replace('.', '',($request->input('minim_amount')));
        if ($total_amount_fin <= $minim_amount_fin ) {
            $validator->errors()->add('total_amount', 'Monto Máximo no puede ser menor o igual a Monto Mínimo, verifique');
            return back()->withErrors($validator)->withInput();
        }else{
            $contract->total_amount = $total_amount_fin;
        }

        $contract->open_contract = $request->input('open_contract');
        $contract->dependency_id = $request->input('dependency_id');
        $contract->contract_admin_id = $request->input('contract_admin_id');
        $contract->comments=$request->input('comments');
        $contract->creator_user_id = $request->user()->id;  // usuario logueado

        $contract->save();
        return redirect()->route('contracts.index')->with('success', 'Llamado agregado correctamente');
    }


    /**
     * Visualización de un pedido general de licitaciones
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function licita(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);
        $user_dependency = $request->user()->dependency_id;
        $role_user = $request->user()->role_id;

        // Obtenemos los archivos cargados por usuarios con tipo de archivos 1 pólizas
        $user_files_pol = $contract->files()->where('dependency_id', $user_dependency)
            ->whereIn('file_type', [1])//1-polizas 3-contratos 4-addendas  5-dictamenes
            ->orderBy('created_at','asc')
            ->get();

        // ROL ADMINISTRADOR Obtenemos los archivos cargados por otras dependencias
        // if($role_user == 1){
            $other_files_pol = $contract->files()->where('dependency_id', '!=', $user_dependency)
            ->whereIn('file_type', [1])//1-polizas 3-contratos 4-addendas  5-dictamenes
            ->orderBy('created_at','asc')
            ->get();
        // }

        // Obtenemos los archivos cargados por usuarios con tipo de archivos 3 contratos
        $user_files_con = $contract->files()->where('dependency_id', $user_dependency)
            ->whereIn('file_type', [3])//1-polizas 3-contratos 4-addendas  5-dictamenes
            ->orderBy('created_at','asc')
            ->get();

        // if($role_user == 1){
            $other_files_con = $contract->files()->where('dependency_id', '!=', $user_dependency)
            ->whereIn('file_type', [3])//1-polizas 3-contratos 4-addendas  5-dictamenes
            ->orderBy('created_at','asc')
            ->get();
        // }

        // chequeamos que el usuario tenga permisos para visualizar el pedido
        if($request->user()->hasPermission(['admin.contracts.show', 'process_contracts.contracts.show',
        'contracts.contracts.index','derive_contracts.contracts.index']) || $contract->dependency_id == $request->user()->dependency_id){
            return view('contract.contracts.show_licita', compact('contract','user_files_pol','user_files_con','other_files_pol','other_files_con'));
        }else{
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }
    }


    /**
     ** Visualización de un pedido de contratos de obras
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $contract_id)
    {
        // Para acceder a eventos y localidades de las órdenes de un contrato
        $contract = Contract::with('orders.events', 'orders.locality')->findOrFail($contract_id);
        
        // Obtener las órdenes del contrato de acuerdo al rol
        $role_user = $request->user()->role_id;

        // SI ES CONTRATISTA ROL = 4
        if($role_user == 4){
            $orders = $contract->orders()->where('order_state_id', 1)->get();
        }else{
            // $orders = $contract->orders;            
            //MUESTRA TODAS LAS ORDENES MENOS LAS QUE ESTAN SUSPENDIDAS = ESTADO 2
            $orders = $contract->orders()->where('order_state_id', '!=', 2)->get();
        }                
        
        // Obtener todos los eventos del contrato a través de las órdenes
        $events = $contract->orders->flatMap->events;

        // para mostrar agrupados los componentes de items_contracts agregados
        $items_contract = ItemContract::with('component') // Carga la relación 'component'
        ->where('contract_id', $contract_id) // Filtra directamente en la consulta según el $contract_id
        ->get()
        ->groupBy('component_id') // Agrupa los elementos por component_id
        ->map(function ($group) {
            // Retorna el primer elemento del grupo ordenado
            return $group->sortBy(function ($item) {
                return $item->component->code . ' ' . $item->component->description; // Ordena por combinación de code y description
            })->first(); // Mantiene solo un registro por grupo
        })
        ->sortBy(function ($item) {
            return $item->component->code; // Ordena los grupos por component->code
        });

        // $user_files_rubros = ItemContract::findOrFail($contract_id);
        $user_dependency = $request->user()->dependency_id;
        $user_id = $request->user()->id;
        $role_user = $request->user()->role_id;        

        // Obtenemos los archivos cargados por usuarios con tipo de archivos 1 pólizas
        $user_files_pol = $contract->files()->where('dependency_id', $user_dependency)
            ->whereIn('file_type', [1])//1-polizas 3-contratos 4-addendas  5-dictamenes
            ->orderBy('created_at','asc')
            ->get();

        // ROL ADMINISTRADOR Obtenemos los archivos cargados por otras dependencias
        // if($role_user == 1){
            $other_files_pol = $contract->files()->where('dependency_id', '!=', $user_dependency)
            ->whereIn('file_type', [1])//1-polizas 3-contratos 4-addendas  5-dictamenes
            ->orderBy('created_at','asc')
            ->get();
        // }

        // Obtenemos los archivos cargados por usuarios con tipo de archivos 3 contratos
        $user_files_con = $contract->files()->where('user_id', $user_id)
            ->whereIn('file_type', [3])//1-polizas 3-contratos 4-addendas 5-dictamenes
            ->whereIn('file_state', [1])//1-activo
            ->orderBy('created_at','asc')
            ->get();

        // if($role_user == 1){
        $other_files_con = $contract->files()->where('user_id', '!=', $user_id)
            ->whereIn('file_type', [3])//1-polizas 3-contratos 4-addendas  5-dictamenes
            ->whereIn('file_state', [1])//1-activo
            ->orderBy('created_at','asc')
            ->get();
        // }

        // Obtenemos los archivos cargados por usuarios con tipo de archivos 6-evaluaciones
        $user_files_eval = $contract->files()->where('user_id', $user_id)
            ->whereIn('file_type', [6])//1-polizas 3-contratos 4-addendas 5-dictamenes 6-evaluaciones
            ->whereIn('file_state', [1])//1-activo
            ->orderBy('created_at','asc')
            ->get();

        // if($role_user == 1){
        $other_files_eval = $contract->files()->where('user_id', '!=', $user_id)
            ->whereIn('file_type', [6])//1-polizas 3-contratos 4-addendas  5-dictamenes 6-evaluaciones
            ->whereIn('file_state', [1])//1-activo
            ->orderBy('created_at','asc')
            ->get();
        
        // chequeamos que el usuario tenga permisos para visualizar el pedido
        if($request->user()->hasPermission(['admin.contracts.show', 'contracts.contracts.show','process_contracts.contracts.show',
        'contracts.contracts.index','derive_contracts.contracts.index']) || $contract->dependency_id == $request->user()->dependency_id){
            
            $role_user = $request->user()->role_id;

            // SI ES CONTRATISTA MUESTRA OTRA VISTA
            if($role_user == 4){
                return view('contract.contracts.showcontra', compact('contract','user_files_pol','user_files_con',
                'user_files_eval','other_files_pol','other_files_con','other_files_eval', 'items_contract', 'orders', 'events'));
            }else{
                return view('contract.contracts.show', compact('contract','user_files_pol','user_files_con',
                'user_files_eval','other_files_pol','other_files_con','other_files_eval', 'items_contract', 'orders', 'events'));
            }
        }else{
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }
    }

    /**
     * Formulario de modificacion de contrato
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);
        // chequeamos que el usuario tenga permisos para editar el llamado
        if($request->user()->hasPermission(['admin.contracts.update','contracts.contracts.update'])){
            $dependencies = Dependency::all();
            $modalities = Modality::all();
            $sub_programs = SubProgram::all();
            $funding_sources = FundingSource::all();
            $financial_organisms = FinancialOrganism::all();
            $expenditure_objects = ExpenditureObject::where('level', 3)->get();
            $providers = Provider::all();//se podria filtrar por estado sólo activo
            $contr_states = ContractState::all();
            $contract_types = ContractType::all();
            $users_admin = User::where('contract_admin', 1)->get();
            // $users = User::all();

            return view('contract.contracts.update', compact('contract','dependencies', 'modalities','sub_programs', 'funding_sources', 'financial_organisms',
                'expenditure_objects', 'providers', 'contr_states','contract_types','users_admin'));
        }else{
            return back()->with('error', 'No tiene los suficientes permisos para editar el llamado.');
        }
    }

    /**
     * Formulario de modificacion de contrato
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit2(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);
        // chequeamos que el usuario tenga permisos para editar el llamado
        if($request->user()->hasPermission(['admin.contracts.update','contracts.contracts.update'])){
            $dependencies = Dependency::all();
            $modalities = Modality::all();
            $sub_programs = SubProgram::all();
            $funding_sources = FundingSource::all();
            $financial_organisms = FinancialOrganism::all();
            $expenditure_objects = ExpenditureObject::where('level', 3)->get();
            $providers = Provider::all();//se podria filtrar por estado sólo activo
            $contr_states = ContractState::all();
            $contract_types = ContractType::all();
            $users_admin = User::where('contract_admin', 1)->get();
            // $users = User::all();

            return view('contract.contracts.update', compact('contract','dependencies', 'modalities','sub_programs', 'funding_sources', 'financial_organisms',
                'expenditure_objects', 'providers', 'contr_states','contract_types','users_admin'));
        }else{
            return back()->with('error', 'No tiene los suficientes permisos para editar el llamado.');
        }
    }

    /**
     * Formulario de modificacion de contrato donde se asigna fiscal de obras
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function asign(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);
        // chequeamos que el usuario tenga permisos para editar el llamado
        if($request->user()->hasPermission(['admin.contracts.update','contracts.contracts.update', 'contracts.fiscales.create'])){
            $dependencies = Dependency::all();
            $modalities = Modality::all();
            $sub_programs = SubProgram::all();
            $funding_sources = FundingSource::all();
            $financial_organisms = FinancialOrganism::all();
            $expenditure_objects = ExpenditureObject::where('level', 3)->get();
            $providers = Provider::all();//se podria filtrar por estado sólo activo
            $contr_states = ContractState::all();
            $contract_types = ContractType::all();
            $users = User::where('role_id', 3)->get();
            return view('contract.contracts.asign_fiscal', compact('contract','dependencies', 'modalities','sub_programs', 'funding_sources', 'financial_organisms',
                'expenditure_objects', 'providers', 'contr_states','contract_types','users'));
        }else{
            return back()->with('error', 'No tiene los suficientes permisos para editar el llamado.');
        }
    }

    /**
     * Formulario de modificacion de contrato donde se asigna usuarios contratista  
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function asign_contra(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);
        // chequeamos que el usuario tenga permisos para editar el llamado
        if($request->user()->hasPermission(['admin.contracts.update','contracts.contracts.update', 'contracts.fiscales.create'])){
            $dependencies = Dependency::all();
            $modalities = Modality::all();
            $sub_programs = SubProgram::all();
            $funding_sources = FundingSource::all();
            $financial_organisms = FinancialOrganism::all();
            $expenditure_objects = ExpenditureObject::where('level', 3)->get();
            $providers = Provider::all();//se podria filtrar por estado sólo activo
            $contr_states = ContractState::all();
            $contract_types = ContractType::all();
            $users = User::where('role_id', 4)->get();
            return view('contract.contracts.asign_contratista', compact('contract','dependencies', 'modalities','sub_programs', 'funding_sources', 'financial_organisms',
                'expenditure_objects', 'providers', 'contr_states','contract_types','users'));
        }else{
            return back()->with('error', 'No tiene los suficientes permisos para editar el llamado.');
        }
    }

    /**
     * Funcionalidad de modificacion del contrato
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);

        $rules = array(
            'description' => 'string|required|max:300',
            'iddncp' => 'string|required|max:999999|min:7',
            'linkdncp' => 'string|required|max:300',

            'number_year' => [
                'string',
                'required',
                Rule::unique('contracts')->ignore($contract->id),
            ],
            // 'number_year' => 'string|required|max:9|unique:contracts,number_year',

            'year_adj' => 'numeric|required|max:9999',
            'sign_date' => 'date_format:d/m/Y|required',
            'provider_id' => 'numeric|required|max:999999',
            'contract_state_id' => 'numeric|required|max:999999',
            'modality_id' => 'numeric|required|max:999999',
            'financial_organism_id' => 'numeric|required|max:999999',
            'contract_type_id' => 'numeric|required|max:999999',
            'minim_amount' => 'string|required|max:9223372036854775807',
            'total_amount' => 'string|required|max:9223372036854775807',
            'open_contract' => 'numeric|required',
            'dependency_id' => 'numeric|required',
            'contract_admin_id' => 'numeric|required',
            'comments' => 'nullable|max:300',
            'observation' => 'nullable|max:500'
            
        );

        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $contract->description=$request->input('description');

        $iddncp_fin = str_replace('.', '',($request->input('iddncp')));

        if ($iddncp_fin > 999999) {
            $validator->errors()->add('iddncp', 'Número no debe sobrepasar 999.999');
            return back()->withErrors($validator)->withInput()->with('fila');
        }else{
            $contract->iddncp = $iddncp_fin;
        }

        $contract->linkdncp=$request->input('linkdncp');
        $contract->number_year=$request->input('number_year');

        $year_adj_fin = str_replace('.', '',($request->input('year_adj')));
        $contract->year_adj = $year_adj_fin;

        $contract->sign_date = date('Y-m-d', strtotime(str_replace("/", "-", $request->input('sign_date'))));
        $contract->provider_id=$request->input('provider_id');
        $contract->contract_state_id=$request->input('contract_state_id');
        $contract->modality_id=$request->input('modality_id');
        $contract->financial_organism_id=$request->input('financial_organism_id');
        $contract->contract_type_id=$request->input('contract_type_id');

        $minim_amount_fin = str_replace('.', '',($request->input('minim_amount')));
        if ($minim_amount_fin <= 0 ) {
            $validator->errors()->add('minim_amount', 'Monto no puede ser cero ni negativo');
            return back()->withErrors($validator)->withInput();
        }else{
            $contract->minim_amount = $minim_amount_fin;
        }
        
        $total_amount_fin = str_replace('.', '',($request->input('total_amount')));
        if ($total_amount_fin <= 0 ) {
            $validator->errors()->add('total_amount', 'Monto no puede ser cero ni negativo');
            return back()->withErrors($validator)->withInput();
        }else{
            $contract->total_amount = $total_amount_fin;
        }

        //controlamos que monto máximo sea mayor a montominimo
        $total_amount_fin = str_replace('.', '',($request->input('total_amount')));
        $minim_amount_fin = str_replace('.', '',($request->input('minim_amount')));
        if ($total_amount_fin <= $minim_amount_fin ) {
            $validator->errors()->add('total_amount', 'Monto Máximo no puede ser menor o igual a Monto Mínimo, verifique');
            return back()->withErrors($validator)->withInput();
        }else{
            $contract->total_amount = $total_amount_fin;
        }

        $contract->open_contract = $request->input('open_contract');
        $contract->dependency_id = $request->input('dependency_id');
        $contract->contract_admin_id = $request->input('contract_admin_id');
        $contract->comments=$request->input('comments');
        $contract->observation=$request->input('observation');
        $contract->creator_user_id = $request->user()->id;  // usuario logueado
        $contract->save();
        return redirect()->route('contracts.show', $contract->id)->with('success', 'Llamado modificado correctamente');
    }

    /**
     * Funcionalidad de modificacion del contrato cuando se agrega Fiscales de Obras
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_fiscal(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);

        $rules = [
            'fiscal1_id' => [
                'numeric',
                'required',
                'max:999999',
                function ($attribute, $value, $fail) {
                    if ($value == request()->input('fiscal2_id') || $value == request()->input('fiscal3_id') || $value == request()->input('fiscal4_id')) {
                        $fail('El campo ' . $attribute . ' no puede ser igual a fiscal2_id ni fiscal3_id ni fiscal4_id.');
                    }
                },
            ],
            'fiscal2_id' => [
                'numeric',
                'nullable',
                'max:999999',
                function ($attribute, $value, $fail) {
                    if ($value && ($value == request()->input('fiscal1_id') || $value == request()->input('fiscal3_id')) || $value == request()->input('fiscal4_id')) {
                        $fail('El campo ' . $attribute . ' no puede ser igual a fiscal1_id ni fiscal3_id ni fiscal4_id.');
                    }
                },
            ],
            'fiscal3_id' => [
                'numeric',
                'nullable',
                'max:999999',
                function ($attribute, $value, $fail) {
                    if ($value && ($value == request()->input('fiscal1_id') || $value == request()->input('fiscal2_id')) || $value == request()->input('fiscal4_id')) {
                        $fail('El campo ' . $attribute . ' no puede ser igual a fiscal1_id ni fiscal2_id ni fiscal4_id.');
                    }
                },
            ],
            'fiscal4_id' => [
                'numeric',
                'nullable',
                'max:999999',
                function ($attribute, $value, $fail) {
                    if ($value && ($value == request()->input('fiscal1_id') || $value == request()->input('fiscal2_id')) || $value == request()->input('fiscal3_id')) {
                        $fail('El campo ' . $attribute . ' no puede ser igual a fiscal1_id ni fiscal2_id ni fiscal3_id.');
                    }
                },
            ],
        ];


        $validator =  Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // PARA ASIGNAR Y REASIGNAR FISCALES DE OBRAS
        $fiscal1_id = $request->input('fiscal1_id');
        if (empty($fiscal1_id) || $fiscal1_id === '0') {
            // Acción si fiscal1_id está vacío o es igual a 0
            $contract->fiscal1_id = null;
            $contract->fiscal1_date = now(); // Por ejemplo, asignar un valor nulo
        } else {
            // Acción si fiscal1_id tiene un valor válido
            $contract->fiscal1_id = $request->input('fiscal1_id');
            $contract->fiscal1_date = now();
        }

        $fiscal2_id = $request->input('fiscal2_id');
        if (empty($fiscal2_id) || $fiscal2_id === '0') {
            // Acción si fiscal2_id está vacío o es igual a 0
            $contract->fiscal2_id = null;
            $contract->fiscal2_date = now(); // Por ejemplo, asignar un valor nulo
        } else {
            // Acción si fiscal2_id tiene un valor válido
            $contract->fiscal2_id = $request->input('fiscal2_id');
            $contract->fiscal2_date = now();
        }

        $fiscal3_id = $request->input('fiscal3_id');
        if (empty($fiscal3_id) || $fiscal3_id === '0') {
            // Acción si fiscal3_id está vacío o es igual a 0
            $contract->fiscal3_id = null;
            $contract->fiscal3_date = now(); // Por ejemplo, asignar un valor nulo
        } else {
            // Acción si fiscal3_id tiene un valor válido
            $contract->fiscal3_id = $request->input('fiscal3_id');
            $contract->fiscal3_date = now();
        }

        $fiscal4_id = $request->input('fiscal4_id');
        if (empty($fiscal4_id) || $fiscal4_id === '0') {
            // Acción si fiscal4_id está vacío o es igual a 0
            $contract->fiscal4_id = null;
            $contract->fiscal4_date = now(); // Por ejemplo, asignar un valor nulo
        } else {
            // Acción si fiscal4_id tiene un valor válido
            $contract->fiscal4_id = $request->input('fiscal4_id');
            $contract->fiscal4_date = now();
        }

        $contract->creator_user_id = $request->user()->id;  // usuario logueado
        $contract->save();
        return redirect()->route('contracts.show', $contract->id)->with('success', 'Fiscal agregado correctamente');
    }


    /**
     * Funcionalidad de modificacion del contrato cuando se agrega Fiscales de Obras
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_contra(Request $request, $contract_id)
    {
        $contract = Contract::findOrFail($contract_id);

        // $rules = ['contra_user_id' => 'numeric','required', 'max:999999'];


        // $validator =  Validator::make($request->input(), $rules);
        // if ($validator->fails()) {
        //     return back()->withErrors($validator)->withInput();
        // }

        // PARA ASIGNAR Y REASIGNAR FISCALES DE OBRAS
        // $contra_user = $request->input('contra_user');
        // if (empty($contra_user) || $contra_user === '0') {
        //     // Acción si contra_user está vacío o es igual a 0
        //     $contract->contra_user = null;            
        // } else {
        //     // Acción si contra_user tiene un valor válido
        //     $contract->contra_user = $request->input('contra_user');
        // }        

        $contract->contra_user_id = $request->input('contra_user_id');
        $contract->creator_user_id = $request->user()->id;  // usuario logueado
        $contract->save();
        return redirect()->route('contracts.show', $contract->id)->with('success', 'Usuario agregado correctamente');
    }



    public function excel(){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="prueba.xlsx"');
        $writer->save('php://output');
    }

    /**
     * Derivar un pedido
     *
     * @return \Illuminate\Http\Response
     */
    public function derive(Request $request, $contract_id){
        // Chequeamos que el usuario disponga de permisos de derivacion de pedido
        if(!$request->user()->hasPermission(['orders.orders.derive'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.'], 200);
        }

        //SE DERIVA A DGAF PARA REVISIÓN DE PEDIDO
        $contract = Order::find($contract_id);
        // Estado 4 = DERIVADO A DGAF PARA REVISIÓN DE PEDIDO
        $contract->actual_state = 4;
        $contract->save();

        // Registramos el movimiento de estado en la tabla orders_order_state
        $contract_order_state = new OrderOrderState;
        $contract_order_state->order_id = $contract->id;
        $contract_order_state->order_state_id = 4;
        $contract_order_state->creator_user_id = $request->user()->id;
        $contract_order_state->save();

        return response()->json(['status' => 'success', 'message' => 'Se ha derivado exitosamente el pedido. '], 200);
    }


    /**
     * Anular pedido
     *
     * @return \Illuminate\Http\Response
     */
    public function anuleOrder(Request $request, $contract_id){
        // Chequeamos que el usuario disponga de permisos de derivacion de pedido
        if(!$request->user()->hasPermission(['orders.orders.anule'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.'], 200);
        }

        $contract = Order::find($contract_id);
        // Verifica estado y si en Estado 2 = PROCESADO PEDIDO se puede cambiar
        $contract->actual_state = 0;
        $contract->save();

        // Registramos el movimiento de estado en la tabla orders_order_state
        $contract_order_state = new OrderOrderState;
        $contract_order_state->order_id = $contract->id;
        $contract_order_state->order_state_id = 0;
        $contract_order_state->creator_user_id = $request->user()->id;
        $contract_order_state->save();

        return response()->json(['status' => 'success', 'message' => 'Se ha anulado el pedido exitosamente. '], 200);
    }


    /**
     * Anular Derivar un pedido
     *
     * @return \Illuminate\Http\Response
     */
    public function anuleDerive(Request $request, $contract_id){
        // Chequeamos que el usuario disponga de permisos de derivacion de pedido
        if(!$request->user()->hasPermission(['orders.orders.derive'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.'], 200);
        }

        $contract = Order::find($contract_id);
        // Verifica estado y si en Estado 2 = PROCESADO PEDIDO se puede cambiar
        $contract->actual_state = 1;
        $contract->save();

        // Registramos el movimiento de estado en la tabla orders_order_state
        $contract_order_state = new OrderOrderState;
        $contract_order_state->order_id = $contract->id;
        $contract_order_state->order_state_id = 1;
        $contract_order_state->creator_user_id = $request->user()->id;
        $contract_order_state->save();

        return response()->json(['status' => 'success', 'message' => 'Se ha anulado derivación exitosamente. '], 200);
    }


    public function destroy(Request $request, $id)
    {
        // Chequeamos que el usuario actual disponga de permisos de eliminacion
        // if(!$request->user()->hasPermission(['admin.contracts.delete']) || !$request->user()->hasPermission(['contracts.contracts.delete'])){
        //     return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        // }

        $contract = Contract::find($id);

        // Chequeamos si existen cargos vinculados al usuarios
        // if($contract->users->count() > 0){
        //     return response()->json(['status' => 'error', 'message' => 'No se ha podido eliminar el cargo debido a que se encuentra vinculado a usuario ', 'code' => 200], 200);
        // }

        // Eliminamos en caso de no existir usuarios vinculados al cargo
        $contract->delete();
        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el Llamado' . $contract->description, 'code' => 200], 200);
    }


    //Para mostrar un archivo EXCEL guardado en el Proyecto
    public function ArchivoPedido(){
        header("Content-type: application/xlsx");
        header("Content-Disposition: inline; filename=0-Modelo Pedido.xlsx");
        readfile("files/0-Modelo Pedido.xlsx");
    }

    //Para mostrar un archivo EXCEL guardado en el Proyecto tipo Contrato Abierto
    public function ArchivoItem(){
        header("Content-type: application/xlsx");
        header("Content-Disposition: inline; filename=1-Modelo Item ABIERTO.xlsx");
        readfile("files/1-Modelo Item ABIERTO.xlsx");
    }

    //Para mostrar un archivo EXCEL guardado en el Proyecto tipo Contrato Cerrado
    public function ArchivoItem2(){
        header("Content-type: application/xlsx");
        header("Content-Disposition: inline; filename=2-Modelo Item CERRADO.xlsx");
        readfile("files/2-Modelo Item CERRADO.xlsx");
    }

    //Para mostrar un archivo EXCEL guardado en el Proyecto tipo Contrato Cerrado
    public function ArchivoItem3(){
        header("Content-type: application/xlsx");
        header("Content-Disposition: inline; filename=3-Modelo Item 3-ABIERTO MMin-MMax.xlsx");
        readfile("files/3-Modelo Item ABIERTO MMin-MMax.xlsx");
    }

    //Para mostrar un archivo EXCEL guardado en el Proyecto items de Adjudicaciones
    public function ArchivoItemAw(){
        header("Content-type: application/xlsx");
        header("Content-Disposition: inline; filename=64-Importar_Items.xlsx");
        readfile("files/64-Importar_Items.xlsx");
    }

    /**
     * Obtener notificaciones de alertas de vencimientos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function getNotifications(Request $request)
{
    $user = $request->user();
    $isAdmin = $user->hasPermission(['admin.contracts.show']);

    // Construyo la parte común de la consulta en un Query Builder sin ejecutar ->get() aún
    $baseSelect = [
        'contrato','iddncp','number_year','year_adj','contratista',
        'estado','modalidad','tipo_contrato','amount','item_from',
        'item_to','comments'
    ];

    $table = 'vista_contracts_full3';

    $query = DB::table($table)->select($baseSelect)
        ->whereIn('state_id', [1]) // 1 - En curso
        ->where(function ($q) {
            $q->where(function ($q2) {
                $q2->where('dias_advance', '<=', 0)
                   ->whereNull('dias_advance_endo');
            })->orWhere(function ($q3) {
                $q3->where('dias_advance', '<=', 0)
                   ->where('dias_advance_endo', '<=', 0);
            });
        });

    // Si NO es admin, filtro por dependencia
    if (! $isAdmin) {
        $query->addSelect(['dias_advance', 'dependency_id'])
              ->where('dependency_id', $user->dependency_id);
    }

    // Antes de ejecutar, loggeo la sql y bindings (para revisar manualmente)
    Log::info('DEBUG - Query SQL', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

    // Ejecuto la consulta final
    $orders = $query->get();

    Log::info('DEBUG - Count orders', ['count' => $orders->count()]);

    // Si no hay filas, hacemos pruebas de relajación para identificar el filtro que está matando resultados
    if ($orders->count() === 0) {
        // 1) Probar sin filtro state_id
        $q1 = DB::table($table)
            ->select(['contrato','iddncp','dias_advance','dias_advance_endo','state_id','dependency_id'])
            ->where(function ($q) {
                $q->where('dias_advance', '<=', 0)
                  ->whereNull('dias_advance_endo');
            })->orWhere(function ($q) {
                $q->where('dias_advance', '<=', 0)
                  ->where('dias_advance_endo', '<=', 0);
            });

        Log::info('DEBUG - q1 SQL', ['sql' => $q1->toSql(), 'bindings' => $q1->getBindings()]);
        $res1 = $q1->limit(10)->get();
        Log::info('DEBUG - q1 sample count', ['count' => $res1->count()]);
        if ($res1->count() > 0) {
            Log::info('DEBUG - q1 sample rows', $res1->toArray());
        }

        // 2) Probar solo estado y dependencia (sin condiciones de dias)
        $q2 = DB::table($table)
            ->select(['contrato','iddncp','dias_advance','dias_advance_endo','state_id','dependency_id'])
            ->where('state_id', 1);

        if (! $isAdmin) {
            $q2->where('dependency_id', $user->dependency_id);
        }

        Log::info('DEBUG - q2 SQL', ['sql' => $q2->toSql(), 'bindings' => $q2->getBindings()]);
        $res2 = $q2->limit(10)->get();
        Log::info('DEBUG - q2 sample count', ['count' => $res2->count()]);
        if ($res2->count() > 0) {
            Log::info('DEBUG - q2 sample rows', $res2->toArray());
        }

        // 3) Probar filas con dias_advance NULL o texto extraño
        $q3 = DB::table($table)
            ->select(['contrato','iddncp','dias_advance','dias_advance_endo'])
            ->whereRaw("CAST(dias_advance AS text) IS NOT NULL")
            ->limit(10);

        Log::info('DEBUG - q3 SQL', ['sql' => $q3->toSql(), 'bindings' => $q3->getBindings()]);
        $res3 = $q3->get();
        Log::info('DEBUG - q3 sample count', ['count' => $res3->count()]);
        if ($res3->count() > 0) {
            Log::info('DEBUG - q3 sample rows', $res3->toArray());
        }
    }

    // Construyo el arreglo final (aunque esté vacío)
    $alerta_advance = [];
    foreach ($orders as $order) {
        $pac_id = number_format($order->iddncp ?? 0, 0, ",", ".");
        $alerta_advance[] = ['pac_id' => $pac_id];
    }
    
    // Log::info('DEBUG count orders = ' . $orders->count());
    // Log::info('DEBUG orders raw', ['orders' => $orders->toArray()]);

    return response()->json(['status' => 'success', 'alerta_advance' => $alerta_advance, 'count' => count($alerta_advance)], 200);
}


}
