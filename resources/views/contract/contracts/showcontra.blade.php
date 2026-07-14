@extends('layouts.app')

@push('styles')
    <style type="text/css">
        .table td,
        .table th {
            padding: 0.2rem 0.5rem;
            font-size: 14px
        }

        .tab-content.card-block {
            padding: 1.25rem 0.5rem;
        }

        .card-block table thead th {
            background-color: #347ead;
            color: #fff;
            border-color: #347ead;
            white-space: nowrap;
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
    {{-- <div class="container"> --}}
    <div class="pcoded-content">
        <div class="page-header card">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa fa-list bg-c-blue"></i>
                        <div class="d-inline">
                            <h5>Módulo de Certificaciones</h5>
                            <span>Contrato Nº {{ $contract->number_year }}</span>
                            <br><br>
                            <h5>
                                <p style="font-size: 17px; font-weight: bold; color:#FF0000;">Estado Actual:
                                    {{ $contract->contractState->id . ' - ' . $contract->contractState->description }}</p>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="page-header-breadcrumb">
                        <ul class=" breadcrumb breadcrumb-title">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('contracts.index') }}">Contratos</a>
                            </li>
                        </ul>
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
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-sm-10 text-left">
                                                <h5>Llamado:
                                                    {{-- {{ $contract->description . ' - ' . $contract->modality->description . ' - Contrato N° ' . $contract->number_year . ' - ' . $contract->provider->description }} --}}
                                                    {{ $contract->description }} - {{ $contract->modality->description }} - Contrato N° {{ $contract->number_year }}
                                                </h5>
                                                <h5 style="color: red; background-color: yellow;"> Contratista: {{ $contract->provider->description }}</h5>
                                            </div>
                                            <div class="col-sm-10 text-left">
                                                <h5 style="font-size: 17px; font-weight: bold; color:blue">Dependencia
                                                    Responsable: {{ $contract->dependency->description }}</h5>
                                            </div>

                                            <div class="col-sm-2">
                                                @if (Auth::user()->hasPermission(['admin.contracts.update']))
                                                    {{-- @if (in_array($contract->contract_state_id, [1, 2])) --}}
                                                    <button class="btn btn-primary dropdown-toggle waves-effect"
                                                        type="button" id="acciones" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="true">Acciones</button>
                                                    {{-- @endif --}}
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
                                </div>

                                <div class="card">
                                    <div class="card-block">
                                        {{-- <ul class="nav nav-tabs md-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#tab2" role="tab"><i
                                                        class="fa fa-clone"></i> Órdenes de Ejec.</a>
                                                <div class="slide"></div>
                                            </li>                                            
                                        </ul> --}}
                                        
                                        
                                        <div class="tab-content card-block">
                                            {{-- ORDENES DE EJECUCIÓN - MUESTRA CON SEARCH --}}
                                            <link rel="stylesheet"
                                                href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
                                            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                                            <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

                                            {{-- <div class="tab-pane" id="tab2" role="tabpanel"> --}}
                                            <div class="table-responsive">
                                                <table id="items" class="display; table table-striped table-bordered"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr>                                                            
                                                            <th>N° OE</th>
                                                            <th>Fecha Orden</th>
                                                            <th>Monto Orden</th>
                                                            <th>Distrito-Localidad</th>
                                                            <th>Fiscal</th>
                                                            <th>Sub-Componente</th>                                                            
                                                            <th>Fecha Acuse</th>
                                                            <th>Fecha Alerta</th>
                                                            <th>Plazo Final</th>
                                                            {{-- <th>Estado</th> --}}
                                                            <th style="width: 190px; text-align: center;">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($orders->sortBy('id') as $index => $order)
                                                            <tr>                                                                
                                                                <td style="text-align: center;width: 60px;">
                                                                    {{ $order->component_code }} - {{ $order->number }}
                                                                </td>
                                                                <td style="text-align: center;width: 25px;">
                                                                    {{ date('d/m/Y', strtotime($order->created_at)) }}</td>                                                                
                                                                <td style="text-align: center;width: 100px;">
                                                                    {{ $order->totalAmountFormat() }}</td>
                                                                <td style="text-align: left;width: 120px;">
                                                                    {{ $order->district->description }} - {{ $order->locality->description }}
                                                                </td>
                                                                <td style="color:black;text-align: left;width: 150px;">
                                                                    {{ $order->creatorUser->name }} {{ $order->creatorUser->lastname }} - {{ $order->creatorUser->position->description }}
                                                                </td>                                                                    
                                                                <td style="text-align: left;width: 350px;">
                                                                    {{ $order->component->code }}-{{ $order->component->description }}
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
                                                                            <span class="badge badge-warning">FECHA ALERTA</span>
                                                                        @else
                                                                            @php
                                                                                $eventDays = \App\Models\Event::where('order_id', $order->id)->value('event_days');
                                                                            @endphp

                                                                            @if ($fechaCalculada)
                                                                                {{ $fechaCalculada->format('d/m/Y') }}
                                                                                @if ($eventDays)
                                                                                    <span class="badge badge-danger">EXTENDIDO</span>
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
                                                                                <span class="badge badge-danger">PLAZO VENCIDO</span>
                                                                            @else
                                                                                @php
                                                                                    $eventDays = \App\Models\Event::where('order_id', $order->id)->value('event_days');
                                                                                @endphp

                                                                                @if ($fechaVencimiento)
                                                                                    {{ $fechaVencimiento->format('d/m/Y') }}
                                                                                    @if ($eventDays)
                                                                                    <span class="badge badge-danger">EXTENDIDO</span>
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
                                                                        {{-- @if (Auth::user()->hasPermission(['admin.orders.update', 'orders.orders.update']))                                                                             --}}
                                                                            {{-- @if ($order->items->count() > 0)                                                                                 --}}
                                                                                {{-- MOSTRAR PDF DE ORDEN --}}
                                                                                <a href="/pdf/panel_contracts10/{{ $order->id }}"
                                                                                    title="Ver Orden" target="_blank"
                                                                                    class="btn btn-warning btn-icon"><i
                                                                                        class="fa fa-eye"></i></a>                                                                                

                                                                                {{-- PARA REALIZAR CERTIFICADOS --}}
                                                                                    <button type="button" title="Realizar Medición"
                                                                                        class="btn btn-primary btn-icon"
                                                                                        onclick="certiOrder({{ $order->id }}, {{ $order->contract->id }}, {{ $order->component->id }})">
                                                                                        <i class="fa fa-table"></i></button>
                                                                            {{-- @endif                                                                             --}}
                                                                        {{-- @endif --}}

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

                                                <div class="text-right">
                                                    {{-- @if (Auth::user()->hasPermission(['contracts.contracts.create', 'admin.orders.create'])) --}}
                                                    @if (Auth::user()->hasPermission(['admin.orders.create', 'orders.orders.create']))
                                                        @if ($contract)
                                                            {{-- Si contrato tiene rubros cargados --}}    
                                                            @if ($contract->itemsContracts->isNotEmpty())
                                                                {{-- Si contrato está anulado no muestra agregar ítems --}}
                                                                @if (in_array($contract->contract_state_id, [1]))
                                                                    <a href="{{ route('contracts.orders.create', $contract->id) }}" class="btn btn-primary">Agregar Orden</a>
                                                                @else
                                                                    <button class="btn btn-danger" disabled>Contrato no está en Curso</button>
                                                                @endif    
                                                            @else
                                                                <button class="btn btn-danger" disabled>Falta Agregar Rubros al Contrato</button>
                                                            @endif
                                                        @else
                                                            <p class="text-danger">No hay un contrato disponible.</p>
                                                        @endif


                                                    @endif
                                                </div>
                                                {{-- <br><br><br> --}}
                                                {{-- ACA DEBEN IR VALORES CALCULADOS DE ACUERDO A LA GENERACIÓN DE ORDENES --}}
                                                {{-- <div
                                                    style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                                                    <div
                                                        style="flex: 1; text-align: center; font-size: 16px; font-weight: bold; color: blue; background-color: white; padding: 10px;">
                                                        <u>MONTO DEL CONTRATO:</u> {{ $contract->totalAmountFormat() }}
                                                    </div>
                                                    <div
                                                        style="flex: 1; text-align: center; font-size: 16px; font-weight: bold; color: blue; background-color: white; padding: 10px;">
                                                        <u>MONTO COMPROMETIDO:</u>
                                                        {{ number_format($contract->compro_amount, 0, ',', '.') }}
                                                    </div>
                                                    <div
                                                        style="flex: 1; text-align: center; font-size: 16px; font-weight: bold; color: red; background-color: white; padding: 10px;">
                                                        <u>MONTO UTILIZADO:</u> 0
                                                    </div>
                                                    <div
                                                        style="flex: 1; text-align: center; font-size: 16px; font-weight: bold; color: blue; background-color: white; padding: 10px;">
                                                        <u>SALDO DEL CONTRATO:</u> {{ $contract->totalAmountFormat() }}                                                        
                                                    </div>
                                                </div>                                                 --}}
                                            {{-- </div>                                             --}}
                                        </div>
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

            $('#items').DataTable();        
            

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

            //lleva a index de archivos
            itemFiles = function(order) {                                
                location.href = '/orders/' + order + '/files';
                // lleva a esta ruta Route::resource('orders.files', OrdersFilesController::class);
            }
            
            //lleva a indexRubros de ItemsContractsController
            // itemContraRubro = function(order, contract, component) {
            //     location.href = '/orders/' + order + '/items_contracts/' + contract + '/component/' + component + '/itemsRubros';
            // }

            //lleva a indexRubros de ItemsContractsController
            certiOrder = function(order, contract, component) {
                location.href = '/orders/' + order + '/items_contracts/' + contract + '/component/' + component + '/itemsCerti';
            }

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
