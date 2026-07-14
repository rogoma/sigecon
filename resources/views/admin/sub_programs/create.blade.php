@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Sub Programas</h5>
                        <span>Agregar Sub Programa</span>
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
                            <a href="{{ route('sub_programs.index') }}">Sub Programas</a>
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
                                    <h5>Agregar Sub Programa</h5>
                                </div>
                                <div class="card-block">
                                    <form method="POST" action="{{ route('sub_programs.store') }}">
                                        @csrf

                                        <div class="form-group row @error('program_id ') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">ID Programa</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="program_id " name="program_id " value="{{ old('program_id ') }}" class="form-control @error('program_id') form-control-danger @enderror" value="{{ old('program_id') }}">
                                                @error('program_id ')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row @error('description') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Descripción del SubPrograma</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="description" name="description" value="{{ old('description') }}" class="form-control @error('description') form-control-danger @enderror" value="{{ old('description') }}">
                                                @error('description')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row @error('activity_code') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Código de Actividad</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="activity_code" name="activity_code" value="{{ old('activity_code') }}" class="form-control @error('activity_code') form-control-danger @enderror" value="{{ old('activity_code') }}">
                                                @error('activity_code')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row @error('proyecto') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Proyecto</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="proyecto" name="proyecto" value="{{ old('proyecto') }}" class="form-control @error('proyecto') form-control-danger @enderror" value="{{ old('proyecto') }}">
                                                @error('proyecto')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row @error('program_measurement_unit_id') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Unidad de Medida - Programa</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="program_measurement_unit_id" name="program_measurement_unit_id" value="{{ old('program_measurement_unit_id') }}" class="form-control @error('program_measurement_unit_id') form-control-danger @enderror" value="{{ old('program_measurement_unit_id') }}">
                                                @error('program_measurement_unit_id')
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