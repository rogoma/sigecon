@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-suitcase bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Roles</h5>
                        <span>Agregar Rol</span>
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
                            <a href="{{ route('roles.index') }}">Roles</a>
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
                                    <h5>Agregar Rol</h5>
                                </div>
                                <div class="card-block">
                                    <form method="POST" action="{{ route('roles.store') }}">
                                        @csrf

                                        <div class="form-group row @error('name') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Nombre</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') form-control-danger @enderror" value="{{ old('name') }}">
                                                @error('name')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('description') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Descripción</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="description" name="description" value="{{ old('description') }}" class="form-control @error('description') form-control-danger @enderror" value="{{ old('description') }}">
                                                @error('description')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('permissions') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Permisos</label>
                                            <div class="col-sm-10 m-t-5">
                                                <div class="row border-checkbox-section">
                                                @foreach ($permissions as $permission)
                                                    <div class="col-sm-3 m-b-10">
                                                        <div class="border-checkbox-group border-checkbox-group-primary">
                                                            <input class="border-checkbox" name="permissions[]" type="checkbox" id="checkbox{{$permission->id}}" value="{{ $permission->id }}" @if (in_array($permission->id, old('permissions', []))) checked @endif>
                                                            <label class="border-checkbox-label" for="checkbox{{$permission->id}}">{{ $permission->description }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                </div>
                                                @error('permissions')
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