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
                        <span>Editar Sub Programa</span>
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
                                    <h5>Editar Sub Programa</h5>
                                </div>
                                <div class="card-block">
                                    <form method="POST" action="{{ route('sub_programs.update', $sub_program->id) }}">
                                        @csrf
                                        @method('PUT')                                        
                                        <div class="form-group row @error('program') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Programa</label>
                                            <div class="col-sm-10">
                                                <select id="program" name="program" class="form-control">
                                                    <option value="">--- Seleccionar Programa ---</option>
                                                    @foreach ($programs as $program)
                                                        <option value="{{ $program->id }}" @if ($program->id == old('program', $sub_program->program_id)) selected @endif>{{ $program->description }}</option>
                                                    @endforeach
                                                    </select>
                                                    @error('program')
                                                        <div class="col-form-label">{{ $message }}</div>
                                                    @enderror                                                                                                
                                                @error('program_id ')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('description') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Descripción del SubPrograma</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="description" name="description" value="{{ old('description', $sub_program->description) }}" class="form-control @error('description') form-control-danger @enderror" value="{{ old('description', $sub_program->description) }}">
                                                @error('description')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row @error('activity_code') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Código de Actividad</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="activity_code" name="activity_code" value="{{ old('activity_code', $sub_program->activity_code) }}" class="form-control @error('activity_code') form-control-danger @enderror" value="{{ old('activity_code', $sub_program->activity_code) }}">
                                                @error('activity_code')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row @error('proyecto') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Proyecto</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="proyecto" name="proyecto" value="{{ old('proyecto', $sub_program->proyecto) }}" class="form-control @error('proyecto') form-control-danger @enderror" value="{{ old('proyecto', $sub_program->proyecto) }}">
                                                @error('proyecto')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row @error('program_measurement_unit_id') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Unidad de Medida - Programa</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="program_measurement_unit_id" name="program_measurement_unit_id" value="{{ old('program_measurement_unit_id', $sub_program->program_measurement_unit_id) }}" class="form-control @error('program_measurement_unit_id') form-control-danger @enderror" value="{{ old('program_measurement_unit_id', $sub_program->program_measurement_unit_id) }}">
                                                @error('program_measurement_unit_id')
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
    $('#program').select2();
});
</script>
@endpush