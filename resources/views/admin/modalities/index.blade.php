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
                        <h5>Modalidades</h5>
                        <span>Listado de Modalidades</span>                        
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
                            <a href="{{ route('modalities.index') }}">Modalidades</a>
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
                                        <!-- <h5>Listado de Modalidades</h5> -->
                                        <a href="pdf/modalities" class="btn btn-primary" target="_blank">Listado PDF_DNCPs</a>
                                        <a href="pdf/modalities2" class="btn btn-primary" target="_blank">Listado de Modalidades-BD</a>                                        
                                    </div>
                                    
                                    <div class="float-right">
                                        <a href="{{ route('modalities.create') }}" class="btn btn-primary">Agregar Modalidad</a>  
                                    </div>
                                    <!-- <div class="float-left">
                                        <a href="pdf/modalities" class="btn btn-primary" target="_blank">Listado de Modalidades</a>
                                    </div>                                     -->
                                    
                                </div>
                                <div class="card-block">
                                    <div class="dt-responsive table-responsive">
                                        <table id="modalities" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Modalidad</th>
                                                    <th>Código</th>
                                                    <th>Tipo de Modalidad</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @for ($i = 0; $i < count($modalities); $i++)
                                                <tr>
                                                    <td>{{ ($i+1) }}</td>
                                                    <td>{{ $modalities[$i]->description }}</td>
                                                    <td>{{ $modalities[$i]->code }}</td>
                                                    <td>{{ $modalities[$i]->modality_type }}</td>
                                                    <td>
                                                        <button type="button" title="Editar" class="btn btn-warning btn-icon" onclick="updateModality({{ $modalities[$i]->id }})">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                        <button type="button" title="Borrar" class="btn btn-danger btn-icon" onclick="deleteModality({{ $modalities[$i]->id }})">
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
    <!-- <a href="pdf/reporte5" class="dropdown-item waves-light waves-effect" target="_blank"><i class="fa-solid fa-file-pdf"></i> &nbsp;Formulario 1</a><div class="dropdown-divider"></div> -->
    <!-- <div class="col-sm-12 p-b-20 p-l-20 m-l-20">
        <a href="pdf/modalities" class="btn btn-primary" target="_blank">Listado de Modalidades</a>
    </div> -->
</div>
@endsection

@push('scripts')
<script src="{{ asset('template-admin/js/jquery.datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('template-admin/js/datatables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('template-admin/js/datatables.bootstrap4.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('template-admin/js/datatables.responsive.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('#modalities').DataTable();

    updateModality = function(modality){
        location.href = '/modalities/'+modality+'/edit/';
    }

    deleteModality = function(modality){
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
              url : '/modalities/'+modality,
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