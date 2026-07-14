@extends('layouts.app')

@push('styles')
<style type="text/css">
.table td, .table th {
    padding: 0.2rem 0.5rem;
    font-size: 14px
}
.tab-content.card-block {
    padding: 1.25rem 0.5rem;
}
}
.columna1 { width: 1%; text-align: center;}
.columna2 { width: 10%; text-align: left;}
.columna3 { width: 9%; text-align: left;}
.columna4 { width: 16%; text-align: left;}
.columna5 { width: 2%; text-align: center;}
.columna6 { width: 4%; text-align: center;}
.columna7 { width: 4%; text-align: center;}
.columna8 { width: 3%; text-align: center;}
.columna9 { width: 3%; text-align: left;}
.columna10 { width: 3%; text-align: center;}
.columna11 { width: 9%; text-align: left;}
.columna12 { width: 10%; text-align: left;}

p.centrado {

}
</style>
@endpush

@section('content')
{{-- <div class="container"> --}}
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-list bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Visualizar Llamado</h5>
                        <span>Llamado Nº {{ $contract->number_year }}</span>
                        <br><br>
                        <h5><p style="font-size: 17px; font-weight: bold; color:#FF0000;">Estado Actual: {{ $contract->contractState->id." - ".$contract->contractState->description }}</p></h5>
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
                            <a href="{{ route('contracts.index') }}">Llamados</a>
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
                                            <h5>Llamado: {{ $contract->description." - ".$contract->modality->description." N° ".$contract->number_year." - ".$contract->provider->description }}</h5>
                                        </div>
                                        <div class="col-sm-10 text-left">
                                            <h5 style="font-size: 17px; font-weight: bold; color:blue">Dependencia Responsable: {{ $contract->dependency->description }}</h5>
                                        </div>
                                        <div class="col-sm-2">
                                                @if (Auth::user()->hasPermission(['admin.orders.create','admin.orders.update']))
                                                    {{-- @if (in_array($contract->contract_state_id, [1,2])) --}}
                                                        <button class="btn btn-primary dropdown-toggle waves-effect" type="button" id="acciones" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Acciones</button>
                                                    {{-- @endif --}}
                                                @endif

                                                <div class="dropdown-menu" aria-labelledby="acciones" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                    {{-- Verificamos permisos de edición del usuario --}}
                                                    @if ((Auth::user()->hasPermission(['contracts.contracts.update']) && $contract->contract_state_id >= 1) || Auth::user()->hasPermission(['admin.contracts.update']))
                                                        <a style="font-size: 14px; font-weight: bold; color:blue;background-color:lightblue;" class="dropdown-item waves-effect f-w-600" href="{{ route('contracts.edit', $contract->id)}}">Editar Llamado</a>
                                                    @endif

                                                    @if ((Auth::user()->hasPermission(['admin.contracts.delete'])) || Auth::user()->hasPermission(['contracts.contracts.delete']))
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
                                    <ul class="nav nav-tabs md-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#tab1" role="tab"><i class="fa fa-tasks"></i> Datos del Llamado</a>
                                            <div class="slide"></div>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#tab3" role="tab"><i class="fa fa-list"></i> Pólizas</a>
                                            <div class="slide"></div>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#tab4" role="tab"><i class="fa-solid fa-file-pdf"></i> Reportes</a>
                                            <div class="slide"></div>
                                        </li>
                                        {{-- <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#tab5" role="tab"><i class="fa fa-folder-open-o"></i> Archivos de Pólizas</a>
                                            <div class="slide"></div>
                                        </li> --}}
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#tab6" role="tab"><i class="fa fa-file-archive-o"></i> Archivos del Contrato</a>
                                            <div class="slide"></div>
                                        </li>
                                    </ul>

                                    <div class="tab-content card-block">
                                        <div class="tab-pane active" id="tab1" role="tabpanel">
                                            <h5 class="text-center">Datos del LLamado</h5>
                                            <table class="table table-striped table-bcontracted">
                                                <tbody>
                                                    <tr>
                                                        <td><label class="col-form-label f-w-600" >Nombre del Llamado:</label></td>
                                                        <td><label class="col-form-label f-w-600" >Tipo Llamado:</label></td>
                                                        <td><label class="col-form-label f-w-600">IDDNCP:</label></td>
                                                        <td><label class="col-form-label f-w-600">Link DNCP:</label></td>
                                                        <td><label class="col-form-label f-w-600">N° Contrato/Año:</label></td>
                                                        <td><label class="col-form-label f-w-600">AÑO:</label></td>
                                                        <td><label class="col-form-label f-w-600">Fecha firma contrato:</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ $contract->description }}</td>
                                                        <td>{{ $contract->modality->description }}</td>
                                                        <td> {{ number_format($contract->iddncp,'0', ',','.') }} </td>
                                                        <td style="color:blue">{{ $contract->linkdncp }}</td>
                                                        <td>{{ $contract->number_year }}</td>
                                                        <td> {{ number_format($contract->year_adj,'0', ',','.') }} </td>
                                                        <td>{{ $contract->signDateFormat() }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label class="col-form-label f-w-600">Contratista:</label></td>
                                                        <td><label class="col-form-label f-w-600">Estado:</label></td>
                                                        <td><label class="col-form-label f-w-600">Modalidad:</label></td>
                                                        <td><label class="col-form-label f-w-600">Organismo Finaciador:</label></td>
                                                        <td><label class="col-form-label f-w-600">Tipo de Contrato:</label></td>
                                                        <td><label class="col-form-label f-w-500">Monto Total:</label></td>
                                                        {{-- <td><label class="col-form-label f-w-600">Dependencia Responsable:</label></td> --}}
                                                    </tr>
                                                    <tr>
                                                        <td>{{ $contract->provider->description }}</td>

                                                        {{-- SI ESTADO ESTA ANULADO, RESCINDIDO O CANCELADO --}}
                                                        @if (in_array($contract->contract_state_id, [2,3,4,6]))
                                                            <td style="color:#ff0000">{{ $contract->contractState->description }}</td>
                                                        @else
                                                            <td style="color:green">{{ $contract->contractState->description }}</td>
                                                        @endif

                                                        <td>{{ $contract->modality->description }}</td>
                                                        <td>{{ $contract->financialOrganism->description }}</td>
                                                        <td>{{ $contract->contractType->description }}</td>
                                                        <td colspan="3" style="font-size: 16px;color:blue;font-weight: bold">{{ 'Gs. '.$contract-> totalAmountFormat() }}</td>
                                                        {{-- <td>{{ $contract->dependency->description }}</td> --}}
                                                        {{-- <td>{{ $contract->comments }}</td> --}}
                                                    </tr>
                                                    <tr>
                                                        <td><label class="col-form-label f-w-600">Dependencia Responsable:</label></td>
                                                        <td><label class="col-form-label f-w-1600">Comentarios:</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ $contract->dependency->description }}</td>
                                                        <td>{{ $contract->comments }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="tab-pane" id="tab3" role="tabpanel">
                                            <table id="items" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Póliza</th>
                                                            <th>N° de Póliza</th>
                                                            <th>Vigencia Desde</th>
                                                            <th>Vigencia Hasta</th>
                                                            <th>Monto</th>
                                                            <th>Comentarios</th>
                                                            <th>Acciones</th>
                                                            <th>Status</th>
                                                            <th>Archivo</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @for ($i = 0; $i < count($contract->items); $i++)
                                                            <tr>
                                                                <td>{{ ($i+1) }}</td>
                                                                <td>{{ $contract->items[$i]->policy->description }}</td>
                                                                <td>{{ $contract->items[$i]->number_policy }}</td>
                                                                <td>{{ $contract->items[$i]->itemFromDateFormat() }}</td>

                                                                {{-- Se calcula 60 días antes para mostrar colores y botón de Endoso --}}
                                                                @php
                                                                    // Ajusta el formato para que coincida con tu cadena de fecha
                                                                    $toDate = \Carbon\Carbon::createFromFormat('d/m/Y', $contract->items[$i]->itemToDateFormat());
                                                                    $currentDate = \Carbon\Carbon::now();
                                                                    $sixtyDaysBefore = $toDate->copy()->subDays(60);  // Resta 60 días a $toDate
                                                                    // var_dump($sixtyDaysBefore);exit;
                                                                @endphp

                                                                @if ($currentDate <= $sixtyDaysBefore)
                                                                    <td style="color:blue;font-weight">{{ $contract->items[$i]->itemToDateFormat() }}</td>
                                                                @else
                                                                    <td style="color:red;font-weight">{{ $contract->items[$i]->itemToDateFormat() }}</td>
                                                                @endif

                                                                <td>{{ $contract->items[$i]->AmountFormat()}} </td>
                                                                <td>{{ $contract->items[$i]->comments }}</td>
                                                                <td>
                                                                {{-- No muestra si estado de llamado no es rescindido, cerrado, impugando o en proceso de rescisión --}}
                                                                @if (in_array($contract->contract_state_id, [1,5]))
                                                                    @if (Auth::user()->hasPermission(['admin.items.update','contracts.items.update']))
                                                                        <button type="button" title="Editar" class="btn btn-warning btn-icon" onclick="updateItem({{ $contract->items[$i]->id }})">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </button>
                                                                    @endif
                                                                    {{-- @if (Auth::user()->hasPermission(['admin.items.delete','contracts.items.delete']) || $contract->dependency_id == Auth::user()->dependency_id) --}}
                                                                    @if (Auth::user()->hasPermission(['admin.items.delete','contracts.items.delete']))
                                                                        <button type="button" title="Borrar" class="btn btn-danger btn-icon" onclick="deleteItem({{ $contract->items[$i]->id }})">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    @endif
                                                                @endif

                                                                @if ($currentDate <= $sixtyDaysBefore)
                                                                    <td style="color:BLUE;font-weight">OK</td>
                                                                @else
                                                                    @if (Auth::user()->hasPermission(['admin.items.update','contracts.items.update']) || $contract->dependency_id == Auth::user()->dependency_id)
                                                                    {{-- @if (Auth::user()->hasPermission(['admin.items.update','contracts.items.update'])) --}}
                                                                        <button type="button" title="Endosos de Póliza" class="btn btn-primary btn-icon" onclick="itemAwardHistories({{ $contract->items[$i]->id }})">
                                                                            <i class="fa fa-list"></i>
                                                                        </button>
                                                                    @endif
                                                                    <td style="color:red;font-weight">ALERTA</td>
                                                                @endif
                                                                </td>
                                                                <td>
                                                                    <a href="{{ route('items.view', $contract->items[$i]->id) }}" title="Ver Archivo" target="_blank" class="btn btn-success btn-icon"><i class="fa fa-eye"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endfor

                                                    </tbody>
                                            </table>

                                                <div class="text-right">
                                                    {{-- @if (Auth::user()->hasPermission(['contracts.contracts.create','admin.orders.create'])) --}}
                                                    @if (Auth::user()->hasPermission(['admin.orders.create']))
                                                        {{-- Si pedido está anulado no muestra agregar ítems --}}
                                                        @if (in_array($contract->contract_state_id, [1]))
                                                            <a href="{{ route('contracts.items.create', $contract->id) }}" class="btn btn-primary">Agregar Póliza</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            <span style="font-size: 16px; font-weight: bold; color:red;background-color:yellow;" >MONTO TOTAL DEL LLAMADO: {{ $contract->totalAmountFormat() }}</span>
                                        </div>

                                        <div class="tab-pane" id="tab4" role="tabpanel">
                                            <table id="forms" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Reporte</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- MUESTRA LINK DE RFEPORTE SI ESTA CARGADO ITEMS (POLIZAS) --}}
                                                    @if($contract->items->count() > 0)
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Reporte Pólizas del Llamado</td>
                                                            <td><a href="/pdf/panel_contracts/{{ $contract->id }}" class="btn btn-default" target="_blank"><i class="fa-solid fa-file-pdf"></i> &nbsp;Informe de Pólizas</a></td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="tab-pane" id="tab5" role="tabpanel">
                                            <label class="col-form-label f-w-600">Archivos de pólizas cargados al llamado:</label>
                                            <table class="table table-striped table-bcontracted">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Descripción</th>
                                                        <th>Dependencia</th>
                                                        <th>Fecha/Hora</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                        @for ($i=0; $i < count($user_files_pol); $i++)
                                                        <tr>
                                                            <td>{{ $i+1 }}</td>
                                                            <td>{{ $user_files_pol[$i]->description }}</td>
                                                            <td>{{ $user_files_pol[$i]->dependency->description }}</td>
                                                            <td>{{ $user_files_pol[$i]->updated_atDateFormat() }}</td>
                                                            <td>
                                                                <a href="{{ route('contracts.files.view', $user_files_pol[$i]->id) }}" title="Ver Archivo" target="_blank" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                                                                <a href="{{ route('contracts.files.download', $user_files_pol[$i]->id) }}" title="Descargar Archivo" class="btn btn-info"><i class="fa-solid fa-download"></i></a>
                                                                <button title="Eliminar Archivo" onclick="deleteFile({{ $user_files_pol[$i]->id }})" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                                            </td>
                                                        </tr>
                                                        @endfor

                                                        @for ($i=0; $i < count($other_files_pol); $i++)
                                                        <tr>
                                                            <td>{{ $i+1 }}</td>
                                                            <td>{{ $other_files_pol[$i]->description }}</td>
                                                            <td>{{ $other_files_pol[$i]->dependency->description }}</td>
                                                            <td>{{ $other_files_pol[$i]->updated_atDateFormat() }}</td>
                                                            <td>
                                                                <a href="{{ route('contracts.files.view', $other_files_pol[$i]->id) }}" title="Ver Archivo" target="_blank" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                                                                <a href="{{ route('contracts.files.download', $other_files_pol[$i]->id) }}" title="Descargar Archivo" class="btn btn-info"><i class="fa-solid fa-download"></i></a>
                                                        </td>
                                                    </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                            <div class="text-right">
                                                @if (Auth::user()->hasPermission(['admin.files.create','contracts.files.create']))
                                                    @if (in_array($contract->contract_state_id, [1,2]))
                                                        <a href="{{ route('contracts.files.create', $contract->id) }}" class="btn btn-danger">Cargar Pólizas</a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="tab6" role="tabpanel">
                                                <label class="col-form-label f-w-600">Archivos de contratos cargados al llamado:</label>
                                                <table class="table table-striped table-bcontracted">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Descripción</th>
                                                            <th>Archivo generado por:</th>
                                                            <th>Fecha/Hora</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                            @for ($i=0; $i < count($user_files_con); $i++)
                                                            <tr>
                                                                <td>{{ $i+1 }}</td>
                                                                <td>{{ $user_files_con[$i]->description }}</td>
                                                                <td>{{ $user_files_con[$i]->dependency->description }}</td>
                                                                <td>{{ $user_files_con[$i]->updated_atDateFormat() }}</td>
                                                                <td>
                                                                    <a href="{{ route('contracts.files.view', $user_files_con[$i]->id) }}" title="Ver Archivo" target="_blank" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                                                                    <a href="{{ route('contracts.files.download', $user_files_con[$i]->id) }}" title="Descargar Archivo" class="btn btn-info"><i class="fa-solid fa-download"></i></a>
                                                                    <button title="Eliminar Archivo" onclick="deleteFile({{ $user_files_con[$i]->id }})" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                                                </td>
                                                            </tr>
                                                            @endfor

                                                            @for ($i=0; $i < count($other_files_con); $i++)
                                                            <tr>
                                                                <td>{{ $i+1 }}</td>
                                                                <td>{{ $other_files_con[$i]->description }}</td>
                                                                <td>{{ $other_files_con[$i]->dependency->description }}</td>
                                                                <td>{{ $other_files_con[$i]->updated_atDateFormat() }}</td>
                                                                <td>
                                                                    <a href="{{ route('contracts.files.view', $other_files_con[$i]->id) }}" title="Ver Archivo" target="_blank" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                                                                    <a href="{{ route('contracts.files.download', $other_files_con[$i]->id) }}" title="Descargar Archivo" class="btn btn-info"><i class="fa-solid fa-download"></i></a>
                                                            </td>
                                                        </tr>
                                                        @endfor
                                                    </tbody>
                                                </table>
                                                <div class="text-right">
                                                    @if (Auth::user()->hasPermission(['admin.orders.create', 'contracts.orders.create']))
                                                        @if (in_array($contract->contract_state_id, [1,2]))
                                                            <a href="{{ route('contracts.files.create_con', $contract->id) }}" class="btn btn-primary">Cargar Contratos</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>

                            {{-- MOSTRAMOS TIMELINE --}}
                            {{-- <div class="card latest-update-card">
                                <div class="card-header">
                                    <h5 style="font-size: 14px; font-weight: bold; color:red" class="text-left">Movimientos del pedido</h5>
                                </div>
                                <div class="card-block">
                                    <div class="latest-update-box">
                                    @foreach ($contract->contractscontractstates()->contractBy('id', 'desc')->get() as $item)
                                        <div class="row p-t-20 p-b-30 borde-alternado">
                                            <div class="col-auto text-right update-meta p-r-0">
                                                <i class="update-icon ring"></i>
                                            </div>
                                            <div class="col p-l-5">
                                                <a href="javascript:void(0);">
                                                    <h6 style="font-size: 14px; font-weight: bold; color:Black">{{ $item->contractstate->id}}-{{ $item->contractstate->description}}</h6>
                                                </a>
                                                <p style="font-size: 14px; font-weight: bold; color:Black">{{ $item->creatorUser->getFullName()}}</p>
                                                <small style="font-size: 13px; font-weight: bold; color:Black">Fecha: {{ $item->createdAtDateFormat()}}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                    </div>
                                </div>
                            </div> --}}
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


    updateItem = function(item){
        //llamar a Función JS que está en H:\Proyectos\sistedoc\public\js\guardar-tab.js
        // persistirTab();
        location.href = '/contracts/{{ $contract->id }}/items/'+item+'/edit/';
    }

    deleteItem = function(item){
      swal({
            title: "Atención",
            text: "Está seguro que desea eliminar la póliza?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
              url : '/contracts/{{ $contract->id }}/items/'+item,

              method : 'POST',
              data: {_method: 'DELETE', _token: '{{ csrf_token() }}'},
              success: function(data){
                try{
                    response = (typeof data == "object") ? data : JSON.parse(data);
                    if(response.status == "success"){
                        swal("Éxito!", "Póliza eliminada correctamente", "success");
                        location.reload();
                    }else{
                        swal("Error!", response.message, "error");
                    }
                }catch(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
              },
              error: function(error){
                swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                console.log(error);
              }
            });
          }
        }
      );
    };


    updateContracts = function(budget){
        // persistirTab();
        location.href = '/contracts/{{ $contract->id }}/items_budget/'+budget+'/edit/';
    }

    recibecontract = function(contract_id){
        $.ajax({
            url : '/contracts/recibe_contract/'+contract_id,
            method: 'POST',
            data: '_token='+'{{ csrf_token() }}',
            success: function(data){
                try{
                    response = (typeof data == "object") ? data : JSON.parse(data);
                    if(response.status == "success"){
                        swal({
                            title: "Éxito!",
                            text: response.message,
                            type: "success"
                        },
                        function(isConfirm){
                            location.reload();
                        });
                    }else{
                        swal("Error!", response.message, "error");
                    }
                }catch(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
              },
              error: function(error){
                swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                console.log(error);
              }
        });
    }

    derivecontract = function(contract_id){
        $.ajax({
            url : '/contracts/derive_contract/'+contract_id,
            method: 'POST',
            data: '_token='+'{{ csrf_token() }}',
            success: function(data){
                try{
                    response = (typeof data == "object") ? data : JSON.parse(data);
                    if(response.status == "success"){
                        swal({
                            title: "Éxito!",
                            text: response.message,
                            type: "success"
                        },
                        function(isConfirm){
                            location.reload();
                        });
                    }else{
                        swal("Error!", response.message, "error");
                    }
                }catch(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
              },
              error: function(error){
                swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                console.log(error);
              }
        });
    }

    itemAwardHistories = function(item){
        //lleva a itemawardhistories index
        location.href = '/items/'+item+'/item_award_histories';
    }

    deleteContract = function(id){
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
                    url: '{{ route("contracts.delete", ["contract_id" => ":id"]) }}'.replace(':id', id),
                    method: 'POST',
                    data: {_method: 'DELETE', _token: '{{ csrf_token() }}'},
                    success: function(data) {
                        try {
                            response = (typeof data == "object") ? data : JSON.parse(data);
                            if (response.status == "success") {
                                location.reload();
                            } else {
                                swal("Error!", response.message, "error");
                            }
                        } catch (error) {
                            swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                            console.log(error);
                        }
                    },
                    error: function(error) {
                        swal("Error!", "Ocurrió 1 un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                        console.log(error);
                    }
                });
            }
        });
    }

    // deleteContract = function(id){
    //   swal({
    //         title: "Atención",
    //         text: "Está seguro que desea eliminar el llamado?",

    //         type: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: "#DD6B55",
    //         confirmButtonText: "Sí, eliminar",
    //         cancelButtonText: "Cancelar",
    //     },
    //     function(isConfirm){
    //       if(isConfirm){
    //         $.ajax({
    //         //   url : '/contracts/contract/'+id+'/delete/',
    //           url : '{{ route("contracts.delete", ["contract_id" => ":id"]) }}'.replace(':id', id),
    //           method : 'POST',
    //           data: {_method: 'DELETE', _token: '{{ csrf_token() }}'},
    //           success: function(data){
    //             try{
    //                 response = (typeof data == "object") ? data : JSON.parse(data);
    //                 if(response.status == "success"){
    //                     location.reload();
    //                 }else{
    //                     swal("Error!", response.message, "error");
    //                 }
    //             }catch(error){
    //                 swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
    //                 console.log(error);
    //             }
    //           },
    //           error: function(error){
    //             swal("Error!", "Ocurrió 1 error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
    //             console.log(error);
    //           }
    //         });
    //       }
    //     }
    //   );
    // };

    deleteFile = function(file){
      swal({
            title: "Atención",
            text: "Está seguro que desea eliminar el registro?",

            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
              url : '/contracts/files/'+file+'/delete/',
              method : 'POST',
              data: {_method: 'DELETE', _token: '{{ csrf_token() }}'},
              success: function(data){
                try{
                    response = (typeof data == "object") ? data : JSON.parse(data);
                    if(response.status == "success"){
                        location.reload();
                    }else{
                        swal("Error!", response.message, "error");
                    }
                }catch(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
              },
              error: function(error){
                swal("Error!", "Ocurrió 1 error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                console.log(error);
              }
            });
          }
        }
      );
    };

    deleteObjection = function(objection){
      swal({
            title: "Atención",
            text: "Está seguro que desea eliminar el registro?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
              url : '/contracts/{{ $contract->id }}/objections/'+objection,
              method : 'POST',
              data: {_method: 'DELETE', _token: '{{ csrf_token() }}'},
              success: function(data){
                try{
                    response = (typeof data == "object") ? data : JSON.parse(data);
                    if(response.status == "success"){
                        location.reload();
                    }else{
                        swal("Error!", response.message, "error");
                    }
                }catch(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
              },
              error: function(error){
                swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                console.log(error);
              }
            });
          }
        }
      );
    };

    deleteObjectionResponse = function(objection, objection_response){
      swal({
            title: "Atención",
            text: "Está seguro que desea eliminar el registro?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
              url : '/contracts/'+objection+'/objections_responses/'+objection_response,
              method : 'POST',
              data: {_method: 'DELETE', _token: '{{ csrf_token() }}'},
              success: function(data){
                try{
                    response = (typeof data == "object") ? data : JSON.parse(data);
                    if(response.status == "success"){
                        location.reload();
                    }else{
                        swal("Error!", response.message, "error");
                    }
                }catch(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
              },
              error: function(error){
                swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
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
