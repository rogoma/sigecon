@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-list bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Pedidos</h5>
                        {{-- <span>Listado de Pedidos</span> --}}
                        {{-- REPORTES PARA ADMINISTRADORES --}}
                        @if (Auth::user()->role_id == 1)
                            <a href="pdf/panel_uta" class="btn btn-primary" target="_blank">LLAMADOS EN CURSO</a>
                            <a href="pdf/panel_uta2" class="btn btn-primary" target="_blank">LLAMADOS ADJUDICADOS</a>                             
                            
                            {{-- @if (count($orders)>0)                             --}}
                                <a href="orders/exportarexcel" class="btn btn-success">EnCurso-Excel</a>
                                <a href="orders/exportarexcel2" class="btn btn-success">Adjudicados-Excel</a>                                                                 
                                <a href="pdf/panel_uta3" class="btn btn-primary" style="margin: 20px" target="_blank">Anulados</a>

                            {{-- @endif --}}
                        @else
                            {{-- REPORTE POR DEPENDENCIA --}}
                            <a href="pdf/panel_pedidos/{{ $dependency }}" class="btn btn-primary" target="_blank">LLAMADOS EN CURSO</a>
                            <a href="pdf/panel_pedidos2/{{ $dependency }}" class="btn btn-primary" target="_blank">LLAMADOS ADJUDICADOS</a>                            

                            {{-- @if (count($orders)>0)                             --}}
                                <a href="orders/exportarexcel" class="btn btn-success">EnCurso-Excel</a>
                                <a href="orders/exportarexcel2" class="btn btn-success">Adjudicados-Excel</a>
                                <a href="pdf/panel_uta3" class="btn btn-primary" style="margin: 20px" target="_blank">Anulados</a>
                            {{-- @endif --}}
                        @endif
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
                            <a href="{{ route('orders.index') }}">Pedidos/Llamados</a>
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
                                    <div class="float-left">
                                        <h5>Listado de Pedidos</h5>                                        
                                    </div>
                                    <div class="float-right">                                        
                                    {{-- Verificamos permisos de creación del usuario --}}
                                    @if (Auth::user()->hasPermission(['orders.orders.create', 'admin.orders.create']))
                                        <div class="float-center">
                                            <h5  style="color:blue">Modelos de Archivos Excel para Descargar y realizar importación de datos</h5>                                            
                                        </div>
                                        <a href="excel/pedidos" title="Descargar Modelo Pedido.xlsx" class="btn btn-danger" target="_blank">0-Pedidos</a>
                                        <a href="excel/items" title="Descargar Modelo Items.xlsx" class="btn btn-danger" target="_blank">1-Items Contrato Abierto</a>
                                        <a href="excel/items2" title="Descargar Modelo Items.xlsx" class="btn btn-danger" target="_blank">2-Items Contrato Cerrado</a>                                            
                                        <a href="excel/items3" title="Descargar Modelo Items.xlsx" class="btn btn-danger" target="_blank">3-Items Contrato Abierto MMin/MMáx</a>
                                        <a href="{{ route('orders.create') }}" title="Agregar pedido" class="btn btn-primary">Agregar pedido</a>
                                        <a href="{{ route('orders.uploadExcel')}}" title="Cargar Archivo EXCEL" class="btn btn-success btn-icon">
                                            <i class="fa fa-upload text-white"></i>                                            
                                        </a>                                                                                
                                    @endif
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="dt-responsive table-responsive">
                                        <table id="orders" class="table table-striped shadow-lg mt-4">                                        
                                            <thead class="bg-secondary text-white">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Modalidad</th>
                                                    <th>Descripción</th>
                                                    {{-- <th>Dependencia Solicitante</th> --}}
                                                    <th>SIMESE N°</th>                                                    
                                                    <th>Prioridad</th>
                                                    <th>Monto Total</th>
                                                    <th>PAC ID</th>
                                                    <th>Tipo Contrato</th>
                                                    <th>Estado</th>                                                    
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @for ($i = 0; $i < count($orders); $i++)
                                                <tr>
                                                    <td>{{ ($i+1) }}</td>                                                                                                        
                                                    <td>{{ $orders[$i]->modality->description }}</td>                                                   
                                                    @if ($orders[$i]->covid==0)                                                            
                                                        <td>{{ is_null($orders[$i]->number)? $orders[$i]->description : $orders[$i]->modality->code." N° ".$orders[$i]->number."/".$orders[$i]->year." - ".$orders[$i]->description }}</td>
                                                    @else                                                            
                                                        <td>{{ is_null($orders[$i]->number)? $orders[$i]->description : $orders[$i]->modality->code." N° ".$orders[$i]->number."/".$orders[$i]->year." - ".$orders[$i]->description}} - <span style="color:red;font-weight: bold"> (PROCESO COVID)</span></td>
                                                    @endif                                                    
                                                    {{-- <td>{{ is_null($orders[$i]->simese->first()) ? '' : number_format($orders[$i]->simese->last()['simese'],'0', ',','.') }}</td> --}}
                                                    <td>{{ is_null($orders[$i]->simese->first()) ? '' : number_format($orders[$i]->simese->first()['simese'],'0', ',','.')."/".$orders[$i]->simese->first()['year'] }}</td>

                                                        @if ($orders[$i]->urgency_state == "ALTA")
                                                            <td style="color:red;font-weight: bold">{{ $orders[$i]->urgency_state }}</td>
                                                        @else
                                                            @if ($orders[$i]->urgency_state == "MEDIA")
                                                                <td style="color:orange;font-weight: bold">{{ $orders[$i]->urgency_state }}</td>
                                                            @else
                                                                <td>{{ $orders[$i]->urgency_state }}</td>
                                                            @endif
                                                        @endif
                                                    {{-- <td>{{ $orders[$i]->expenditureObject->code }}</td> --}}
                                                    <td>{{ $orders[$i]->totalAmountFormat() }}</td>
                                                    <td>{{ $orders[$i]->dncpPacIdFormat() }}</td>
                                                        
                                                        @if ($orders[$i]->open_contract == 1)
                                                        {{-- <td style="color:orange;font-weight: bold">Contrato Cerrado</td> --}}
                                                            <td>Contrato Abierto</td>
                                                        @else
                                                            @if ($orders[$i]->open_contract == 2)
                                                                <td>Contrato Cerrado</td>
                                                            @else
                                                                <td>Contrato Abierto con Mmin y Mmáx</td>
                                                            @endif
                                                        @endif

                                                        @if (in_array($orders[$i]->orderState->id, [0,1]))                                                                                                                    
                                                            <td style="color:#ff0000">{{ $orders[$i]->orderState->id."-".$orders[$i]->orderState->description }}</td>
                                                        @else
                                                            <td>{{ $orders[$i]->orderState->id."-".$orders[$i]->orderState->description }}</td>
                                                        @endif
                                                        {{-- <td>{{ $orders[$i]->orderState->id."-".$orders[$i]->orderState->description }}</td> --}}
                                                    <td>
                                                        
                                                        
                                                        {{-- Preparamos botón rojo si Pedido está anulado --}}
                                                        @if ($orders[$i]->actual_state == 0)
                                                            <a href="{{ route('orders.show', $orders[$i]->id) }}" class="btn btn-danger">Anulado</a>
                                                        @endif

                                                        {{-- Preparamos botón rojo si Pedido está finalizado --}}
                                                        @if ($orders[$i]->actual_state == 90)
                                                            <a href="{{ route('orders.show', $orders[$i]->id) }}" class="btn btn-danger">Finalizado</a>
                                                        @else
                                                            {{-- Preparamos botón verde para Licitaciones --}}
                                                            @if ($orders[$i]->actual_state >= 25 && (in_array($orders[$i]->modality->id, [1,2,3,7,8,17,20])))
                                                                <a href="{{ route('orders.show', $orders[$i]->id) }}" class="btn btn-success">En Curso</a>                                                            
                                                            @else
                                                                {{-- Preparamos botón verde para Compras Menores --}}
                                                                @if ($orders[$i]->actual_state >= 45 && (in_array($orders[$i]->modality->id, [4,5,6])))
                                                                    <a href="{{ route('orders.show', $orders[$i]->id) }}" class="btn btn-success">En Curso</a>                                                            
                                                                @else
                                                                    {{-- Preparamos botón verde para Excepciones --}}
                                                                    @if ($orders[$i]->actual_state >= 55 && (in_array($orders[$i]->modality->id, [9,10])))
                                                                        <a href="{{ route('orders.show', $orders[$i]->id) }}" class="btn btn-success">En Curso</a>
                                                                    @else
                                                                        @if ($orders[$i]->actual_state > 0)
                                                                            <a href="{{ route('orders.show', $orders[$i]->id) }}" class="btn btn-primary">Ver Más</a>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </td> 
                                                </tr>
                                            @endfor
                                            </tbody>
                                        </table>
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
$(document).ready(function(){
    $('#orders').DataTable({
        "language":{
            "search":           "Buscar",
            "lengthMenu":       "Mostrar _MENU_ registros por página",
            "info":             "Mostrando página _PAGE_ de _PAGES_",
            "paginate":{
                    "previous": "Anterior",
                    "next":     "Siguiente",
                    "first":    "Primero",
                    "last":     "Último"
            }
        }
    });
});
</script>
@endpush