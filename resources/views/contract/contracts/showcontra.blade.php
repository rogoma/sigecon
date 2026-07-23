@extends('layouts.app')

@push('styles')
    <style type="text/css">
        .contra-page {
            --acta-primary: #2f6f97;
            --acta-primary-dark: #204d69;
            --acta-accent: #2196f3;
            --acta-success: #2eb85c;
            --acta-warning: #f9b115;
            --acta-danger: #e55353;
        }

        /* ---------- Hero / encabezado ---------- */
        .contra-page .acta-hero {
            background: linear-gradient(135deg, var(--acta-primary) 0%, var(--acta-primary-dark) 100%);
            border-radius: 16px;
            border: 0;
            color: #fff;
            box-shadow: 0 8px 24px rgba(32, 77, 105, .25);
            overflow: hidden;
        }

        .contra-page .acta-hero .breadcrumb {
            background: rgba(255, 255, 255, .12);
            border-radius: 30px;
        }

        .contra-page .acta-hero .breadcrumb-item a {
            color: #fff;
            opacity: .9;
        }

        .contra-page .acta-hero-icon {
            width: 56px;
            height: 56px;
            min-width: 56px;
            border-radius: 16px;
            background: rgba(255, 255, 255, .18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .contra-page .acta-hero-eyebrow {
            text-transform: uppercase;
            letter-spacing: .08em;
            font-size: 12px;
            opacity: .85;
            font-weight: 600;
        }

        .contra-page .acta-state-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, .15);
            border-radius: 30px;
            padding: 6px 16px;
            font-weight: 700;
            font-size: 13px;
        }

        .contra-page .acta-dependency {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13.5px;
            opacity: .95;
        }

        /* ---------- Card acciones ---------- */
        .contra-page .acta-actions-card {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
        }

        /* ---------- Cards generales ---------- */
        .contra-page .acta-card {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 2px 14px rgba(0, 0, 0, .06);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .contra-page .acta-card-header {
            background: #f7fafc;
            border-bottom: 1px solid #edf2f7;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .contra-page .acta-card-header h5 {
            margin: 0;
            font-weight: 700;
            color: var(--acta-primary-dark);
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .contra-page .acta-card-body {
            padding: 20px;
        }

        /* ---------- Tabla de órdenes ---------- */
        .contra-page .table td,
        .contra-page .table th {
            padding: 10px 12px;
            font-size: 13.5px;
            vertical-align: middle;
        }

        .contra-page .acta-orders-table thead th {
            background: linear-gradient(135deg, var(--acta-primary) 0%, var(--acta-primary-dark) 100%);
            color: #fff;
            border-color: var(--acta-primary);
            white-space: nowrap;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .02em;
        }

        .contra-page .acta-orders-table tbody tr:hover {
            background: #f5fbff;
        }

        /* ---------- Agrupación Distrito / Localidad / Sub-Componente ---------- */
        .contra-page .acta-group-distrito {
            border-left: 5px solid var(--acta-primary-dark);
            background: #eef5f9;
            border-radius: 10px;
            margin: 16px 16px 0 16px;
            padding-bottom: 12px;
        }

        .contra-page .acta-group-localidad {
            border-left: 4px solid var(--acta-accent);
            background: #f4faff;
            border-radius: 8px;
            margin: 10px 16px 0 16px;
            padding: 10px 12px 12px;
        }

        .contra-page .acta-group-subcomponente {
            border-left: 4px solid var(--acta-success);
            background: #fff;
            border-radius: 8px;
            margin: 10px 0 0 12px;
            padding: 12px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .05);
        }

        .contra-page .acta-group-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 10px;
        }

        .contra-page .acta-group-title {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 30px;
        }

        .contra-page .acta-group-title-distrito {
            background: var(--acta-primary-dark);
            color: #fff;
            font-size: 13.5px;
        }

        .contra-page .acta-group-title-localidad {
            background: var(--acta-accent);
            color: #fff;
            font-size: 13px;
        }

        .contra-page .acta-group-title-subcomponente {
            background: var(--acta-success);
            color: #fff;
            font-size: 12.5px;
        }

        .contra-page .acta-group-footer {
            padding: 10px 0 0;
            text-align: right;
            border-top: 1px dashed #dbe6ec;
            margin-top: 10px;
        }

        .contra-page .badge-pill {
            padding: 5px 12px;
            font-weight: 600;
        }

        .contra-page .btn-icon {
            border-radius: 30px;
            width: auto;
            padding: 6px 12px;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .contra-page .btn-icon:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, .15);
        }

        /* ---------- Estado vacío ---------- */
        .contra-page .acta-empty-state {
            text-align: center;
            padding: 40px 10px;
            color: #97a3ad;
        }

        .contra-page .acta-empty-state i {
            font-size: 38px;
            margin-bottom: 10px;
            display: block;
            color: #cbd7de;
        }

        .contra-page .acta-empty-state.acta-empty-state-sm {
            padding: 16px 10px;
        }

        .contra-page .acta-empty-state.acta-empty-state-sm i {
            font-size: 24px;
            margin-bottom: 6px;
        }

        /* ---------- Actas de Medición por Localidad/Sub-Componente ---------- */
        .contra-page .acta-certifications-wrap {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px dashed #dbe6ec;
        }

        .contra-page .acta-certifications-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            font-size: 13px;
            color: var(--acta-primary-dark);
            margin-bottom: 8px;
        }

        .contra-page .acta-certifications-table thead th {
            background: #eef5f9;
            color: var(--acta-primary-dark);
            border-color: #dbe6ec;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .02em;
        }

        .contra-page .acta-certifications-table tbody tr:hover {
            background: #f7fbfd;
        }

        /* ---------- Botón agregar orden ---------- */
        .contra-page .acta-actionbar {
            padding: 16px 20px;
            border-top: 1px solid #edf2f7;
            text-align: right;
        }

        .contra-page .acta-actionbar .btn {
            border-radius: 30px;
            padding: 10px 24px;
            font-weight: 700;
        }

        /* ---------- Modal Actas de Medición ---------- */
        .contra-page .modal-content {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
        }

        .contra-page .modal-header {
            background: linear-gradient(135deg, var(--acta-primary) 0%, var(--acta-primary-dark) 100%);
            border-bottom: 0;
        }

        .contra-page .modal-header .modal-title {
            color: #fff;
            font-weight: 700;
            font-size: 16px;
        }

        .contra-page .modal-header .close {
            color: #fff;
            opacity: .85;
            text-shadow: none;
        }

        .contra-page .modal-header .close:hover {
            color: #fff;
            opacity: 1;
        }

        .contra-page .modal-body table thead th {
            background: #eef5f9;
            color: var(--acta-primary-dark);
            border-top: 0;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .contra-page .modal-body table tbody tr:hover {
            background: #f7fbfd;
        }

        .contra-page .acta-pdf-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff0ee;
            color: #d9534f;
            border-radius: 30px;
            padding: 5px 14px;
            font-weight: 600;
            font-size: 12.5px;
            text-decoration: none;
        }

        .contra-page .acta-pdf-link:hover {
            color: #d9534f;
            text-decoration: none;
        }

        /* ---------- Responsive ---------- */
        @media (max-width: 767.98px) {
            .contra-page .acta-hero {
                border-radius: 12px;
                text-align: center;
            }

            .contra-page .acta-hero .d-flex {
                justify-content: center;
            }

            .contra-page .acta-card-body {
                padding: 14px;
            }

            .contra-page .acta-actionbar {
                text-align: center;
            }

            .contra-page .acta-actionbar .btn {
                width: 100%;
            }
        }
    </style>
@endpush

{{-- Mensaje --}}
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif 

@section('content')
    <div class="pcoded-content contra-page">

        {{-- HERO --}}
        <div class="page-header card acta-hero mb-3">
            <div class="row align-items-center p-2">
                <div class="col-12 col-lg-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="acta-hero-icon"><i class="fa-solid fa-file-signature"></i></div>
                        <div class="ml-3">
                            <div class="acta-hero-eyebrow">Módulo de Certificaciones</div>
                            <h4 class="mb-1 text-white">{{ $contract->description }} - {{ $contract->modality->description }} - Contrato N° {{ $contract->number_year }}</h4>
                            <div class="d-flex flex-wrap gap-2" style="gap:8px;">
                                <span class="acta-state-badge"><i class="fa-solid fa-circle-check"></i> {{ $contract->contractState->id }} - {{ $contract->contractState->description }}</span>
                                <span class="acta-dependency"><i class="fa-solid fa-building"></i> {{ $contract->dependency->description }}</span>
                            </div>
                            <div class="acta-dependency mt-1"><i class="fa-solid fa-user-tie"></i> Contratista: {{ $contract->provider->description }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 text-lg-right mt-3 mt-lg-0">
                    <ul class="breadcrumb breadcrumb-title justify-content-lg-end mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa-solid fa-house"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('contracts.index') }}">Contratos</a></li>
                    </ul>

                    @if (Auth::user()->hasPermission(['admin.contracts.update']))
                        <button class="btn btn-light dropdown-toggle waves-effect"
                            type="button" id="acciones" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="true">Acciones</button>
                    @endif

                    <div class="dropdown-menu" aria-labelledby="acciones"
                        data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                        {{-- Verificamos permisos de edición del usuario --}}
                        @if (
                            (Auth::user()->hasPermission(['contracts.contracts.update']) && $contract->contract_state_id >= 1) ||
                                Auth::user()->hasPermission(['admin.contracts.update']))
                            <a style="font-size: 14px; font-weight: bold; color:blue;background-color:lightblue;"
                                class="dropdown-item waves-effect f-w-600"
                                href="{{ route('contracts.edit', $contract->id) }}">Editar
                                Contrato</a>
                        @endif

                        @if (Auth::user()->hasPermission(['admin.contracts.delete']) ||
                                Auth::user()->hasPermission(['contracts.contracts.delete']))
                            {{-- <a href="#" style="font-size: 14px; font-weight: bold; color:red;background-color:lightblue;" class="dropdown-item waves-effect f-w-600" onclick="deleteContract('{{ $contract->id }}')">Eliminar Llamado</a> --}}
                            {{-- <button type="button" title="Borrar" class="btn btn-danger btn-icon" onclick="deleteItem({{ $contract->id }})"><i class="fa fa-trash"></i></button>                                                         --}}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">
                        <div class="row">
                            <div class="col-sm-12">

                                <div class="card acta-card">
                                    <div class="acta-card-header">
                                        <h5><i class="fa-solid fa-list-check"></i> Órdenes de Ejecución - En Curso</h5>
                                        <span class="badge badge-pill" style="background:#eef5f9;color:var(--acta-primary-dark);">{{ $orders->count() }} orden{{ $orders->count() === 1 ? '' : 'es' }}</span>
                                    </div>

                                    @if ($orders->isEmpty())
                                        <div class="acta-empty-state">
                                            <i class="fa-regular fa-folder-open"></i>
                                            No hay órdenes de ejecución en estado "En Curso" para este contrato.
                                        </div>
                                    @else
                                        @php
                                            // Agrupación multinivel: Distrito -> Localidad -> Sub-Componente
                                            // Las actas de medición se generan por Localidad + Sub-Componente y pueden abarcar distintos periodos.
                                            $groupedOrders = $orders->groupBy([
                                                fn ($order) => $order->district->description,
                                                fn ($order) => $order->locality->description,
                                                fn ($order) => $order->component->code . ' - ' . $order->component->description,
                                            ]);
                                        @endphp
                                        <div class="acta-card-body p-0">
                                            @foreach ($groupedOrders as $distrito => $localidades)
                                                <div class="acta-group-distrito">
                                                    @foreach ($localidades as $localidad => $subcomponentes)
                                                        <div class="acta-group-localidad">
                                                            @foreach ($subcomponentes as $subcomponente => $ordersGroup)
                                                                <div class="acta-group-subcomponente">
                                                                    <div class="acta-group-breadcrumb">
                                                                        <span class="acta-group-title acta-group-title-distrito"><i class="fa-solid fa-map"></i> Distrito: {{ $distrito }}</span>
                                                                        <span class="acta-group-title acta-group-title-localidad"><i class="fa-solid fa-location-dot"></i> Localidad: {{ $localidad }}</span>
                                                                        <span class="acta-group-title acta-group-title-subcomponente"><i class="fa-solid fa-layer-group"></i> Sub-Componente: {{ $subcomponente }}</span>
                                                                    </div>

                                                                    <div class="table-responsive">
                                                                        <table class="acta-orders-table display table table-striped table-bordered mb-0"
                                                                            style="width:100%">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>N° OE</th>
                                                                                    <th>Fecha Orden</th>
                                                                                    <th>Monto Orden</th>
                                                                                    <th>Fiscal</th>
                                                                                    <th>Fecha Acuse</th>
                                                                                    <th>Fecha Alerta</th>
                                                                                    <th>Plazo Final</th>
                                                                                    {{-- <th>Estado</th> --}}
                                                                                    <th style="width: 190px; text-align: center;">Acciones</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($ordersGroup->sortBy('id') as $order)
                                                                                    <tr>
                                                                                        <td style="text-align: center;width: 60px;">
                                                                                            {{ $order->component_code }} - {{ $order->number }}
                                                                                        </td>
                                                                                        <td style="text-align: center;width: 25px;">
                                                                                            {{ date('d/m/Y', strtotime($order->created_at)) }}</td>
                                                                                        <td style="text-align: center;width: 100px;">
                                                                                            {{ $order->totalAmountFormat() }}</td>
                                                                                        <td style="color:black;text-align: left;width: 150px;">
                                                                                            {{ $order->creatorUser->name }} {{ $order->creatorUser->lastname }} - {{ $order->creatorUser->position->description }}
                                                                                        </td>
                                                                                        {{-- FECHA ACUSE CONTRATISTA --}}
                                                                                        <td style="color:#ff0000;text-align: left;width: 25px;">
                                                                                            @if ($order->sign_date)
                                                                                                {{ \Carbon\Carbon::parse($order->sign_date)->format('d/m/Y') }}
                                                                                            @endif
                                                                                        </td>

                                                                                        {{-- FECHA ALERTA 03 DIAS ANTES NO PINTA SI YA ESTA FINALIZADO ESTADO 4 --}}
                                                                                        <td style="text-align: left; width: 20px;">
                                                                                            @php
                                                                                                    $eventDays = \App\Models\Event::where('order_id', $order->id)->value('event_days');
                                                                                                    // Verifica si hay eventos asociados a la orden // Si hay eventos, calcula restando 3 dias la ultima fecha del plazo
                                                                                                    if ($eventDays) {
                                                                                                        $ultimoEvento = \App\Models\Event::where('order_id', $order->id)
                                                                                                        ->orderByDesc('event_date_fin')
                                                                                                        ->first();

                                                                                                        $fechaCalculada = \Carbon\Carbon::parse($ultimoEvento->event_date_fin)->subDays(3);
                                                                                                    } else {
                                                                                                        $fechaCalculada = $order->sign_date ? \Carbon\Carbon::parse($order->sign_date)->addDays($order->plazo - 3) : null;
                                                                                                    }
                                                                                            @endphp

                                                                                            @if ($order->sign_date)
                                                                                                @if ($order->orderState->id == 1 && $fechaCalculada && \Carbon\Carbon::now()->gt($fechaCalculada))
                                                                                                    {{ $fechaCalculada->format('d/m/Y') }}
                                                                                                    <span class="badge badge-pill badge-warning">FECHA ALERTA</span>
                                                                                                @else
                                                                                                    @php
                                                                                                        $eventDays = \App\Models\Event::where('order_id', $order->id)->value('event_days');
                                                                                                    @endphp

                                                                                                    @if ($fechaCalculada)
                                                                                                        {{ $fechaCalculada->format('d/m/Y') }}
                                                                                                        @if ($eventDays)
                                                                                                            <span class="badge badge-pill badge-danger">EXTENDIDO</span>
                                                                                                        @endif
                                                                                                    @endif
                                                                                                @endif
                                                                                            @endif
                                                                                        </td>

                                                                                        {{-- PLAZO FINAL CALCULA SI FECHA PLAZO ES IGUAL A FECHA ACTUAL Y PONE EN ROJO - NO PINTA SI YA ESTA FINALIZADO ESTADO 4 --}}
                                                                                        <td style="text-align: left; width: 25px;">
                                                                                                @php
                                                                                                    $eventDays = \App\Models\Event::where('order_id', $order->id)->value('event_days');
                                                                                                    // Obtener el último evento asociado a la orden

                                                                                                    // Verifica si hay eventos asociados a la orden // Si hay eventos, muestra la ultima fecha de vencimiento event_date_fin
                                                                                                    if ($eventDays) {
                                                                                                        $ultimoEvento = \App\Models\Event::where('order_id', $order->id)
                                                                                                        ->orderByDesc('event_date_fin')
                                                                                                        ->first();

                                                                                                        $fechaVencimiento = \Carbon\Carbon::parse($ultimoEvento->event_date_fin);
                                                                                                    } else {
                                                                                                        $fechaVencimiento = $order->sign_date ? \Carbon\Carbon::parse($order->sign_date)->addDays($order->plazo) : null;
                                                                                                    }
                                                                                                @endphp

                                                                                                @if ($order->sign_date)
                                                                                                    @if ($order->orderState->id == 1 && $fechaVencimiento && \Carbon\Carbon::now()->gt($fechaVencimiento))
                                                                                                        {{ $fechaVencimiento->format('d/m/Y') }}
                                                                                                        <span class="badge badge-pill badge-danger">PLAZO VENCIDO</span>
                                                                                                    @else
                                                                                                        @php
                                                                                                            $eventDays = \App\Models\Event::where('order_id', $order->id)->value('event_days');
                                                                                                        @endphp

                                                                                                        @if ($fechaVencimiento)
                                                                                                            {{ $fechaVencimiento->format('d/m/Y') }}
                                                                                                            @if ($eventDays)
                                                                                                            <span class="badge badge-pill badge-danger">EXTENDIDO</span>
                                                                                                            @endif
                                                                                                        @endif
                                                                                                    @endif
                                                                                            @endif
                                                                                        </td>

                                                                                        {{-- Estado: se quitó como columna propia (header comentado más arriba);
                                                                                             para órdenes ANULADAS ya se muestra "Motivo: ..." en la columna Acciones. --}}

                                                                                        <td>
                                                                                            {{-- Para mostra datos de acuerdo a estados de la Orden  --}}
                                                                                            @if (in_array($order->orderState->id, [1]))
                                                                                                {{-- Columna Acciones: sólo el ícono del ojo, que lleva al PDF de la Orden de Ejecución.
                                                                                                     Las Actas de Medición generadas se listan debajo de la tabla, agrupadas por Localidad/Sub-Componente. --}}
                                                                                                <a href="/pdf/panel_contracts10/{{ $order->id }}"
                                                                                                    title="Ver Orden" target="_blank"
                                                                                                    class="btn btn-warning btn-icon"><i
                                                                                                        class="fa fa-eye"></i></a>

                                                                                                {{-- Muestra botones si no son fiscales --}}
                                                                                                {{-- @if (Auth::user()->hasPermission(['admin.orders.show', 'orders.orders.view']))
                                                                                                    @if ($order->items->count() > 0)
                                                                                                        <a href="/pdf/panel_contracts10/{{ $order->id }}"
                                                                                                            title="Ver Orden" target="_blank"
                                                                                                            class="btn btn-success btn-icon"><i
                                                                                                                class="fa fa-eye"></i></a>
                                                                                                    @endif
                                                                                                @endif --}}
                                                                                            @endif

                                                                                            {{-- SI ESTA FINALIZADO --}}
                                                                                            @if (in_array($order->orderState->id, [4]))
                                                                                                    <a href="/pdf/panel_contracts10/{{ $order->id }}"
                                                                                                    title="Ver Orden" target="_blank"
                                                                                                    class="btn btn-success btn-icon"><i class="fa fa-eye"></i></a>

                                                                                                    <a href="{{ route('orders.file.view', $order->id) }}" title="Ver Archivo de Finalización de Orden" target="_blank" class="btn btn-danger btn-icon"><i class="fa-solid fa-file-pdf"></i></a>

                                                                                                    @if ($order->events->count() > 0)
                                                                                                    <button type="button" title="Cargar Eventos"
                                                                                                                class="btn btn-primary btn-icon"
                                                                                                                onclick="itemEvents({{ $order->id }})"><i
                                                                                                                    class="fa-solid fa-calendar-days"></i></button>
                                                                                                    @endif
                                                                                            @endif

                                                                                            {{-- SI ESTA ANULADO --}}
                                                                                            @if (in_array($order->orderState->id, [5]))
                                                                                                <a style="color: red;">Motivo: {{ $order->motivo_anule }}</a>
                                                                                                    <br>
                                                                                                    {{-- MOSTRAR PDF DE ORDEN --}}
                                                                                                    <a href="/pdf/panel_contracts10/{{ $order->id }}"
                                                                                                    title="Ver Orden" target="_blank"
                                                                                                    class="btn btn-danger btn-icon"><i
                                                                                                        class="fa fa-eye"></i></a>
                                                                                            @endif

                                                                                            {{-- botón para cargar archivos a la orden --}}
                                                                                            {{-- <button type="button" title="Cargar Archivos"
                                                                                            class="btn btn-info btn-icon"
                                                                                            onclick="itemFiles({{ $order->id }})"><i
                                                                                                class="fa fa-files-o"></i></button> --}}
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>

                                                                    @php
                                                                        // Actas de Medición de todas las órdenes de este grupo (Localidad + Sub-Componente),
                                                                        // ordenadas por N° de Planilla. Las Actas generadas junto con otras (mismo batch_uuid,
                                                                        // por abarcar más de una Orden de Ejecución) se muestran en una única fila que agrupa
                                                                        // todas las órdenes referenciadas.
                                                                        $actasGrupo = collect();
                                                                        foreach ($ordersGroup as $ordenActa) {
                                                                            foreach ($ordenActa->certifications as $certificacion) {
                                                                                $claveTanda = $certificacion->batch_uuid ?? ('single-' . $certificacion->id);
                                                                                if (!$actasGrupo->has($claveTanda)) {
                                                                                    $actasGrupo->put($claveTanda, ['certification' => $certificacion, 'orders' => collect()]);
                                                                                }
                                                                                $actasGrupo[$claveTanda]['orders']->push($ordenActa);
                                                                            }
                                                                        }
                                                                        $actasGrupo = $actasGrupo->sortBy(fn ($fila) => $fila['certification']->number)->values();
                                                                    @endphp
                                                                    <div class="acta-certifications-wrap">
                                                                        <div class="acta-certifications-title">
                                                                            <i class="fa-solid fa-clipboard-check"></i> Actas de Medición - {{ $localidad }} / {{ $subcomponente }}
                                                                        </div>
                                                                        @if ($actasGrupo->isEmpty())
                                                                            <div class="acta-empty-state acta-empty-state-sm">
                                                                                <i class="fa-regular fa-folder-open"></i>
                                                                                Aún no se generaron Actas de Medición para esta Localidad / Sub-Componente.
                                                                            </div>
                                                                        @else
                                                                            <div class="table-responsive">
                                                                                <table class="acta-certifications-table display table table-striped table-bordered mb-0" style="width:100%">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>N° Planilla</th>
                                                                                            <th>Orden</th>
                                                                                            <th>Período</th>
                                                                                            <th>Fecha Medición</th>
                                                                                            <th class="text-right">PDF</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @foreach ($actasGrupo as $fila)
                                                                                            <tr>
                                                                                                <td><strong>{{ $fila['certification']->number }}</strong></td>
                                                                                                <td>{{ $fila['orders']->map(fn ($o) => $o->component_code . '-' . $o->number)->implode(', ') }}</td>
                                                                                                <td>{{ $fila['certification']->period }}</td>
                                                                                                <td>{{ $fila['certification']->signDateFormat() }}</td>
                                                                                                <td class="text-right">
                                                                                                    <a href="{{ route('item_certifications.pdf', $fila['certification']->id) }}"
                                                                                                        target="_blank" rel="noopener" class="acta-pdf-link">
                                                                                                        <i class="fa-solid fa-file-pdf"></i> Ver Acta
                                                                                                    </a>
                                                                                                    @if ($fila['certification']->state_id == \App\Models\ItemCertification::STATE_EMITIDO)
                                                                                                        <a href="{{ route('items_contracts.certi_group', [$contract->id, $ordersGroup->first()->locality_id, $ordersGroup->first()->component_id]) }}?acta={{ $fila['certification']->id }}"
                                                                                                            class="btn btn-primary btn-icon" title="Editar Acta">
                                                                                                            <i class="fa-solid fa-pen"></i>
                                                                                                        </a>
                                                                                                        <button type="button" class="btn btn-success btn-icon"
                                                                                                            title="Validar Acta" onclick="validarActa({{ $fila['certification']->id }})">
                                                                                                            <i class="fa-solid fa-check"></i>
                                                                                                        </button>
                                                                                                    @else
                                                                                                        <span class="badge badge-pill badge-success">Aprobada</span>
                                                                                                    @endif
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        @endif
                                                                    </div>

                                                                    @php
                                                                        // Sólo las órdenes "En Curso" (estado 1) pueden generar nuevas Actas de Medición
                                                                        $ordenesEnCurso = $ordersGroup->filter(fn ($o) => $o->orderState->id == 1);
                                                                        $primeraOrdenEnCurso = $ordenesEnCurso->first();
                                                                    @endphp
                                                                    @if ($primeraOrdenEnCurso)
                                                                        <div class="acta-group-footer">
                                                                            <a href="{{ route('items_contracts.certi_group', [$contract->id, $primeraOrdenEnCurso->locality_id, $primeraOrdenEnCurso->component_id]) }}"
                                                                                title="Realizar Medición"
                                                                                class="btn btn-primary btn-icon">
                                                                                <i class="fa fa-table mr-1"></i> Realizar Medición
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="acta-actionbar">
                                        @if (Auth::user()->hasPermission(['admin.orders.create', 'orders.orders.create']))
                                            @if ($contract)
                                                {{-- Si contrato tiene rubros cargados --}}
                                                @if ($contract->itemsContracts->isNotEmpty())
                                                    {{-- Si contrato está anulado no muestra agregar ítems --}}
                                                    @if (in_array($contract->contract_state_id, [1]))
                                                        <a href="{{ route('contracts.orders.create', $contract->id) }}" class="btn btn-primary"><i class="fa-solid fa-plus mr-1"></i> Agregar Orden</a>
                                                    @else
                                                        <button class="btn btn-danger" disabled>Contrato no está en Curso</button>
                                                    @endif
                                                @else
                                                    <button class="btn btn-danger" disabled>Falta Agregar Rubros al Contrato</button>
                                                @endif
                                            @else
                                                <p class="text-danger mb-0">No hay un contrato disponible.</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            // Una tabla por cada grupo Distrito -> Localidad -> Sub-Componente
            $('.acta-orders-table').each(function() {
                $(this).DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "language": {
                        "search": "Buscar orden:",
                        "lengthMenu": "Mostrar _MENU_ órdenes",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ órdenes",
                        "infoEmpty": "Sin órdenes en curso",
                        "zeroRecords": "No se encontraron órdenes",
                        "paginate": { "previous": "Anterior", "next": "Siguiente" }
                    },
                    "columnDefs": [
                        // Prioridad de columnas al colapsar en pantallas angostas: N° OE y Acciones se mantienen visibles
                        { "responsivePriority": 1, "targets": 6 },  // Acciones
                        { "responsivePriority": 2, "targets": 0 },  // N° OE
                        { "responsivePriority": 3, "targets": 2 },  // Monto Orden
                        { "responsivePriority": 10, "targets": [1, 3, 4, 5] },
                    ],
                });
            });

            // Una tabla de Actas de Medición por cada grupo Distrito -> Localidad -> Sub-Componente
            $('.acta-certifications-table').each(function() {
                $(this).DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "language": {
                        "search": "Buscar acta:",
                        "lengthMenu": "Mostrar _MENU_ actas",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ actas",
                        "infoEmpty": "Sin actas de medición",
                        "zeroRecords": "No se encontraron actas",
                        "paginate": { "previous": "Anterior", "next": "Siguiente" }
                    },
                });
            });


            const table = $('#example').DataTable({
                ajax: '/tables', // URL que devuelve los datos JSON
                columns: [{
                        data: 'title',
                        title: 'Título'
                    },
                    {
                        data: null,
                        title: 'Acción',
                        render: function(data, type, row) {
                            return `
                        <button class="btn btn-primary toggle-files" data-id="${row.id}">Ver Archivos</button>
                        <div id="files-${row.id}" class="files-container" style="display: none; margin-top: 10px;">
                            ${row.files.map(file => `<a href="${file.url}" target="_blank">${file.name}</a><br>`).join('')}
                        </div>
                    `;
                        }
                    }
                ]
            });

            // Manejar el despliegue de archivos
            $('#example').on('click', '.toggle-files', function() {
                const id = $(this).data('id');
                $(`#files-${id}`).toggle(); // Mostrar/Ocultar contenedor de archivos
            });

            updateOrder = function(order) {
                location.href = '/contracts/{{ $contract->id }}/orders/' + order + '/edit/';
            }

            anuleOrder = function(order) {
                swal({
                        title: "Atención",
                        text: "Está seguro que desea anular la orden? Ingrese el motivo:",
                        type: "input", // Permite ingresar texto
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sí, anular",
                        cancelButtonText: "Cancelar",
                        closeOnConfirm: false,
                        inputPlaceholder: "Escriba el motivo aquí..."
                    },
                    function(motivo) {
                        if (motivo === false) return false; // Si se presiona cancelar
                        
                        if (motivo === "") {
                            swal.showInputError("Debe ingresar un motivo!"); // Si no se ingresa motivo 
                            return false;
                        }
                            $.ajax({
                                url: '/contracts/{{ $contract->id }}/orders/' + order,
                                method: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}',
                                    motivo: motivo // Se envía el motivo en la solicitud
                                },
                                success: function(data) {
                                    try {
                                        response = (typeof data == "object") ? data : JSON
                                            .parse(data);
                                        if (response.status == "success") {
                                            swal("Éxito!", "Orden Anulada correctamente",
                                                "success");
                                            location.reload();
                                        } else {
                                            swal("Error!", response.message, "error");
                                        }
                                    } catch (error) {
                                        swal("Error!",
                                            "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                            "error");
                                        console.log(error);
                                    }
                                },
                                error: function(error) {
                                    swal("Error!",
                                        "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                        "error");
                                    console.log(error);
                                }
                            });
                        
                    }
                );
            };

            // anuleOrder = function(order) {
            //     swal({
            //             title: "Atención",
            //             text: "Está seguro que desea anular la orden?",
            //             type: "warning",
            //             showCancelButton: true,
            //             confirmButtonColor: "#DD6B55",
            //             confirmButtonText: "Sí, anular",
            //             cancelButtonText: "Cancelar",
            //         },
            //         function(isConfirm) {
            //             if (isConfirm) {
            //                 $.ajax({
            //                     url: '/contracts/{{ $contract->id }}/orders/' + order,
            //                     method: 'POST',
            //                     data: {
            //                         _method: 'DELETE',
            //                         _token: '{{ csrf_token() }}'
            //                     },
            //                     success: function(data) {
            //                         try {
            //                             response = (typeof data == "object") ? data : JSON
            //                                 .parse(data);
            //                             if (response.status == "success") {
            //                                 swal("Éxito!", "Orden Anulada correctamente",
            //                                     "success");
            //                                 location.reload();
            //                             } else {
            //                                 swal("Error!", response.message, "error");
            //                             }
            //                         } catch (error) {
            //                             swal("Error!",
            //                                 "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
            //                                 "error");
            //                             console.log(error);
            //                         }
            //                     },
            //                     error: function(error) {
            //                         swal("Error!",
            //                             "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
            //                             "error");
            //                         console.log(error);
            //                     }
            //                 });
            //             }
            //         }
            //     );
            // };

            DesanuleOrder = function(order) {
                swal({
                        title: "Atención",
                        text: "Está seguro que desea Desanular la orden?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sí, desanular",
                        cancelButtonText: "Cancelar",
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: '/contracts/{{ $contract->id }}/orders/' + order,

                                method: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    try {
                                        response = (typeof data == "object") ? data : JSON
                                            .parse(data);
                                        if (response.status == "success") {
                                            swal("Éxito!", "Orden Desanulada correctamente",
                                                "success");
                                            location.reload();
                                        } else {
                                            swal("Error!", response.message, "error");
                                        }
                                    } catch (error) {
                                        swal("Error!",
                                            "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                            "error");
                                        console.log(error);
                                    }
                                },
                                error: function(error) {
                                    swal("Error!",
                                        "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                        "error");
                                    console.log(error);
                                }
                            });
                        }
                    }
                );
            };

            anuleRubro = function(contract, component) {
                swal({
                        title: "Atención",
                        text: "¿Está seguro que desea eliminar el componente cargado?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sí, anular",
                        cancelButtonText: "Cancelar",
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: `/items_contracts/${contract}/component/${component}/delete`,
                                method: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    try {
                                        let response = (typeof data === "object") ? data : JSON
                                            .parse(data);
                                        if (response.status === "success") {
                                            swal("Éxito!", "Componente anulado correctamente",
                                                "success");
                                            location.reload();
                                        } else {
                                            swal("Error!", response.message, "error");
                                        }
                                    } catch (error) {
                                        swal("Error!",
                                            "1-Ocurrió un error al procesar la solicitud.",
                                            "error");
                                        console.log(error);
                                    }
                                },
                                error: function(error) {
                                    swal("Error!",
                                        "2-Ocurrió un error al procesar la solicitud.",
                                        "error");
                                    console.log(error);
                                }
                            });
                        }
                    }
                );
            };

            //lleva a index de ItemsOrdersController
            itemOrder = function(order) {
                location.href = '/orders/' + order + '/items_orders';
            }

            //lleva a index de eventos de Ordenes
            itemEvents = function(order) {
                location.href = '/orders/' + order + '/events';
                // lleva a esta ruta Route::resource('orders.events', EventsOrdersController::class);
            }

            // Aprueba una Acta de Medición (y toda su tanda, si abarca más de una Orden de Ejecución).
            // Una vez aprobada queda bloqueada: ya no se puede editar ni volver a grabar.
            validarActa = function(certificationId) {
                swal({
                        title: "Atención",
                        text: "¿Está seguro que desea validar esta Acta de Medición? Una vez validada ya no podrá editarla.",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sí, validar",
                        cancelButtonText: "Cancelar",
                    },
                    function(isConfirm) {
                        if (!isConfirm) return;

                        $.ajax({
                            url: '/item_certifications/' + certificationId + '/approve',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                try {
                                    response = (typeof data == "object") ? data : JSON.parse(data);
                                    if (response.status == "success") {
                                        swal("Éxito!", response.message, "success");
                                        location.reload();
                                    } else {
                                        swal("Error!", response.message, "error");
                                    }
                                } catch (error) {
                                    swal("Error!",
                                        "Ocurrió un error intentado resolver la solicitud, por favor recargue la página e intente nuevamente.",
                                        "error");
                                    console.log(error);
                                }
                            },
                            error: function(error) {
                                swal("Error!",
                                    "Ocurrió un error intentado resolver la solicitud, por favor recargue la página e intente nuevamente.",
                                    "error");
                                console.log(error);
                            }
                        });
                    }
                );
            }

            //lleva a index de archivos
            itemFiles = function(order) {                                
                location.href = '/orders/' + order + '/files';
                // lleva a esta ruta Route::resource('orders.files', OrdersFilesController::class);
            }
            
            //lleva a indexRubros de ItemsContractsController
            // itemContraRubro = function(order, contract, component) {
            //     location.href = '/orders/' + order + '/items_contracts/' + contract + '/component/' + component + '/itemsRubros';
            // }

            //lleva a index de ItemsContractsController
            itemRubro = function(contract, component) {
                location.href = '/items_contracts/' + contract + '/component/' + component + '/items';
            }

            updateItem = function(item) {
                //llamar a Función JS que está en H:\Proyectos\sistedoc\public\js\guardar-tab.js
                // persistirTab();
                location.href = '/contracts/{{ $contract->id }}/items/' + item + '/edit/';
            }

            deleteItem = function(item) {
                swal({
                        title: "Atención",
                        text: "Está seguro que desea eliminar la póliza?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "Cancelar",
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: '/contracts/{{ $contract->id }}/items/' + item,

                                method: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    try {
                                        response = (typeof data == "object") ? data : JSON
                                            .parse(data);
                                        if (response.status == "success") {
                                            swal("Éxito!", "Póliza eliminada correctamente",
                                                "success");
                                            location.reload();
                                        } else {
                                            swal("Error!", response.message, "error");
                                        }
                                    } catch (error) {
                                        swal("Error!",
                                            "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                            "error");
                                        console.log(error);
                                    }
                                },
                                error: function(error) {
                                    swal("Error!",
                                        "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                        "error");
                                    console.log(error);
                                }
                            });
                        }
                    }
                );
            };


            updateContracts = function(budget) {
                // persistirTab();
                location.href = '/contracts/{{ $contract->id }}/items_budget/' + budget + '/edit/';
            }

            recibecontract = function(contract_id) {
                $.ajax({
                    url: '/contracts/recibe_contract/' + contract_id,
                    method: 'POST',
                    data: '_token=' + '{{ csrf_token() }}',
                    success: function(data) {
                        try {
                            response = (typeof data == "object") ? data : JSON.parse(data);
                            if (response.status == "success") {
                                swal({
                                        title: "Éxito!",
                                        text: response.message,
                                        type: "success"
                                    },
                                    function(isConfirm) {
                                        location.reload();
                                    });
                            } else {
                                swal("Error!", response.message, "error");
                            }
                        } catch (error) {
                            swal("Error!",
                                "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                "error");
                            console.log(error);
                        }
                    },
                    error: function(error) {
                        swal("Error!",
                            "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                            "error");
                        console.log(error);
                    }
                });
            }

            derivecontract = function(contract_id) {
                $.ajax({
                    url: '/contracts/derive_contract/' + contract_id,
                    method: 'POST',
                    data: '_token=' + '{{ csrf_token() }}',
                    success: function(data) {
                        try {
                            response = (typeof data == "object") ? data : JSON.parse(data);
                            if (response.status == "success") {
                                swal({
                                        title: "Éxito!",
                                        text: response.message,
                                        type: "success"
                                    },
                                    function(isConfirm) {
                                        location.reload();
                                    });
                            } else {
                                swal("Error!", response.message, "error");
                            }
                        } catch (error) {
                            swal("Error!",
                                "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                "error");
                            console.log(error);
                        }
                    },
                    error: function(error) {
                        swal("Error!",
                            "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                            "error");
                        console.log(error);
                    }
                });
            }

            itemAwardHistories = function(item) {
                //lleva a itemawardhistories index
                location.href = '/items/' + item + '/item_award_histories';
            }

            deleteContract = function(id) {
                swal({
                        title: "Atención",
                        text: "¿Está seguro que desea eliminar el llamado?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "Cancelar",
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: '{{ route('contracts.delete', ['contract_id' => ':id']) }}'
                                    .replace(':id', id),
                                method: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    try {
                                        response = (typeof data == "object") ? data : JSON
                                            .parse(data);
                                        if (response.status == "success") {
                                            location.reload();
                                        } else {
                                            swal("Error!", response.message, "error");
                                        }
                                    } catch (error) {
                                        swal("Error!",
                                            "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                            "error");
                                        console.log(error);
                                    }
                                },
                                error: function(error) {
                                    swal("Error!",
                                        "Ocurrió 1 un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                        "error");
                                    console.log(error);
                                }
                            });
                        }
                    });
            }


            deleteFile = function(file) {
                swal({
                        title: "Atención",
                        text: "Está seguro que desea anular el Archivo?",

                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sí, anular",
                        cancelButtonText: "Cancelar",
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: '/contracts/files/' + file + '/delete/',
                                method: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    try {
                                        response = (typeof data == "object") ? data : JSON
                                            .parse(data);
                                        if (response.status == "success") {
                                            location.reload();
                                        } else {
                                            swal("Error!", response.message, "error");
                                        }
                                    } catch (error) {
                                        swal("Error!",
                                            "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                            "error");
                                        console.log(error);
                                    }
                                },
                                error: function(error) {
                                    swal("Error!",
                                        "Ocurrió 1 error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                        "error");
                                    console.log(error);
                                }
                            });
                        }
                    }
                );
            };

            deleteObjection = function(objection) {
                swal({
                        title: "Atención",
                        text: "Está seguro que desea eliminar el registro?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "Cancelar",
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: '/contracts/{{ $contract->id }}/objections/' + objection,
                                method: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    try {
                                        response = (typeof data == "object") ? data : JSON
                                            .parse(data);
                                        if (response.status == "success") {
                                            location.reload();
                                        } else {
                                            swal("Error!", response.message, "error");
                                        }
                                    } catch (error) {
                                        swal("Error!",
                                            "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                            "error");
                                        console.log(error);
                                    }
                                },
                                error: function(error) {
                                    swal("Error!",
                                        "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                        "error");
                                    console.log(error);
                                }
                            });
                        }
                    }
                );
            };

            deleteObjectionResponse = function(objection, objection_response) {
                swal({
                        title: "Atención",
                        text: "Está seguro que desea eliminar el registro?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "Cancelar",
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: '/contracts/' + objection + '/objections_responses/' +
                                    objection_response,
                                method: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(data) {
                                    try {
                                        response = (typeof data == "object") ? data : JSON
                                            .parse(data);
                                        if (response.status == "success") {
                                            location.reload();
                                        } else {
                                            swal("Error!", response.message, "error");
                                        }
                                    } catch (error) {
                                        swal("Error!",
                                            "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                            "error");
                                        console.log(error);
                                    }
                                },
                                error: function(error) {
                                    swal("Error!",
                                        "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina",
                                        "error");
                                    console.log(error);
                                }
                            });
                        }
                    }
                );
            };

        });
    </script>
@endpush
