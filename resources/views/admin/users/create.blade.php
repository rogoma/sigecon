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
                        <span>Agregar Usuario</span>
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
                                    <h5>Agregar Usuario</h5>
                                </div>
                                <div class="card-block">
                                    <form method="POST" action="{{ route('users.store') }}">
                                        @csrf
                                        <div class="form-group row @error('name') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Nombre</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') form-control-danger @enderror">
                                                @error('name')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('lastname') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Apellido</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" class="form-control @error('lastname') form-control-danger @enderror" value="{{ old('lastname') }}">
                                                @error('lastname')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('document') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Cédula</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="document" name="document" value="{{ old('document') }}" class="form-control @error('document') form-control-danger @enderror" value="{{ old('document') }}">
                                                @error('document')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('email') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') form-control-danger @enderror" value="{{ old('email') }}">
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
                                                        <option value="{{ $dependency->id }}" @if ($dependency->id == old('dependency')) selected @endif>{{ $dependency->description }}</option>
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
                                                        <option value="{{ $position->id }}" @if ($position->id == old('position')) selected @endif>{{ $position->description }}</option>
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
                                                        <option value="{{ $index }}" @if ($index == old('contract_admin')) selected @endif>{{ $value }}</option>
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
                                                        <option value="{{ $role->id }}" @if ($role->id == old('role')) selected @endif>{{ $role->description }}</option>
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
                                                    <option value="">--- Seleccionar Estado ---</option>
                                                    @foreach ($estados as $valor => $nombre)
                                                        <option value="{{ $valor }}">{{ $nombre }}</option>
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
                                                <button type="submit" class="btn btn-primary m-b-0">Guardar</button>
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
    $('#superior_dependency').select2();
    $('#dependency').select2();
    $('#position').select2();
    $('#role').select2();
    $('#state').select2();
    $('#contract_admin').select2();

});
</script>
@endpush
