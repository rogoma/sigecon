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
                        <span>Mostrar Rol</span>
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
                                    <h5>Mostrar Rol</h5>
                                </div>
                                <div class="card-block">

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Nombre:</label>
                                        <div class="col-sm-10 m-t-10">
                                            <p>{{ $role->name }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Descripción:</label>
                                        <div class="col-sm-10 m-t-10">
                                            <p>{{ $role->description }}</p>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Permisos</label>
                                        <div class="col-sm-10 m-t-10">
                                            <ul>
                                            @foreach ($role->permissions as $permission)
                                                <li>
                                                    <p><i class="fa fa-circle"></i> {{ $permission->description }}</p>
                                                </li>
                                            @endforeach
                                            </ul>
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