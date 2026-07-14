@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Contratistas</h5>
                        <span>EditarContratista</span>
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
                            <a href="{{ route('providers.index') }}">Contratistas</a>
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
                                    <h5>EditarContratista</h5>
                                </div>
                                <div class="card-block">
                                    <form method="POST" action="{{ route('providers.update', $provider->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group row @error('description') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Descripción</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="description" name="description" value="{{ old('description', $provider->description) }}" class="form-control @error('description') form-control-danger @enderror" value="{{ old('description', $provider->description) }}">
                                                @error('description')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('ruc') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">RUC</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="ruc" name="ruc" value="{{ old('ruc', $provider->ruc) }}" class="form-control @error('ruc') form-control-danger @enderror" value="{{ old('ruc', $provider->ruc) }}">
                                                @error('ruc')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('telefono') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Teléfono</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="telefono" name="telefono" value="{{ old('telefono',$provider->telefono) }}" class="form-control @error('telefono') form-control-danger @enderror" value="{{ old('telefono', $provider->telefono) }}">
                                                @error('telefono')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('email_oferta') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Email para Oferta</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="email_oferta" name="email_oferta" value="{{ old('email_oferta',$provider->email_oferta) }}" class="form-control @error('email_oferta') form-control-danger @enderror" value="{{ old('email_oferta',$provider->email_oferta) }}">
                                                @error('email_oferta')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('email_ocompra') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Email para Orden de Compras</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="email_ocompra" name="email_ocompra" value="{{ old('email_ocompra',$provider->email_ocompra) }}" class="form-control @error('email_ocompra') form-control-danger @enderror" value="{{ old('email_ocompra',$provider->email_ocompra) }}">
                                                @error('email_ocompra')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('representante') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Representante</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="representante" name="representante" value="{{ old('representante',$provider->representante) }}" class="form-control @error('representante') form-control-danger @enderror" value="{{ old('representante',$provider->representante) }}">
                                                @error('representante')
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
