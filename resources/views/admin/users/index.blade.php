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
                    <i class="fa fa-users bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Usuarios</h5>
                        <span>Listado de Usuarios</span>
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
                            <a href="{{ route('users.index') }}">Usuarios</a>
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
                                        <h5>Listado de Usuarios</h5>
                                        {{-- <a href="pdf/users" class="btn btn-primary" target="_blank">Listado de Usuarios-BD</a>  --}}
                                        <a href="pdf/users2" class="btn btn-danger" target="_blank">Listado de Usuarios-BD</a>
                                        <a href="/users/exportarexcel" class="btn btn-success">Bajar_en_Excel</a>
                                        {{-- <a href="/users/exportarexcel3" class="btn btn-primary">Bajar_en_Excel</a> --}}
                                    </div>
                                    <div class="float-right">
                                        <a href="{{ route('users.create') }}" class="btn btn-primary">Agregar Usuario</a>
                                        {{-- <a href="{{ route('users.create2') }}" class="btn btn-primary">Agregar Usuario</a>   --}}
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="dt-responsive table-responsive">
                                        <table id="users" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Usuario</th>
                                                    <th>Cédula</th>
                                                    <th>Email</th>
                                                    <th>Dependencia</th>
                                                    <th>Cargo</th>
                                                    <th>Rol</th>
                                                    <th>Admin Contrato</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @for ($i = 0; $i < count($users); $i++)
                                                <tr>
                                                    <td>{{ ($i+1) }}</td>
                                                    <td>{{ $users[$i]->getFullName() }}</td>
                                                    <td>{{ $users[$i]->document }}</td>
                                                    <td width="50">{{ $users[$i]->email }}</td>
                                                    <td width="200">{{ $users[$i]->dependency->description }}</td>
                                                    <td width="50">{{ $users[$i]->position->description }}</td>
                                                    <td>{{ $users[$i]->role->description }}</td>

                                                    @if ($users[$i]->contract_admin == 1)
                                                        <td width="50">SI</td>
                                                    @else
                                                        <td width="50">NO</td>
                                                    @endif

                                                    @if ($users[$i]->state == 1)
                                                        <td>Activo</td>
                                                    @else
                                                        <td style="color:red;font-weight: bold">Inactivo</td>
                                                    @endif

                                                    <td>
                                                        <button type="button" title="Editar" class="btn btn-warning btn-icon" onclick="updateUser({{ $users[$i]->id }})">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>

                                                        @if (Auth::user()->hasPermission(['admin.users.reset']))
                                                            <button type="button" title="Resetear" class="btn btn-success btn-icon" onclick="resetUser({{ $users[$i]->id }})">
                                                                <i class="fa fa-recycle"></i>
                                                            </button>
                                                        @endif

                                                        {{-- NO SE UTILIZARÁ ESTE BOTÓN, LA FUNCIÓN JS Y TAMPOCO CONTROLLER, SE MANEJARÁ CON ESTADOS 1(ACTIVO) 2 (INACTIVO) --}}
                                                        {{-- <button type="button" title="Borrar" class="btn btn-danger btn-icon" onclick="deleteUser({{ $users[$i]->id }})">
                                                            <i class="fa fa-trash"></i>
                                                        </button> --}}
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
    $('#users').DataTable();

    updateUser = function(user){
        location.href = '/users/'+user+'/edit/';
    }

    resetUser = function(user){
        swal({
            title: "Atención",
            text: "Está seguro que desea resetear el password?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí, resetear",
            cancelButtonText: "Cancelar",
        },
        function(isConfirm){
            if(isConfirm){
                location.href = '/users/'+user+'/reset_pass/';
            }
        });
    }

    deleteUser = function(user){
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
              url : '/users/'+user,
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
