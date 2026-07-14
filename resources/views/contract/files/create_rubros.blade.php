@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Importación de Rubros</h5>
                        {{-- <span>Evaluaciones Técnicas</span> --}}
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
                            <a href="{{ route('contracts.show', $contract->id) }}">Contrato Nº {{ $contract->number_year }}</a>
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
                                    <h5>Importar Archivos Excel con Rubros detallados</h5>
                                </div>
                                <div class="col-sm-12 text-left">
                                    <h5>{{ $contract->description." - ".$contract->modality->description." - Contrato N° ".$contract->number_year." - ".$contract->provider->description }}
                                    {{-- <h5>SIMESE: {{ number_format($contract->simese->first()['simese'],'0', ',','.') }} </h5> --}}
                                    {{-- <h5><a style="font-size: 17px; color:BLACK"> SIMESE: </a>{{ is_null($contract->simese->first()) ?' ' : number_format($contract->simese->first()['simese'],'0', ',','.')."/".$contract->simese->first()['year'] }}</h5> --}}

                                </div>
                                <div class="card-block">
                                    <form method="POST" action="{{ route('contracts.files.store_rubros', $contract->id) }}" enctype="multipart/form-data">
                                    
                                    @csrf

                                    <div class="col-sm-6">
                                        <label for="component_id" class="col-form-label">Componente</label>
                                        <select id="component_id" name="component_id" class="form-control @error('component_id') is-invalid @enderror">
                                            <option value="">--- Seleccionar Componente ---</option>
                                            @foreach ($components as $component)
                                            <option value="{{ $component->id }}" @if ($component->id == old('component_id')) selected @endif>{{ $component->code }}-{{ $component->description }}</option>
                                            @endforeach
                                        </select>
                                        @error('component_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <br>

                                    <div class="col-sm-12">
                                        <div class="form-group @error('description') has-danger @enderror">
                                            <label class="col-form-label">Descripción (hasta 500 caracteres)</label>
                                            <textarea rows="2" id="description" name="description" class="form-control">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="col-form-label">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                        <div class="form-group @error('file') has-danger @enderror">
                                            <label class="col-form-label">Cargar archivo <small>(Archivos permitido: EXCEL)</small></label>
                                            <input id="file" type="file" class="form-control" name="file">
                                            @error('file')
                                                <div class="col-form-label">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 text-center">
                                            <button id="guardar" type="submit" class="btn btn-primary m-b-0">Importar</button>
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

    $('#component_id').select2();    

});
</script>
@endpush
