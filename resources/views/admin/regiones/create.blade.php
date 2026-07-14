@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Región Geográfica</h5>                        
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
                            <a href="{{ route('regiones.index') }}">Región Geográfica</a>
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
                                    <h5>Agregar Región Geográfica</h5>
                                </div>
                                <div class="card-block">
                                    <form method="POST" action="{{ route('regiones.store') }}">
                                        @csrf
                                        

                                        <div class="form-group row @error('description') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Nombre Región Geográfica</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="description" name="description" value="{{ old('description') }}" class="form-control @error('description') form-control-danger @enderror" value="{{ old('description') }}">
                                                @error('description')
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


{{-- @extends('layouts.app')

@section('content')
    <h1>Crear Región</h1>
    <form action="{{ route('regiones.store') }}" method="POST">
        @csrf
        <label>Descripción:</label>
        <input type="text" name="description" value="{{ old('description') }}">
        @error('description') <p>{{ $message }}</p> @enderror
        <button type="submit">Guardar</button>
    </form>
@endsection --}}
