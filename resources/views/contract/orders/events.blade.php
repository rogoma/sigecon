@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/datatables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/buttons.datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h3>Listado de Eventos</h3>
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
                            <a href="{{ route('contracts.show', $contract->id) }}">Contrato</a>
                            {{-- <a>Póliza Nº {{ $item->contract_id }}</a> --}}
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
                    <h4>Contratista: {{$contract->provider->description }} - Localidad: {{ $order->locality->description }} 
                        {{-- - SubComponente: {{ $order->component_id->components->description }} --}}
                        {{-- - Componente: {{ $order->component_code->component->description }} --}}
                        - Orden N°: {{ $order->component_code }} - {{ $order->number }} </h4>
                    
                    {{-- <h5>Póliza: {{ $item->policy->description }} - N°: {{ $item->number_policy }}</h5> --}}
                    {{-- <h3 style="font-size: 20px;color: blue;">Póliza: {{ $item->policy->description }} - N°: {{ $item->number_policy }} - Gs.: {{ $item->amountFormat() }}</h3> --}}

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="float-left">
                                        {{-- <h5>Listado Precios Referenciales del Ítem Nro {{ $item->item_number }}</h5> --}}
                                        {{-- <h5>Producto {{ $item->level5_catalog_code->description }}</h5> --}}
                                    </div>
                                    <div class="float-right">

                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="dt-responsive table-responsive">
                                        <table id="item_award_histories" class="table table-striped table-bcontracted nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tipo de Evento</th>                                                    
                                                    <th>Fecha Evento</th>
                                                    <th>Dias Prórroga</th>
                                                    <th>Fecha nuevo Plazo</th>
                                                    <th>Comentarios</th>
                                                    <th>Acciones</th>
                                                    <th>Archivo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                            
                                            @php
                                                use Carbon\Carbon;
                                                $fechaInicio = Carbon::parse($order->sign_date);
                                                $fechaFin = $fechaInicio->copy()->addDays($order->plazo);
                                            @endphp

                                            @for ($i = 0; $i < count($events); $i++)
                                                <tr>
                                                    <td>{{ ($i+1) }}</td>
                                                    <td>{{ $events[$i]->eventType->description }}</td>                                                    
                                                    <td>{{ $events[$i]->eventDateFormat() }}</td>
                                                    <td>{{ $events[$i]->event_days }}</td>
                                                    <td>{{ $events[$i]->eventDateFinFormat() }}</td>
                                                    <td>{{ $events[$i]->comments }}</td>

                                                    {{-- SI ORDEN ESTA FINALIZADA --}}
                                                    @if ($order->order_state_id == 4)
                                                        <td></td>
                                                        <td>                                                            
                                                            <a href="{{ route('events.view', $order->events[$i]->id) }}" title="Ver Archivo" target="_blank" class="btn btn-success btn-icon"><i class="fa fa-eye"></i></a>
                                                        </td>
                                                    @else
                                                        <td>
                                                            @if (Auth::user()->hasPermission(['admin.orders.index', 'orders.orders.index']))
                                                                <button type="button" title="Editar" class="btn btn-warning btn-icon">  
                                                                {{-- <button type="button" title="Editar" class="btn btn-warning btn-icon" onclick="updateEvent({{ $events[$i]->id }})"> --}}
                                                                    <i class="fa fa-pencil"></i>
                                                                </button>
                                                            @endif

                                                            @if (Auth::user()->hasPermission(['admin.orders.index', 'orders.orders.index']))
                                                            <button type="button" title="Borrar" class="btn btn-danger btn-icon">
                                                                {{-- <button type="button" title="Borrar" class="btn btn-danger btn-icon" onclick="deleteItemAwardHistories({{$item->itemAwardHistories[$i]->id }})"> --}}
                                                                <i class="fa fa-trash"></i>
                                                                </button>
                                                            @endif
                                                        </td>                                                        
                                                    <td>                                                            
                                                        <a href="{{ route('events.view', $order->events[$i]->id) }}" title="Ver Archivo" target="_blank" class="btn btn-success btn-icon"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endfor
                                            </tbody>
                                        </table>
                                        <br>
                                        <div class="text-right">
                                            @if (Auth::user()->hasPermission(['admin.orders.index', 'orders.orders.index']))
                                                {{-- Si pedido está anulado no muestra agregar ítems --}}
                                                {{-- @if (in_array($contract->contract_state_id, [1])) --}}
                                                
                                                {{-- SI ORDEN ESTA FINALIZADA --}}
                                                @if ($order->order_state_id == 4)
                                                
                                                @else
                                                    <a href="{{ route('orders.events.create', $order->id) }}" class="btn btn-primary">Agregar Evento</a>
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
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('template-admin/js/jquery.datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('template-admin/js/datatables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('template-admin/js/datatables.bootstrap4.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('template-admin/js/datatables.responsive.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('#item_award_histories').DataTable();

    updateEvent = function(event){       
        location.href = '/orders/events/'+event+'/edit';       
    }

    deleteItemAwardHistories = function(item_id){
      swal({
            title: "Atención",
            text: "Está seguro que desea eliminar los registros?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
           

              method : 'POST',
              data: {_method: 'DELETE', _token: '{{ csrf_token() }}'},
              success: function(data){
                try{
                    response = (typeof data == "object") ? data : JSON.parse(data);
                    if(response.status == "success"){
                        swal("Éxito!", "Endoso eliminado correctamente", "success");
                        location.reload();
                    }else{
                        swal("Error!", response.message, "error");
                    }
                }catch(error){
                    swal("Error!", "Ocurrió1 un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
              },
              error: function(error){
                swal("Error!", "Ocurrió2 un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
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
