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
                        <h5>Pedido Nº {{ $item->order_id }}</h5>
                        <span>Listado de Precios Referenciales</span>
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
                            <a href="{{ $orders_route }}">Pedido Nº {{ $item->order_id }}</a>
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
                                        {{-- <h5>Listado Precios Referenciales del Ítem Nro {{ $item->item_number }}</h5> --}}
                                        <h5>Producto {{ $item->level5_catalog_code->description }}</h5>
                                    </div>
                                    <div class="float-right">
                                    {{-- En caso de no tener precios referenciales relacionados--}}
                                    @if($item_award_histories->count() == 0)
                                        @if (Auth::user()->hasPermission(['admin.item_award_histories.create', 'plannings.item_award_histories.create']) || $item->order->dependency_id == Auth::user()->dependency_id)
                                            <a href="{{ route('item_award_histories.create', $item->id) }}" class="btn btn-primary">Agregar</a>
                                        @endif
                                    @else
                                        @if (Auth::user()->hasPermission(['admin.item_award_histories.update', 'plannings.item_award_histories.update']) || $item->order->dependency_id == Auth::user()->dependency_id)
                                            <a href="{{ route('item_award_histories.edit', $item->id) }}" class="btn btn-warning">Editar</a>
                                        @endif
                                        @if (Auth::user()->hasPermission(['admin.item_award_histories.delete']) || $item->order->dependency_id == Auth::user()->dependency_id)
                                            <button type="button" title="Borrar" class="btn btn-danger" onclick="deleteItemAwardHistories({{ $item->id }})">
                                                Borrar
                                            </button>
                                        @endif
                                    @endif
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="dt-responsive table-responsive">
                                        <table id="item_award_histories" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Ítem</th>
                                                    <th>DNCP PAC ID</th>
                                                    <th>Proveedor</th>
                                                    <th>Monto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @for ($i = 0; $i < count($item_award_histories); $i++)
                                                <tr>
                                                    <td>{{ ($i+1) }}</td>
                                                    <td>{{ $item_award_histories[$i]->item->item_number }}</td>
                                                    <td>{{ $item_award_histories[$i]->dncpPacIdFormat() }}</td>
                                                    <td>{{ $item_award_histories[$i]->provider() }}</td>
                                                    <td>{{ 'Gs. '.$item_award_histories[$i]->amountFormat() }}</td>
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
<script src="{{ asset('template-admin/js/jquery.datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('template-admin/js/datatables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('template-admin/js/datatables.bootstrap4.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('template-admin/js/datatables.responsive.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('#item_award_histories').DataTable();

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
              url : '/items/'+item_id+'/item_award_histories',
              method : 'POST',
              data: {_method: 'DELETE', _token: '{{ csrf_token() }}'},
              success: function(data){
                try{
                    response = (typeof data == "object") ? data : JSON.parse(data);
                    if(response.status == "success"){
                        location.href = "{{ route('orders.show', $item->order->id) }}";
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
