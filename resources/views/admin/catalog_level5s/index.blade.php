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
                        <h5>Cátalogo Nivel 5</h5>
                        <span>Listado de Catálogo Nivel 5</span>
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
                            <a href="{{ route('catalog_level5s.index') }}">Cátalogo Nivel 5</a>
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
                                        <h5>Listado de Cátalogo Nivel 5</h5>
                                    </div>
                                    <div class="float-right">
                                        <a href="{{ route('catalog_level5s.create') }}" class="btn btn-primary">Agregar Código Cátalogo Nivel 5</a>  
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="dt-responsive table-responsive">
                                        <table id="catalog_level5s" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>                    
                                                    <th width="50px" style="color:red;font-weight: bold">Código</th>
                                                    <th width="120px" style="color:red;font-weight: bold">Descripción</th>
                                                    <th width="50px" style="color:red;font-weight: bold">Código</th>
                                                    <th width="180px" style="color:red;font-weight: bold">Descripción</th>
                                                    <th width="100px" style="color:red;font-weight: bold">Acciones</th>                        
                                                </tr>
                                            </thead>                                            
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

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#catalog_level5s').DataTable({
                    "serverSide": true,                   
                    "ajax": "{{ url('api/catalogs') }}",
                    "columns": [                        
                        
                        //CODE CATALOG5 CON VISTA
                        {data: 'code4'},
                        {data: 'descrip_c4'},
                        {data: 'code5'},
                        {data: 'descrip_c5'},   
                        {data: 'btn'},
                    ],
                    "language": {
                        "info": "_TOTAL_ registros",
                        "search": "Buscar",
                        "paginate": {
                            "next": "Siguiente",
                            "previous": "Anterior",
                        },
                        "lengthMenu": 'Mostrar <select >'+
                                    '<option value="10">10</option>'+
                                    '<option value="30">30</option>'+
                                    '<option value="-1">Todos</option>'+
                                    '</select> registros',
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "emptyTable": "No hay datos",
                        "zeroRecords": "No hay coincidencias", 
                        "infoEmpty": "",
                        "infoFiltered": ""
                    }
                });
            });

    updateCatalog = function(catalog){      
      location.href = '/catalog_level5s/'+catalog+'/edit_c5/';
    }

    deleteCatalog = function(catalog){
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
              url : '/catalog_level5s/'+catalog,
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
                    swal("Error1!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
              },
              error: function(error){
                swal("Error2!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                console.log(error);
              }
            });
          }
        }
      );
    };    
</script>   








{{-- @extends('layouts.app')
@push('styles')
    
@endpush

<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">        

        <title>SISTEDOC</title>
    </head>    
    
    @section('content')  
    <div class="page-header-title">
        <i class="fa fa-sitemap bg-c-blue"></i>
        <div class="d-inline">
            <h5>Cátalogo Nivel 5</h5>
            <span>Listado de Catálogo Nivel 5</span>
        </div>
    </div>  
    <div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <i class="fa fa-sitemap bg-c-blue"></i>
                <div class="d-inline">
                  
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
                        <a href="{{ route('catalog_level5s.index') }}">Cátalogo Nivel 5</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container">
            <div class="card-header">
                <div class="float-left">
                    <h5>Listado de Cátalogo Nivel 5</h5>
                </div>
                <div class="float-right">
                    <a href="{{ route('catalog_level5s.create') }}" class="btn btn-primary">Agregar Código Cátalogo Nivel 5</a>  
                </div>
            </div>
            <br><br>
            <table id="users" class="table">
                <thead>
                    <tr>                    
                        <th width="50px" style="color:red;font-weight: bold">Código</th>
                        <th width="120px" style="color:red;font-weight: bold">Descripción</th>
                        <th width="50px" style="color:red;font-weight: bold">Código</th>
                        <th width="180px" style="color:red;font-weight: bold">Descripción</th>
                        <th width="100px" style="color:red;font-weight: bold">Acciones</th>                        
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div> --}}
{{-- </div>

        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#users').DataTable({
                    "serverSide": true,                   
                    "ajax": "{{ url('api/catalogs') }}",
                    "columns": [                        
                        
                        //CODE CATALOG5 CON VISTA
                        {data: 'code4'},
                        {data: 'descrip_c4'},
                        {data: 'code5'},
                        {data: 'descrip_c5'},   
                        {data: 'btn'},
                    ],
                    "language": {
                        "info": "_TOTAL_ registros",
                        "search": "Buscar",
                        "paginate": {
                            "next": "Siguiente",
                            "previous": "Anterior",
                        },
                        "lengthMenu": 'Mostrar <select >'+
                                    '<option value="10">10</option>'+
                                    '<option value="30">30</option>'+
                                    '<option value="-1">Todos</option>'+
                                    '</select> registros',
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "emptyTable": "No hay datos",
                        "zeroRecords": "No hay coincidencias", 
                        "infoEmpty": "",
                        "infoFiltered": ""
                    }
                });
            });
        </script>    
</html> --}}