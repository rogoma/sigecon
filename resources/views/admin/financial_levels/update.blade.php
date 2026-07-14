@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Tipos de Nivel Financiero</h5>
                        <span>Editar Tipo de Nivel Financiero</span>
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
                            <a href="{{ route('financial_levels.index') }}">Tipos de Nivel Financiero</a>
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
                                    <h5>Editar Tipo de Nivel Financiero</h5>
                                </div>
                                <div class="card-block">
                                    <form method="POST" action="{{ route('financial_levels.update', $financial_level->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group row @error('number') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Número</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="number" name="number" value="{{ old('number', $financial_level->number) }}" class="form-control @error('number') form-control-danger @enderror" value="{{ old('number', $financial_level->number) }}">
                                                @error('number')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row @error('description') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Descripción</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="description" name="description" value="{{ old('description', $financial_level->description) }}" class="form-control @error('description') form-control-danger @enderror" value="{{ old('description', $financial_level->description) }}">
                                                @error('description')
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