@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/datatables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/buttons.datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/responsive.bootstrap4.min.css') }}">

    <style type="text/css">
    }
        .columna1 { width: 2%; text-align: center;}
        .columna2 { width: 20%; text-align: left;}
        .columna3 { width: 20%; text-align: left;}
        .columna4 { width: 4%; text-align: center;}
        .columna5 { width: 3%; text-align: center;}
        .columna6 { width: 8%; text-align: left;}
        .columna7 { width: 4%; text-align: center;}
        .columna8 { width: 4%; text-align: center;}
        .columna9 { width: 5%; text-align: left;}
        .columna10 { width: 5%; text-align: left;}
        .columna11 { width: 5%; text-align: center;}

        p.centrado {
            text-align: center;
    }
    </style>
@endpush

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Contratistas</h5>
                        <span>Listado de Contratistas</span>
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
                            <a href="{{ route('providers.index') }}">Contratistas</a>
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
                                        <h5>Listado de Contratistas</h5>
                                        <a href="pdf/pdfContratistas" class="btn btn-danger" target="_blank">Listado de Contratistas</a>
                                    </div>
                                    <div class="float-right">
                                        <a href="{{ route('providers.create') }}" class="btn btn-primary">Agregar Contratista</a>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="dt-responsive table-responsive">
                                        <table id="providers" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Contratista</th>
                                                    <th>Ruc</th>
                                                    <th>Teléfono</th>
                                                    <th>Email para Oferta</th>
                                                    <th>Email para Orden de Compra</th>
                                                    <th>Representante</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @for ($i = 0; $i < count($providers); $i++)
                                                <tr>
                                                    <td>{{ ($i+1) }}</td>
                                                    <td class="columna2">{{ $providers[$i]->description }}</td>
                                                    <td>{{ $providers[$i]->ruc }}</td>
                                                    <td>{{ $providers[$i]->telefono}}</td>
                                                    <td>{{ $providers[$i]->email_oferta }}</td>
                                                    <td>{{ $providers[$i]->email_ocompra }}</td>
                                                    <td>{{ $providers[$i]->representante }}</td>

                                                    <td style="white-space:nowrap">
                                                        <button type="button" title="Editar" class="btn btn-warning btn-icon" onclick="updateProvider({{ $providers[$i]->id }})">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                        <button type="button" title="Borrar" class="btn btn-danger btn-icon" onclick="deleteProvider({{ $providers[$i]->id }})">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
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
<script src="{{ asset('template-admin/js/jquery.datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('template-admin/js/datatables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('template-admin/js/datatables.bootstrap4.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('template-admin/js/datatables.responsive.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('#providers').DataTable();

    updateProvider = function(provider){
        location.href = '/providers/'+provider+'/edit/';
    }

    deleteProvider = function(provider){
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
              url : '/providers/'+provider,
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
