@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Objeto de gastos</h5>
                        <span>Agregar Objeto de gasto</span>
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
                            <a href="{{ route('expenditure_objects.index') }}">Objeto de gastos</a>
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
                                    <h5>Agregar Objeto de gasto</h5>
                                </div>
                                <div class="card-block">
                                    <form method="POST" action="{{ route('expenditure_objects.store') }}">
                                        @csrf
                                        <div class="form-group row @error('code') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Código</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="code " name="code " value="{{ old('code') }}" class="form-control @error('code ') form-control-danger @enderror" value="{{ old('code') }}">
                                                @error('code ')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row @error('description') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Descripción del Objeto de Gasto</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="description" name="description" value="{{ old('description') }}" class="form-control @error('description') form-control-danger @enderror" value="{{ old('description') }}">
                                                @error('description')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row @error('alias') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Alias</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="alias" name="alias" value="{{ old('alias') }}" class="form-control @error('alias') form-control-danger @enderror" value="{{ old('alias') }}">
                                                @error('alias')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row @error('level') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Nivel</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="level" name="level" value="{{ old('level') }}" class="form-control @error('level') form-control-danger @enderror" value="{{ old('level') }}">
                                                @error('level')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row @error('financial_level_id') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Nivel de Financiamiento</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="financial_level_id" name="financial_level_id" value="{{ old('financial_level_id') }}" class="form-control @error('financial_level_id') form-control-danger @enderror" value="{{ old('financial_level_id') }}">
                                                @error('financial_level_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div> 
                                        <div class="form-group row @error('superior_expenditure_object_id') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Nivel Superior de O.G.</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="superior_expenditure_object_id" name="superior_expenditure_object_id" value="{{ old('superior_expenditure_object_id') }}" class="form-control @error('superior_expenditure_object_id') form-control-danger @enderror" value="{{ old('superior_expenditure_object_id') }}">
                                                @error('superior_expenditure_object_id')
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