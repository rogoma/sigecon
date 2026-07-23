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
use App\Models\ItemContract;
use App\Models\ItemCertification;
use App\Models\ItemCertificationDetail;
use Mpdf\Mpdf;
use Barryvdh\DomPDF\Facade as Pdf;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;


class ItemsContractsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $index_permissions = ['admin.items.index','contracts.items.index','contracts.items.show'];
        $create_permissions = ['admin.items.create','contracts.items.create'];
        $update_permissions = ['admin.items.update','contracts.items.update'];

        $this->middleware('checkPermission:'.implode(',',$index_permissions))->only(['index']); // Permiso para index
        $this->middleware('checkPermission:'.implode(',',$create_permissions))->only(['create', 'store']);   // Permiso para create
        // indexCerti/storeCerti/certificationPdf no llevan checkPermission porque el Contratista (role_id 4) las usa
        // y no participa del sistema de permisos; se validan inline permitiendo permiso, rol Contratista o misma dependencia.
        $this->middleware('checkPermission:'.implode(',',$update_permissions))->only(['edit', 'update']);   // Permiso para update
    }


    public function index(Request $request, $contract_id, $component_id)
    {
        $contract = Contract::findOrFail($contract_id);

        $items = ItemContract::where('contract_id', $contract_id)
                ->where('component_id', $component_id)
                ->orderBy('id')
                ->get();

        // Chequeamos permisos del usuario
        if(!$request->user()->hasPermission(['admin.items.index', 'contracts.items.index','contracts.items.show'])){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        return view('contract.itemscontracts.index', compact('items','contract'));
        // return view('order.items.index', compact('items','contract'));        
    }

    public function indexRubros(Request $request, $order_id, $contract_id, $component_id)
    {
        $contract = Contract::findOrFail($contract_id);

        $order = Order::findOrFail($order_id);

        // $order = Order::with('locality')->find($order_id);
                
       
        $items = ItemContract::where('contract_id', $contract_id)
                ->where('component_id', $component_id)
                ->orderBy('id')
                ->get();

        // Chequeamos permisos del usuario
        if(!$request->user()->hasPermission(['admin.items.index', 'contracts.items.index','contracts.items.show'])){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }
        
        return view('contract.itemscontracts.index2_orig2', compact('items','contract', 'order'));        
    }

    // PARA MOSTRAR VISTA PARA MEDIR ITEMS DE LA ORDEN DE EJECUCIÓN
    public function indexCerti(Request $request, $order_id, $contract_id, $component_id)
    {
        $contract = Contract::findOrFail($contract_id);

        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario: acceso vía permiso, vía rol Contratista (role_id 4, no maneja permisos) o por dependencia dueña del contrato
        if (!$request->user()->hasPermission(['admin.items.index', 'contracts.items.index', 'contracts.items.show'])
            && $request->user()->role_id != 4
            && $contract->dependency_id != $request->user()->dependency_id) {
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        $items0 = ItemContract::where('contract_id', $contract_id)
                ->where('component_id', $component_id)
                ->orderBy('id')
                ->get();

        $items = ItemOrder::where('order_id', $order_id)
                ->orderBy('id')
                ->get();

        // Cant. a Ejecutar por rubro, asignada a esta orden (0 si el rubro del componente no fue incluido en la orden)
        $cantidadesOrden = $items->pluck('quantity', 'rubro_id');

        // Cantidad ya certificada (Anterior) por rubro, sumando todas las actas de medición previas de esta orden
        $anteriores = ItemCertificationDetail::whereHas('itemCertification', function ($q) use ($order_id) {
                $q->where('order_id', $order_id);
            })
            ->selectRaw('rubro_id, SUM(quantity) as total')
            ->groupBy('rubro_id')
            ->pluck('total', 'rubro_id');

        // Cantidad Contratada por rubro (Cant. Contract.), para mostrar junto a la Cant. a Ejecutar de la orden
        $cantidadesContrato = $items0->pluck('quantity', 'rubro_id');

        // Próximo N° de Planilla de Certificación para esta orden (se sugiere como valor por defecto; el usuario puede editarlo)
        $nextCertificationNumber = (int) ItemCertification::where('order_id', $order_id)->max('number') + 1;

        // Actas de Medición ya generadas para esta orden, para listar los enlaces a sus PDFs
        $certifications = ItemCertification::where('order_id', $order_id)
            ->orderBy('number', 'asc')
            ->get();

        return view('contract.itemsorders.index_itemsorders', compact('items0', 'items', 'contract', 'order', 'anteriores', 'cantidadesContrato', 'cantidadesOrden', 'nextCertificationNumber', 'certifications'));
    }

    /**
     * PARA MOSTRAR VISTA DE MEDICIÓN COMBINANDO TODAS LAS ÓRDENES "EN CURSO" QUE COMPARTEN
     * LA MISMA LOCALIDAD Y EL MISMO SUB-COMPONENTE (pueden existir varias por distintos periodos).
     * La "Cant. a Ejecutar" y el "Anterior" de cada rubro se muestran sumando todas esas órdenes.
     */
    public function indexCertiGroup(Request $request, $contract_id, $locality_id, $component_id)
    {
        $contract = Contract::findOrFail($contract_id);

        // Chequeamos permisos del usuario: acceso vía permiso, vía rol Contratista (role_id 4, no maneja permisos) o por dependencia dueña del contrato
        if (!$request->user()->hasPermission(['admin.items.index', 'contracts.items.index', 'contracts.items.show'])
            && $request->user()->role_id != 4
            && $contract->dependency_id != $request->user()->dependency_id) {
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        // Órdenes "En Curso" que comparten Contrato + Localidad + Sub-Componente
        $orders = Order::with('locality', 'component', 'creatorUser')
            ->where('contract_id', $contract_id)
            ->where('locality_id', $locality_id)
            ->where('component_id', $component_id)
            ->where('order_state_id', 1)
            ->orderBy('id')
            ->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'No hay Órdenes de Ejecución en curso para esta Localidad y Sub-Componente.');
        }

        $orderIds = $orders->pluck('id');

        $items0 = ItemContract::where('contract_id', $contract_id)
                ->where('component_id', $component_id)
                ->orderBy('id')
                ->get();

        // Cant. a Ejecutar agregada por rubro: suma de lo asignado en items_orders de TODAS las órdenes del grupo
        $cantidadesGrupo = ItemOrder::whereIn('order_id', $orderIds)
                ->selectRaw('rubro_id, SUM(quantity) as total')
                ->groupBy('rubro_id')
                ->pluck('total', 'rubro_id');

        // Cantidad ya certificada (Anterior) por rubro, sumando todas las actas previas de CUALQUIERA de las órdenes del grupo
        $anteriores = ItemCertificationDetail::whereHas('itemCertification', function ($q) use ($orderIds) {
                $q->whereIn('order_id', $orderIds);
            })
            ->selectRaw('rubro_id, SUM(quantity) as total')
            ->groupBy('rubro_id')
            ->pluck('total', 'rubro_id');

        // Cantidad Contratada por rubro (Cant. Contract.)
        $cantidadesContrato = $items0->pluck('quantity', 'rubro_id');

        // Próximo N° de Planilla sugerido: siguiente al mayor usado en cualquiera de las órdenes del grupo
        $nextCertificationNumber = (int) ItemCertification::whereIn('order_id', $orderIds)->max('number') + 1;

        // Actas de Medición ya generadas para cualquiera de las órdenes de este grupo
        $certifications = ItemCertification::whereIn('order_id', $orderIds)
            ->with('order')
            ->orderBy('number', 'asc')
            ->get();

        $locality = $orders->first()->locality;
        $component = $orders->first()->component;

        return view('contract.itemsorders.index_itemsorders_group', compact(
            'items0', 'cantidadesGrupo', 'contract', 'orders', 'locality', 'component',
            'anteriores', 'cantidadesContrato', 'nextCertificationNumber', 'certifications'
        ));
    }

    /**
     * Guarda una nueva Acta de Medición (planilla de certificación) con sus rubros medidos.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeCerti(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Chequeamos permisos del usuario: acceso vía permiso, vía rol Contratista (role_id 4, no maneja permisos) o por dependencia dueña del contrato
        if (!$request->user()->hasPermission(['admin.items.create', 'contracts.items.create'])
            && $request->user()->role_id != 4
            && $order->contract->dependency_id != $request->user()->dependency_id) {
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $rules = [
            'number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('item_certifications', 'number')->where(function ($q) use ($order_id) {
                    return $q->where('order_id', $order_id);
                }),
            ],
            'month_date' => ['required', 'string', 'regex:/^\d{2}\/\d{4}$/'],
            'sign_date' => 'required|date_format:d/m/Y',
            'contratista_representative' => 'required|string|max:150',
            'items' => 'required|array|min:1',
            'items.*.rubro_id' => 'required|integer',
            'items.*.quantity' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
        ];
        $messages = [
            'number.unique' => 'Ya existe una Acta de Medición con ese N° de Planilla para esta orden.',
            'month_date.regex' => 'El Mes/Año debe tener el formato mm/yyyy.',
            'contratista_representative.required' => 'Debe ingresar el nombre del representante de la Contratista.',
            'items.*.quantity.regex' => 'La cantidad medida admite como máximo 2 decimales.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first(), 'code' => 200], 200);
        }

        if ($this->periodoExcedeFechaMedicion($request->input('month_date'), $request->input('sign_date'))) {
            return response()->json(['status' => 'error', 'message' => 'El Mes/Año del periodo no puede ser posterior al mes de la Fecha de la Medición.', 'code' => 200], 200);
        }

        // Solo persistimos rubros con cantidad medida mayor a 0
        $items = collect($request->input('items'))->filter(function ($item) {
            return (float) $item['quantity'] > 0;
        });

        if ($items->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Debe ingresar al menos una cantidad medida mayor a 0.', 'code' => 200], 200);
        }

        $number = (int) $request->input('number');

        $certification = ItemCertification::create([
            'order_id' => $order_id,
            'number' => $number,
            'period' => $request->input('month_date'),
            'sign_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('sign_date'))->format('Y-m-d'),
            'creator_user_id' => $request->user()->id,
            'state_id' => ItemCertification::STATE_EMITIDO,
            'contratista_representative' => $request->input('contratista_representative'),
        ]);

        foreach ($items as $item) {
            ItemCertificationDetail::create([
                'item_certification_id' => $certification->id,
                'rubro_id' => $item['rubro_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Acta de Medición N° ' . $number . ' generada correctamente.',
            'redirect_url' => route('item_certifications.pdf', $certification->id),
        ]);
    }

    /**
     * Guarda la medición combinada de un grupo de Órdenes "En Curso" (misma Localidad + Sub-Componente).
     * La cantidad medida de cada rubro se reparte contra el saldo de cada orden, empezando por la más
     * antigua; si sobra medición luego de agotar el saldo de todas las órdenes del grupo, el excedente
     * se registra contra la orden más reciente (misma lógica de "excede saldo" que la carga por orden individual).
     * Se genera una Acta de Medición por cada orden que resulte con rubros medidos.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeCertiGroup(Request $request, $contract_id, $locality_id, $component_id)
    {
        $contract = Contract::findOrFail($contract_id);

        // Chequeamos permisos del usuario: acceso vía permiso, vía rol Contratista (role_id 4, no maneja permisos) o por dependencia dueña del contrato
        if (!$request->user()->hasPermission(['admin.items.create', 'contracts.items.create'])
            && $request->user()->role_id != 4
            && $contract->dependency_id != $request->user()->dependency_id) {
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $orders = Order::where('contract_id', $contract_id)
            ->where('locality_id', $locality_id)
            ->where('component_id', $component_id)
            ->where('order_state_id', 1)
            ->orderBy('id') // más antigua primero, para el reparto de saldo
            ->get();

        if ($orders->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'No hay Órdenes de Ejecución en curso para esta Localidad y Sub-Componente.', 'code' => 200], 200);
        }

        $rules = [
            'number' => 'required|integer|min:1',
            'month_date' => ['required', 'string', 'regex:/^\d{2}\/\d{4}$/'],
            'sign_date' => 'required|date_format:d/m/Y',
            'contratista_representative' => 'required|string|max:150',
            'items' => 'required|array|min:1',
            'items.*.rubro_id' => 'required|integer',
            'items.*.quantity' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
        ];
        $messages = [
            'month_date.regex' => 'El Mes/Año debe tener el formato mm/yyyy.',
            'contratista_representative.required' => 'Debe ingresar el nombre del representante de la Contratista.',
            'items.*.quantity.regex' => 'La cantidad medida admite como máximo 2 decimales.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first(), 'code' => 200], 200);
        }

        if ($this->periodoExcedeFechaMedicion($request->input('month_date'), $request->input('sign_date'))) {
            return response()->json(['status' => 'error', 'message' => 'El Mes/Año del periodo no puede ser posterior al mes de la Fecha de la Medición.', 'code' => 200], 200);
        }

        $number = (int) $request->input('number');
        $orderIds = $orders->pluck('id');

        // El N° de Planilla se genera en todas las órdenes afectadas, así que debe estar libre en todas ellas
        $numeroEnUso = ItemCertification::whereIn('order_id', $orderIds)->where('number', $number)->exists();
        if ($numeroEnUso) {
            return response()->json(['status' => 'error', 'message' => 'Ya existe una Acta de Medición con ese N° de Planilla en alguna de las órdenes de este grupo.', 'code' => 200], 200);
        }

        // Solo persistimos rubros con cantidad medida mayor a 0
        $itemsInput = collect($request->input('items'))->filter(function ($item) {
            return (float) $item['quantity'] > 0;
        });

        if ($itemsInput->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Debe ingresar al menos una cantidad medida mayor a 0.', 'code' => 200], 200);
        }

        // Cant. a Ejecutar por orden y rubro (lo asignado en items_orders de cada orden)
        $cantidadesPorOrden = ItemOrder::whereIn('order_id', $orderIds)
            ->get()
            ->groupBy('order_id')
            ->map(function ($rows) {
                return $rows->pluck('quantity', 'rubro_id');
            });

        // Lo ya certificado por orden y rubro en actas previas
        $certificadoPorOrden = ItemCertificationDetail::join('item_certifications', 'item_certifications.id', '=', 'item_certification_details.item_certification_id')
            ->whereIn('item_certifications.order_id', $orderIds)
            ->selectRaw('item_certifications.order_id as order_id, item_certification_details.rubro_id as rubro_id, SUM(item_certification_details.quantity) as total')
            ->groupBy('item_certifications.order_id', 'item_certification_details.rubro_id')
            ->get()
            ->groupBy('order_id')
            ->map(function ($rows) {
                return $rows->pluck('total', 'rubro_id');
            });

        // Repartimos cada rubro medido contra el saldo de cada orden, de la más antigua a la más reciente
        $detallesPorOrden = []; // [order_id => [rubro_id => quantity]]
        foreach ($itemsInput as $itemInput) {
            $rubroId = $itemInput['rubro_id'];
            $restante = (float) $itemInput['quantity'];

            foreach ($orders as $order) {
                if ($restante <= 0) {
                    break;
                }
                $asignadoOrden = (float) ($cantidadesPorOrden[$order->id][$rubroId] ?? 0);
                $certificadoOrden = (float) ($certificadoPorOrden[$order->id][$rubroId] ?? 0);
                $saldoOrden = max(0, $asignadoOrden - $certificadoOrden);
                if ($saldoOrden <= 0) {
                    continue;
                }
                // Redondeamos a 2 decimales en cada paso para evitar arrastre de error de punto flotante
                $aplicar = round(min($restante, $saldoOrden), 2);
                $detallesPorOrden[$order->id][$rubroId] = round(($detallesPorOrden[$order->id][$rubroId] ?? 0) + $aplicar, 2);
                $restante = round($restante - $aplicar, 2);
            }

            // Excedente sin saldo en ninguna orden del grupo: se registra contra la orden más reciente
            // (permite exceder saldo, igual que la carga por orden individual)
            if ($restante > 0) {
                $ordenMasReciente = $orders->last();
                $detallesPorOrden[$ordenMasReciente->id][$rubroId] = round(($detallesPorOrden[$ordenMasReciente->id][$rubroId] ?? 0) + $restante, 2);
            }
        }

        $signDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('sign_date'))->format('Y-m-d');
        $creatorUserId = $request->user()->id;
        $period = $request->input('month_date');
        $contratistaRepresentative = $request->input('contratista_representative');

        // Identifica esta "tanda" de Actas, generadas juntas a partir de una misma medición combinada.
        // Con ella el PDF de cada Acta puede reconocer que corresponde a más de una Orden de Ejecución
        // y mostrar los valores Anterior/Actual/Acumulado agregados de todo el grupo.
        $batchUuid = (string) Str::uuid();

        $certifications = DB::transaction(function () use ($detallesPorOrden, $batchUuid, $number, $period, $signDate, $creatorUserId, $contratistaRepresentative) {
            $created = [];
            foreach ($detallesPorOrden as $orderId => $rubros) {
                $certification = ItemCertification::create([
                    'order_id' => $orderId,
                    'batch_uuid' => $batchUuid,
                    'number' => $number,
                    'period' => $period,
                    'sign_date' => $signDate,
                    'creator_user_id' => $creatorUserId,
                    'state_id' => ItemCertification::STATE_EMITIDO,
                    'contratista_representative' => $contratistaRepresentative,
                ]);

                foreach ($rubros as $rubroId => $quantity) {
                    ItemCertificationDetail::create([
                        'item_certification_id' => $certification->id,
                        'rubro_id' => $rubroId,
                        'quantity' => $quantity,
                    ]);
                }

                $created[] = $certification;
            }

            return $created;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Acta de Medición N° ' . $number . ' generada correctamente en ' . count($certifications) . ' orden' . (count($certifications) === 1 ? '' : 'es') . '.',
            'redirect_urls' => collect($certifications)->map(function ($certification) {
                return route('item_certifications.pdf', $certification->id);
            })->values(),
        ]);
    }

    /**
     * Devuelve los datos de una Acta de Medición (encabezado + rubros medidos) para precargar el modal de edición.
     * Solo se permite editar actas en estado Emitido (1).
     *
     * @return \Illuminate\Http\Response
     */
    public function editCerti(Request $request, $certification_id)
    {
        $certification = ItemCertification::with('details')->findOrFail($certification_id);
        $order = $certification->order;

        if (!$request->user()->hasPermission(['admin.items.index', 'contracts.items.index', 'contracts.items.show'])
            && $request->user()->role_id != 4
            && $order->contract->dependency_id != $request->user()->dependency_id) {
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        if ($certification->state_id != ItemCertification::STATE_EMITIDO) {
            return response()->json(['status' => 'error', 'message' => 'Esta Acta de Medición no se encuentra en estado Emitido y no puede editarse.', 'code' => 200], 200);
        }

        $rubroIds = $certification->details->pluck('rubro_id');

        // Rubros medidos en esta acta, con su descripción/unidad y las cantidades de Contrato y de la Orden para referencia
        $itemsContract = ItemContract::whereIn('rubro_id', $rubroIds)
            ->where('contract_id', $order->contract_id)
            ->where('component_id', $order->component_id)
            ->with('rubro.orderPresentations')
            ->get()
            ->keyBy('rubro_id');

        $cantidadesOrden = ItemOrder::where('order_id', $order->id)->pluck('quantity', 'rubro_id');

        $rubros = $certification->details->map(function ($detail) use ($itemsContract, $cantidadesOrden) {
            $itemContract = $itemsContract->get($detail->rubro_id);
            return [
                'rubro_id' => $detail->rubro_id,
                'descripcion' => $itemContract ? $itemContract->rubro->code . '-' . $itemContract->rubro->description : $detail->rubro_id,
                'unidad' => $itemContract && $itemContract->rubro->orderPresentations ? $itemContract->rubro->orderPresentations->description : '',
                'cantidad_contrato' => $itemContract ? $itemContract->quantity : 0,
                'cantidad_ejecutar' => $cantidadesOrden[$detail->rubro_id] ?? 0,
                'quantity' => $detail->quantity,
            ];
        })->values();

        return response()->json([
            'status' => 'success',
            'certification' => [
                'id' => $certification->id,
                'number' => $certification->number,
                'period' => $certification->period,
                'sign_date' => \Carbon\Carbon::parse($certification->sign_date)->format('d/m/Y'),
                'contratista_representative' => $certification->contratista_representative,
            ],
            'rubros' => $rubros,
        ]);
    }

    /**
     * Actualiza una Acta de Medición existente. Solo permitido mientras esté en estado Emitido (1).
     *
     * @return \Illuminate\Http\Response
     */
    public function updateCerti(Request $request, $certification_id)
    {
        $certification = ItemCertification::findOrFail($certification_id);
        $order = $certification->order;

        if (!$request->user()->hasPermission(['admin.items.update', 'contracts.items.update'])
            && $request->user()->role_id != 4
            && $order->contract->dependency_id != $request->user()->dependency_id) {
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        if ($certification->state_id != ItemCertification::STATE_EMITIDO) {
            return response()->json(['status' => 'error', 'message' => 'Esta Acta de Medición no se encuentra en estado Emitido y no puede editarse.', 'code' => 200], 200);
        }

        $rules = [
            'number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('item_certifications', 'number')->where(function ($q) use ($order) {
                    return $q->where('order_id', $order->id);
                })->ignore($certification->id),
            ],
            'month_date' => ['required', 'string', 'regex:/^\d{2}\/\d{4}$/'],
            'sign_date' => 'required|date_format:d/m/Y',
            'contratista_representative' => 'required|string|max:150',
            'items' => 'required|array|min:1',
            'items.*.rubro_id' => 'required|integer',
            'items.*.quantity' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
        ];
        $messages = [
            'number.unique' => 'Ya existe otra Acta de Medición con ese N° de Planilla para esta orden.',
            'month_date.regex' => 'El Mes/Año debe tener el formato mm/yyyy.',
            'contratista_representative.required' => 'Debe ingresar el nombre del representante de la Contratista.',
            'items.*.quantity.regex' => 'La cantidad medida admite como máximo 2 decimales.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first(), 'code' => 200], 200);
        }

        if ($this->periodoExcedeFechaMedicion($request->input('month_date'), $request->input('sign_date'))) {
            return response()->json(['status' => 'error', 'message' => 'El Mes/Año del periodo no puede ser posterior al mes de la Fecha de la Medición.', 'code' => 200], 200);
        }

        $items = collect($request->input('items'))->filter(function ($item) {
            return (float) $item['quantity'] > 0;
        });

        if ($items->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Debe ingresar al menos una cantidad medida mayor a 0.', 'code' => 200], 200);
        }

        $certification->update([
            'number' => (int) $request->input('number'),
            'period' => $request->input('month_date'),
            'sign_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('sign_date'))->format('Y-m-d'),
            'contratista_representative' => $request->input('contratista_representative'),
        ]);

        // Reemplazamos el detalle de rubros medidos por el nuevo detalle enviado
        $certification->details()->delete();
        foreach ($items as $item) {
            ItemCertificationDetail::create([
                'item_certification_id' => $certification->id,
                'rubro_id' => $item['rubro_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Acta de Medición N° ' . $certification->number . ' actualizada correctamente.',
            'redirect_url' => route('item_certifications.pdf', $certification->id),
        ]);
    }

    /**
     * Compara el periodo (Mes/Año, formato mm/yyyy) contra el mes de la Fecha de la Medición (formato d/m/Y).
     * El periodo puede ser igual o anterior al mes de la fecha de medición, pero nunca posterior.
     */
    private function periodoExcedeFechaMedicion(string $monthDate, string $signDateDmy): bool
    {
        if (!preg_match('/^(\d{2})\/(\d{4})$/', $monthDate, $matches)) {
            return false;
        }

        try {
            $signDate = \Carbon\Carbon::createFromFormat('d/m/Y', $signDateDmy);
        } catch (\Exception $e) {
            return false;
        }

        $periodoValor = ((int) $matches[2]) * 12 + (int) $matches[1];
        $signDateValor = $signDate->year * 12 + $signDate->month;

        return $periodoValor > $signDateValor;
    }

    /**
     * Genera el PDF del Acta de Medición (planilla de certificación).
     *
     * @return \Illuminate\Http\Response
     */
    public function certificationPdf(Request $request, $certification_id)
    {
        $certification = ItemCertification::with(['order.contract.provider', 'order.locality.district.department', 'order.creatorUser', 'order.contract.fiscal1', 'order.contract.contratista', 'order.component.componentType'])
            ->findOrFail($certification_id);

        // Chequeamos permisos del usuario: acceso vía permiso, vía rol Contratista (role_id 4, no maneja permisos) o por dependencia dueña del contrato
        if (!$request->user()->hasPermission(['admin.items.index', 'contracts.items.index', 'contracts.items.show'])
            && $request->user()->role_id != 4
            && $certification->order->contract->dependency_id != $request->user()->dependency_id) {
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        $order = $certification->order;
        $contract = $order->contract;

        // Si esta Acta fue generada junto con otras (misma "tanda", identificada por batch_uuid) por hacer
        // referencia a más de una Orden de Ejecución de la misma Localidad/Sub-Componente, agrupamos los
        // valores Anterior/Actual/Acumulado de TODAS las órdenes de esa tanda, no sólo los de esta orden.
        $ordenesReferenciadas = collect([$order]);
        $ordenIdsGrupo = collect([$order->id]);

        if ($certification->batch_uuid) {
            $certificacionesTanda = ItemCertification::with('order', 'details')
                ->where('batch_uuid', $certification->batch_uuid)
                ->get();

            $ordenesReferenciadas = $certificacionesTanda->pluck('order')->unique('id')->sortBy('id')->values();
            $ordenIdsGrupo = $ordenesReferenciadas->pluck('id');
        }

        $esMultiOrden = $ordenIdsGrupo->count() > 1;

        if ($esMultiOrden) {
            // Cantidad Actual por rubro: lo ingresado en esta Acta, sumando el detalle repartido entre todas las órdenes de la tanda
            $actuales = $certificacionesTanda->flatMap->details
                ->groupBy('rubro_id')
                ->map(fn ($detalles) => $detalles->sum('quantity'));

            // Cantidad Anterior por rubro: todo lo certificado antes de esta tanda, en cualquiera de las órdenes del grupo
            $anteriores = ItemCertificationDetail::whereHas('itemCertification', function ($q) use ($ordenIdsGrupo, $certification) {
                    $q->whereIn('order_id', $ordenIdsGrupo)
                        ->where(function ($qq) use ($certification) {
                            $qq->whereNull('batch_uuid')->orWhere('batch_uuid', '!=', $certification->batch_uuid);
                        })
                        ->where('created_at', '<=', $certification->created_at);
                })
                ->selectRaw('rubro_id, SUM(quantity) as total')
                ->groupBy('rubro_id')
                ->pluck('total', 'rubro_id');

            // Cantidad solicitada por rubro agregada de TODAS las órdenes de la tanda
            $cantidadesOrden = ItemOrder::whereIn('order_id', $ordenIdsGrupo)
                ->selectRaw('rubro_id, SUM(quantity) as total')
                ->groupBy('rubro_id')
                ->pluck('total', 'rubro_id');
        } else {
            // Cantidad Anterior por rubro: suma de todas las actas previas a esta (mismo N° menor) de la misma orden
            $anteriores = ItemCertificationDetail::whereHas('itemCertification', function ($q) use ($order, $certification) {
                    $q->where('order_id', $order->id)->where('number', '<', $certification->number);
                })
                ->selectRaw('rubro_id, SUM(quantity) as total')
                ->groupBy('rubro_id')
                ->pluck('total', 'rubro_id');

            // Cantidad Actual (esta acta) por rubro
            $actuales = $certification->details->pluck('quantity', 'rubro_id');

            // Cantidad solicitada por rubro EN ESTA ORDEN DE EJECUCIÓN (no la cantidad total del contrato); 0 si el rubro no fue solicitado en la orden
            $cantidadesOrden = ItemOrder::where('order_id', $order->id)
                ->pluck('quantity', 'rubro_id');
        }

        // Rubros del componente/contrato, para listar la descripción y unidad (siempre todos, independientemente de la cantidad de órdenes)
        $rubros = ItemContract::where('contract_id', $contract->id)
            ->where('component_id', $order->component_id)
            ->orderBy('id')
            ->get();

        $pdf = App::make('dompdf.wrapper');
        $view = View::make('reports.item_certification', compact('certification', 'order', 'contract', 'anteriores', 'actuales', 'rubros', 'cantidadesOrden', 'esMultiOrden', 'ordenesReferenciadas'))->render();
        $pdf->loadHTML($view);
        return $pdf->stream('Acta_Certificacion_N' . $certification->number . '.pdf');
    }

    public function getItems(Request $request)
    {        
        $items = ItemContract::where('component_id', $request->component_id)
            ->where('contract_id', $request->contract_id)
            ->where('rubro_id', '!=', 9999)
            ->select('id', 'quantity', 'unit_price_mo', 'unit_price_mat')
            ->get();

        return response()->json(['data' => $items]);        
    }


    public function generateReport($contractId, $componentId)
{
    // Obtén los datos necesarios para el reporte
    $contract = Contract::findOrFail($contractId);

    // Filtrar los ítems por contract_id y component_id
    $items = ItemContract::where('contract_id', $contractId)
                 ->where('component_id', $componentId)
                 ->get();

    // Verificar si hay ítems para el componente
    if ($items->isEmpty()) {
        return redirect()->back()->with('error', 'No hay ítems para el componente seleccionado.');
    }

    // Calcula los totales
    $tot_price_mo = 0;
    $tot_price_mat = 0;

    foreach ($items as $item) {
        $tot_price_mo += ($item->unit_price_mo * $item->quantity);
        $tot_price_mat += ($item->unit_price_mat * $item->quantity);
    }

    // Pasa los datos a la vista del reporte
    $data = [
        'contract' => $contract,
        'items' => $items,
        'tot_price_mo' => $tot_price_mo,
        'tot_price_mat' => $tot_price_mat,
    ];

    // Generar el PDF con DomPDF
    $pdf = Pdf::loadView('reports.items_contracts', $data);

    // Mostrar el PDF en el navegador
    return $pdf->stream('reporte_contrato_' . $contractId . '_componente_' . $componentId . '.pdf');
}

    private function generateHtmlForPdf($contract, $items, $tot_price_mo, $tot_price_mat)
    {
        // Generar el HTML para el PDF
        $html = '
        <h1>Reporte de Contrato: ' . $contract->id . '</h1>
        <h2>Componente: ' . $items[0]->component->description . '</h2>
        
        <table border="1" cellpadding="4" cellspacing="0">
            <thead>
                <tr>
                    <th>N° item</th>
                    <th>Rubro (Cod. - Descripción)</th>
                    <th>Cant.</th>
                    <th>Unid.</th>
                    <th>Precio UNIT. Mano de Obra</th>
                    <th>Precio UNIT. Materiales</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($items as $item) {
            $html .= '
                <tr>
                    <td>' . $item->item_number . '</td>
                    <td>' . $item->rubro->code . ' - ' . $item->rubro->description . '</td>
                    <td>' . $item->quantity . '</td>
                    <td>' . $item->rubro->orderPresentations->description . '</td>
                    <td>' . number_format($item->unit_price_mo, '0', ',', '.') . '</td>
                    <td>' . number_format($item->unit_price_mat, '0', ',', '.') . '</td>
                </tr>';
        }

        $html .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4"><strong>TOTALES:</strong></td>
                    <td><strong>' . number_format($tot_price_mo, '0', ',', '.') . '</strong></td>
                    <td><strong>' . number_format($tot_price_mat, '0', ',', '.') . '</strong></td>
                </tr>
            </tfoot>
        </table>';

        return $html;
    }

    public function uploadExcel(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        $contracts = $order->contracts;

        // Chequeamos permisos del usuario en caso de no ser de la dependencia solicitante
        if(!$request->user()->hasPermission(['admin.items.create', 'orders.items.create'])){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        return view('order.items.uploadExcel', compact('order', 'contracts'));
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
            $order_amount_items = 0;
            $tot_tot_price_mo = 0;
            $tot_tot_price_mat = 0;

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
                $item['component_id'];
                // var_dump($compo);
                // var_dump($item['component_id']);exit();

                if ($item['component_id'] !== $compo) {
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
                $new_item->item_number = empty($item['item_number'])? NULL : $item['item_number'];
                $new_item->rubro_id = $item['rubro_id'];

                if ($item['rubro_id'] == 9999){
                    $new_item->subitem_id = $item['subItem_id'];
                }else{
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
                $new_item = new ItemOrder;
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
                $new_item = new ItemOrder;
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

        $item = ItemOrder::findOrFail($item_id);
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
        $item = ItemOrder::findOrFail($item_id);

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
        if(($request->user()->dependency_id == 59)){
            return redirect()->route('plannings.show', $order_id)->with('success', 'Ítem modificado correctamente'); // Caso usuario posee rol pedidos
        }else{
            return redirect()->route('orders.show', $order_id)->with('success', 'Ítem modificado correctamente'); // Caso usuario posee rol pedidos
        }
    }


    public function index2(Request $request, $contract_id, $component_id)
    {
        $contract = Contract::findOrFail($contract_id);

        $items = ItemContract::where('contract_id', $contract_id)
                ->where('component_id', $component_id)
                ->get();

        // Chequeamos permisos del usuario
        if(!$request->user()->hasPermission(['admin.items.index', 'contracts.items.index','contracts.items.show'])){
            return back()->with('error', 'No tiene los suficientes permisos para acceder a esta sección.');
        }

        return view('contract.itemscontracts.index', compact('items','contract'));
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $contract_id, $component_id)
        {
    
        if(!$request->user()->hasPermission(['admin.items.delete', 'contracts.items.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        $item = ItemContract::where('contract_id', $contract_id)
            ->where('component_id', $component_id)
            ->get();

        // Chequeamos si existen item_award_histories referenciando al item
        // if($item->orders->count() > 0){
        //     return response()->json(['status' => 'error', 'message' => 'No se ha podido eliminar el item debido a que se encuentra vinculado con históricos de precios referenciales, debe eliminarlos primero para continuar. ', 'code' => 200], 200);
        // }
    
        //PARA BORRAR LOS REGISTROS
        // ItemContract::where('contract_id', $contract_id)
        // ->where('component_id', $component_id)
        // ->delete();
        
        // AQUI RECORRER LOS ITEMS DEL PEDIDO Y CARGAR COMO NUEVO TOTAL_AMOUNT
        // $total_amountitems = 0;
        // for ($i = 0; $i < count($order->items); $i++){
        //     $total_amountitems += $order->items[$i]->total_amount;
        // }

        //CONTROLAMOS PARA AVISAR QUE MONTO DE SUMATORIA DE ITEMS SOBREPASA MONTO CDP (SI YA TIENE CDP)
        // $cdp_amount = $order->cdp_amount;
        // if ($cdp_amount > 0) {
        //     if ($total_amountitems > $cdp_amount) {
        //         $validator = Validator::make($request->input(), []); // Creamos un objeto validator
        //         $validator->errors()->add('order_measurement_unit', 'Con este cambio Monto total de Ítems: '.$total_amountitems.', es MAYOR a: '.$cdp_amount.' monto de CDP del Pedido, DEBE ACTUALIZAR CDP...');
        //         return back()->withErrors($validator)->withInput();
        //     }
        // }


        //CERAMOS VALOR DEL MONTO DE ORDER Y CARGAMOS VALOR NUEVO
        // $order->total_amount = 0;
        // $order->total_amount = $total_amountitems;
        // $order->save();

        return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el ítem ', 'code' => 200], 200);

    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function destroy_orig(Request $request, $contract_id, $component_id)
    {
        $contract = Contract::findOrFail($contract_id);

        $item = ItemContract::where('contract_id', $contract_id)
                ->where('component_id', $component_id)
                ->get();

        // Chequeamos permisos del usuario para borrar el registro
        if(!$request->user()->hasPermission(['admin.items.delete', 'contracts.items.delete'])){
            return response()->json(['status' => 'error', 'message' => 'No posee los suficientes permisos para realizar esta acción.', 'code' => 200], 200);
        }

        // Chequeamos si existen item_award_histories referenciando al item
        // if($item->itemAwardHistories->count() > 0){
        //     return response()->json(['status' => 'error', 'message' => 'No se ha podido eliminar el item debido a que se encuentra vinculado con históricos de precios referenciales, debe eliminarlos primero para continuar. ', 'code' => 200], 200);
        // }

        // Eliminamos en caso de no existir registros referenciando al item
        //  $item->delete();

        // AQUI RECORRER LOS ITEMS DEL PEDIDO Y CARGAR COMO NUEVO TOTAL_AMOUNT
        // $total_amountitems = 0;
        // for ($i = 0; $i < count($order->items); $i++){
        //     $total_amountitems += $order->items[$i]->total_amount;
        // }

        //CONTROLAMOS PARA AVISAR QUE MONTO DE SUMATORIA DE ITEMS SOBREPASA MONTO CDP (SI YA TIENE CDP)
        // $cdp_amount = $order->cdp_amount;
        // if ($cdp_amount > 0) {
        //     if ($total_amountitems > $cdp_amount) {
        //         $validator = Validator::make($request->input(), []); // Creamos un objeto validator
        //         $validator->errors()->add('order_measurement_unit', 'Con este cambio Monto total de Ítems: '.$total_amountitems.', es MAYOR a: '.$cdp_amount.' monto de CDP del Pedido, DEBE ACTUALIZAR CDP...');
        //         return back()->withErrors($validator)->withInput();
        //     }
        // }


        //CERAMOS VALOR DEL MONTO DE ORDER Y CARGAMOS VALOR NUEVO
        // $order->total_amount = 0;
        // $order->total_amount = $total_amountitems;
        // $order->save();

        // return response()->json(['status' => 'success', 'message' => 'Se ha eliminado el ítem ', 'code' => 200], 200);
    }
}
