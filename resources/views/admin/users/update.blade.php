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
                        <span>Editar Usuario</span>
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
                                    <h5>Editar Usuario</h5>
                                </div>
                                <div class="card-block">
                                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group row @error('name') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Nombre</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') form-control-danger @enderror">
                                                @error('name')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('lastname') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Apellido</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="lastname" name="lastname" value="{{ old('lastname', $user->lastname) }}" class="form-control @error('lastname') form-control-danger @enderror" value="{{ old('lastname', $user->lastname) }}">
                                                @error('lastname')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('document') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Cédula</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="document" name="document" value="{{ old('document', $user->document) }}" class="form-control @error('document') form-control-danger @enderror" value="{{ old('document', $user->document) }}">
                                                @error('document')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('email') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') form-control-danger @enderror" value="{{ old('email', $user->email) }}">
                                                @error('email')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('dependency') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Dependencia</label>
                                            <div class="col-sm-10">
                                                <select id="dependency" name="dependency" class="form-control">
                                                    <option value="">--- Seleccionar Dependencia ---</option>
                                                    @foreach ($dependencies as $dependency)
                                                        <option value="{{ $dependency->id }}" @if ($dependency->id == old('dependency', $user->dependency_id)) selected @endif>{{ $dependency->description }}</option>
                                                    @endforeach
                                                </select>
                                                @error('dependency')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('position') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Cargo</label>
                                            <div class="col-sm-10">
                                                <select id="position" name="position" class="form-control">
                                                    <option value="">--- Seleccionar Cargo ---</option>
                                                    @foreach ($positions as $position)
                                                        <option value="{{ $position->id }}" @if ($position->id == old('position', $user->position_id)) selected @endif>{{ $position->description }}</option>
                                                    @endforeach
                                                </select>
                                                @error('position')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('contract_admin') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Administrador de Contrato <br><small>(Si/NO)</small></label>
                                            <div class="col-sm-10">
                                                <select id="contract_admin" name="contract_admin" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                    @foreach (array(0 => 'NO', 1 => 'SI') as $index => $value)
                                                        <option value="{{ $index }}" @if ($index == old('contract_admin', $user->contract_admin)) selected @endif>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                @error('contract_admin')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="form-group row @error('role') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Rol</label>
                                            <div class="col-sm-10">
                                                <select id="role" name="role" class="form-control">
                                                    <option value="">--- Seleccionar Rol ---</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}" @if ($role->id == old('role', $user->role_id)) selected @endif>{{ $role->description }}</option>
                                                    @endforeach
                                                </select>
                                                @error('role')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('state') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Estado</label>
                                            <div class="col-sm-10">
                                                <select id="state" name="state" class="form-control">
                                                       <option value="{{ ($user->state) }}"> </option>
                                                        @foreach (array(1 => 'Activo', 2 => 'Inactivo') as $index => $value)
                                                            <option value="{{ $index }}" @if ($index == old('state', $user->state)) selected @endif>{{ $value }}</option>
                                                        @endforeach
                                                </select>
                                                @error('state')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2"></label>
                                            <div class="col-sm-10">
                                                <button type="submit" class="btn btn-warning m-b-0">Modificar</button>
                                            </div>
                                        </div>

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

    $('#dependency').select2();
    $('#position').select2();
    $('#role').select2();
    $('#state').select2();
    $('#contract_admin').select2();

});
</script>
@endpush
