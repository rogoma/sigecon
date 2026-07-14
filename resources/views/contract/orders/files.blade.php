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
                        <h3>Listado de Archivos</h3>
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
                        - Orden N°: {{ $order->component_code }} - {{ $order->number }} </h4>                    
                   
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
                                                    <tr>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Descripción del Archivo</th>
                                                            <th>Fecha/Hora</th>                                                            
                                                            <th style="width: 200px; text-align: center;">Acciones</th>
                                                        </tr>                                                        
                                                    </tr>                                                    
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @for ($i = 0; $i < count($files); $i++)
                                                            <tr>
                                                                <td>{{ $i + 1 }}</td>
                                                                <td>{{ $files[$i]->description }}</td>                                                                
                                                                {{-- <td style="max-width: 500px"> {{ $files[$i]->user->name}} {{ $files[$i]->user->lastname }}</td> --}}

                                                                <td>{{ $files[$i]->updated_atDateFormat() }}</td>

                                                                <td>
                                                                    <a href="{{ route('orders.files.view', $files[$i]->id) }}"
                                                                        title="Ver Archivo" target="_blank"
                                                                        class="btn btn-primary"><i
                                                                            class="fa fa-eye"></i></a>
                                                                    <a href="{{ route('orders.files.download', $files[$i]->id) }}"
                                                                        title="Descargar Archivo" class="btn btn-info"><i
                                                                            class="fa-solid fa-download"></i></a>
                                                                    
                                                                    @if (Auth::user()->id == $order->creator_user_id)
                                                                            <button title="Eliminar Archivo"
                                                                        onclick="deleteFile({{ $files[$i]->id }})"
                                                                        class="btn btn-danger"><i
                                                                            class="fa fa-trash"></i></a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                        <br>
                                        <div class="text-right">
                                            @if (Auth::user()->hasPermission(['admin.orders.index', 'orders.files.index']))
                                                @if (Auth::user()->id == $order->creator_user_id)
                                                    <a href="{{ route('orders.files.create', $order->id) }}" class="btn btn-primary">Agregar Archivo</a>
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
    <script type="text/javascript">        

        deleteFile = function(file) {
            swal({
                    title: "Atención",
                    text: "Está seguro que desea eliminar el Archivo?",

                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sí, anular",
                    cancelButtonText: "Cancelar",
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: '/orders/files/' + file + '/delete/',
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
    </script>
@endpush
