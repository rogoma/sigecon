@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Catálogo</h5>
                        <span>Editar Catálogo</span>
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
                            <a href="{{ route('catalog_level5s.index') }}">Catálogos</a>
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
                                    <h5>Editar Catálogo</h5>
                                </div>
                                <div class="card-block">
                                    <form method="POST" action="{{ route('catalog_level5s.update', $catalog_level5->id) }}">
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

                                        <div class="form-group row @error('catalog_level4s') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Código Cát. 4)</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="catalog_level4s" name="catalog_level4s" value="{{ $catalog_level4->code }}" class="form-control @error('catalog_level4s') form-control-danger @enderror" value="{{  $catalog_level4->code }}" readonly>
                                                @error('catalog_level4s')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row @error('code') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Código Cat. 5 (nnnn)</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="code" name="code" value="{{ substr($catalog_level5->code,9,6) }}" class="form-control @error('code') form-control-danger @enderror" value="{{  substr($catalog_level5->code,9,6) }}">
                                                @error('code')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- $rest2 = substr($request->input('code'), 0, 8); --}}
                                        
                                        <div class="form-group row @error('description') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Descripción</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="description" name="description" value="{{ old('description', $catalog_level5->description) }}" class="form-control @error('description') form-control-danger @enderror" value="{{ old('description', $catalog_level5->description) }}">
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


@push('scripts')
<script type="text/javascript">
$(document).ready(function(){
    // $('#catalog_level4s').select2();    

});
</script>
@endpush

