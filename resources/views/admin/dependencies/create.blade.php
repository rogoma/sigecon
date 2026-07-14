@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Dependencias</h5>
                        <span>Agregar Dependencia</span>
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
                            <a href="{{ route('dependencies.index') }}">Dependencias</a>
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
                                    <h5>Agregar Dependencia</h5>
                                </div>
                                <div class="card-block">
                                    <form method="POST" action="{{ route('dependencies.store') }}">
                                        @csrf

                                        <div class="form-group row @error('description') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Descripción</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="description" name="description" value="{{ old('description') }}" class="form-control @error('description') form-control-danger @enderror" value="{{ old('description') }}">
                                                @error('description')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('dependency_types') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Tipo de Dependencia</label>
                                            <div class="col-sm-10">
                                                <select id="dependency_types" name="dependency_types" class="form-control">
                                                    <option value="">--- Seleccionar Tipo de Dependencia ---</option>
                                                    @foreach ($dependency_types as $dependency_type)
                                                        <option value="{{ $dependency_type->id }}" @if ($dependency_type->id == old('dependency_types')) selected @endif>{{ $dependency_type->description }}</option>
                                                    @endforeach
                                                </select>
                                                @error('dependency_types')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('uoc_types') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Tipo de UOC</label>
                                            <div class="col-sm-10">
                                                <select id="uoc_types" name="uoc_types" class="form-control">
                                                    <option value="">--- Seleccionar Tipo de UOC ---</option>
                                                    @foreach ($uoc_types as $uoc_type)
                                                        <option value="{{ $uoc_type->id }}" @if ($uoc_type->id == old('uoc_types')) selected @endif>{{ $uoc_type->description }}</option>
                                                    @endforeach
                                                </select>
                                                @error('uoc_types')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('uoc_number') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">N° de UOC</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="uoc_number" name="uoc_number" value="{{ old('uoc_number') }}" class="form-control @error('uoc_number') form-control-danger @enderror" value="{{ old('uoc_number') }}">
                                                @error('uoc_number')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('superior_dependency') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Dependencia Superior</label>
                                            <div class="col-sm-10">
                                                <select id="superior_dependency" name="superior_dependency" class="form-control">
                                                    <option value="">--- Seleccionar Dependencia Superior ---</option>
                                                    @foreach ($dependencies as $superior_dependency)
                                                        <option value="{{ $superior_dependency->id }}" @if ($superior_dependency->id == old('superior_dependency')) selected @endif>{{ $superior_dependency->description }}</option>
                                                    @endforeach
                                                </select>
                                                @error('superior_dependency')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('sicp') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">SICP</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="sicp" name="sicp" value="{{ old('sicp') }}" class="form-control @error('sicp') form-control-danger @enderror" value="{{ old('sicp') }}">
                                                @error('sicp')
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

    $('#dependency_types').select2();
    $('#uoc_types').select2();
    $('#superior_dependency').select2();

});
</script>
@endpush