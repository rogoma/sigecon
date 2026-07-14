@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-users bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Usuarios</h5>
                        <span>Cambiar Password</span>
                    </div>
                </div>
            </div>
            {{-- <div class="col-lg-4">
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
            </div> --}}
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
                                    <h5>Cambiar Password</h5>
                                </div>
                                <div class="card-block">
                                    {{-- <form method="GET" action="{{ route('users.update_pass', Auth::user()->id) }}"> --}}
                                    <form method="POST" action="{{ route('users.update_pass', Auth::user()->id) }}">
                                        @csrf
                                        @method('PUT')

                                        {{-- CÓDIGO PARA MOSTRAR ERRORES --}}
                                        <div class="col-sm-12">
                                            @if ($errors->any())
                                           <div class="alert alert-danger">
                                           <ul>
                                               @foreach ($errors->all() as $error)
                                                 <li>{{ $error }}</li>
                                               @endforeach
                                           </ul>
                                           </div>
                                           @endif
                                        </div>

                                        <div class="form-group row @error('document') has-danger @enderror">
                                            {{-- <label class="col-sm-2 col-form-label">CI Usuario</label> --}}
                                            <div class="col-sm-2">
                                                <input type="text" name="document" placeholder="Cédula" value="{{ Auth::user()->document }}" class="form-control @error('document') form-control-danger @enderror" autofocus required hidden>

                                                {{-- <input type="text" name="document" placeholder="Cédula" value="{{ old('document') }}" class="form-control @error('document') form-control-danger @enderror" autofocus required> --}}

                                                @error('document')
                                                    <div class="has-danger col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('password') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Password Actual</label>
                                            <div class="col-sm-2">
                                                <input type="password" id="password" name="password" placeholder="Password actual" value="{{ old('password') }}" class="form-control @error('password') form-control-danger @enderror">
                                                @error('password')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('new_pass') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Nuevo Password</label>
                                            <div class="col-sm-2">
                                                <input type="password" id="new_pass" name="new_pass" value="{{ old('new_pass') }}" class="form-control @error('new_pass') form-control-danger @enderror">
                                                @error('new_pass')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('sec_pass') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Repetir Password</label>
                                            <div class="col-sm-2">
                                                <input type="password" id="sec_pass" name="sec_pass" value="{{ old('sec_pass') }}" class="form-control @error('sec_pass') form-control-danger @enderror">
                                                @error('sec_pass')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        @error('bad_credentials')
                                            <div class="has-danger col-form-label text-center">{{ $message }}</div>
                                        @enderror

                                        <div class="form-group row">
                                            <label class="col-sm-2"></label>
                                            <div class="col-sm-2">
                                                <button type="submit" class="btn btn-primary m-b-0">Cambiar Password</button>
                                            </div>
                                        </div>

                                        <br><br><br><br>

                                        {{-- VERIFICA PERMISOS --}}
                                        {{-- @if (Auth::user()->hasPermission(['admin.users.update']))
                                            <div class="text-center">
                                                <label class="col-mm-2"></label>
                                                <div class="col-mm-2">
                                                    <a href="{{ route('home') }}" class="btn btn-primary">Resetear Password</a>
                                                </div>
                                            </div>
                                        @endif --}}

                                        <h5 style="color:red;font-weight: bold" class="text-center"> ATENCIÓN: si olvidó su password, favor ponerse en contacto con INFORMÁTICA-SENASA.</h5>

                                        {{-- <div class="form-group row">
                                            <label class="col-mm-2"></label>
                                            <div class="col-mm-2">
                                                <a href="{{ route('home') }}" class="btn btn-primary">Resetear Password</a>
                                            </div>
                                        </div> --}}

                                        {{-- <div class="text-center">
                                            <button type="button" title="Editar" class="btn btn-danger" onclick="resetPass({{ Auth::user()->id }})">
                                            <i class="fa fa-pencil"> Resetear Password</i>
                                            </button>
                                        </div> --}}



                                        {{-- <div class="form-group row">
                                            <label class="col-sm-2"></label>
                                            <div class="col-sm-2">
                                                <button type="button" title="Editar" class="btn btn-danger" onclick="resetPass({{ Auth::user()->id }})">
                                                    <i>Resetear Password</i>
                                            </div>
                                        </div> --}}



                                        {{-- @php
                                            var_dump(Auth::user()->id);exit();
                                        @endphp --}}


                                        {{-- <div class="text-left">
                                            {{-- <a href="{{ route('orders.files.create', $order->id) }}" class="btn btn-primary">Cargar Archivos</a> --}}
                                            {{-- <a class="btn btn-primary" onclick="updateDependencies({{ Auth::user()->id }})>Cargar Archivos</a> --}}
                                        {{-- </div> --}}

                                    </form>
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

    // $('#dependency').select2();
    // $('#position').select2();
    // $('#role').select2();

    resetPass = function(reset){
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
            $.ajax({
                // url : '/orders/'+id+'/reset_pass',
                url : '/users/reset_pass/'+id,
                // users/reset_pass/{id}
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
        }
      );
    };

});
</script>
@endpush
