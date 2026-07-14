@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Catálogos</h5>
                        <span>Agregar Catálogo</span>
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
                                    <h5>Agregar Catálogo</h5>
                                </div>
                                <div class="card-block">
                                    <form method="POST" action="{{ route('catalog_level5s.store') }}">
                                        @csrf

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

                                        <div class="form-group row @error('level4_catalog_code') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Código de Catálogo Nivel 4</label>
                                            
                                            <div class="col-sm-2">
                                                <input type="text" id="level4_catalog_code" name="level4_catalog_code" value="{{ old('level4_catalog_code') }}" class="form-control @error('level4_catalog_code_id') form-control-danger @enderror" value="{{ old('level4_catalog_code_id') }}">
                                                {{-- <input type="text" id="level4_catalog_code" name="level4_catalog_code" value="{{ old('level4_catalog_code', $catalog_level4s->description) }}" class="form-control @error('level4_catalog_code_id') form-control-danger @enderror"> --}}
                                                {{-- <small id="level4_catalog_description">{{ old('level4_catalog_description', $catalog_level4s->description) }}</small> --}}
                                                
                                                {{-- <input type="hidden" id="level4_catalog_code_id" name="level4_catalog_code_id" value="{{ old('level4_catalog_code_id', $catalog_level4s->id) }}">
                                                <input type="hidden" name="level4_catalog_description" value={{ old('level4_catalog_description', $catalog_level4s->description) }}> --}}

                                                {{-- ACA TRAE DATO CAPTURADO EN JSCRIPT DE ABAJO --}}
                                                <br>
                                                <input style="width : 560px;" id="level4_catalog_description" name="level4_catalog_description" value="{{ old('level4_catalog_description') }}" readonly>
                                                <br>
                                                <input  type="hidden" id="level4_catalog_code_id" name="level4_catalog_code_id" value="{{ old('level4_catalog_code_id','level4_catalog_code_id') }}">                                                 
                                                {{-- <small  id="level4_catalog_description">{{ 'level4_catalog_description'}}</small> --}}

                                                
                                                {{-- @php                                            
                                                    var_dump('level4_catalog_code_id');exit();                                            
                                                @endphp --}}

                                            </div>

                                            <div class="col-sm-4">
                                                <button id="search_level4" type="button" class="btn btn-info">Buscar código de Cát. 4</button>
                                            </div>
                                            @error('level5_catalog_code_id')
                                                <div class="col-form-label">{{ $message }}</div>
                                            @enderror
                                            <div id="codes_result" class="offset-sm-2 col-sm-8"></div>
                                        </div>
                                        

                                         {{-- <div class="form-group row @error('catalog_level4s2') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Código-Catálogo 4</label>
                                            <div class="col-sm-10">
                                                <select id="catalog_level4s2" name="catalog_level4s2" class="form-control">
                                                    <option value="">--- Seleccionar Código ---</option>
                                                    @foreach ($catalog_level4s as $catalog_level4)                                                    
                                                        <option value="{{ $catalog_level4->code }}" @if ($catalog_level4->id == old('catalog_level4s')) selected @endif>{{ $catalog_level4->code }} - {{ $catalog_level4->description }}</option>
                                                    @endforeach
                                                </select>
                                                @error('catalog_level4s2')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div> --}}

                                        {{-- <div class="form-group row @error('catalog_level4s') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Código-descrip. Catálogo 4</label>
                                            <div class="col-sm-10">
                                                <select id="catalog_level4s" name="catalog_level4s" class="form-control">
                                                    <option value="">--- Seleccionar Código ---</option>
                                                    @foreach ($catalog_level4s as $catalog_level4)                                                    
                                                        <option value="{{ $catalog_level4->id }}" 
                                                            @if ($catalog_level4->id == old('catalog_level4s')) selected @endif>{{ $catalog_level4->code }} - {{ $catalog_level4->description }}</option>
                                                    @endforeach
                                                </select>
                                                @error('catalog_level4s')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div> --}}

                                        {{-- <div class="form-group row @error('catalog_level4s2') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Código Catalog 4</label>
                                            <div class="col-sm-10">
                                                @foreach ($catalog_level4s as $catalog_level4)   
                                                    <input type="text" id="code" name="code" value="{{ old('code') }}" class="form-control @error('code') form-control-danger @enderror" value="{{ old('code') }}">
                                                @endforeach
                                                @error('catalog_level4s2')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>                                        --}}
                                        
                                        <div class="form-group row @error('code') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Código (nnnn)</label>
                                            <div class="col-sm-1">
                                                <input type="text" id="code" name="code" value="{{ old('code') }}" class="form-control @error('code') form-control-danger @enderror" value="{{ old('code') }}">
                                                @error('code')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="form-group row @error('description') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Descripción</label>
                                            <div class="col-sm-4">
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

@push('scripts')
<script type="text/javascript">
$(document).ready(function(){
    $('#catalog_level4s2').select2();
    
});

$('#search_level4').click(function(){
        $('#search_level4').attr("disabled", true);
        $.ajax({
            url : '/items/search4',
            method : 'GET',
            data: { search4: $('#level4_catalog_code').val(),  _token: '{{ csrf_token() }}'},
            success: function(data){
                try{
                    $('#search_level4').removeAttr("disabled");
                    let table_start = '<table class="table table-striped table-bordered table-sm"><tbody>'
                    let thead = '<thead><th>Id</th><th>Código</th><th>Descripción</th><th>Acción</th></thead>'
                    let rows = data.map(row => '<tr><td>'+row.id+'</td>'+'<td>'+row.code+'</td>'+'<td>'+row.description+'</td>'+'<td><button type="button" onclick="seleccionar(\''+row.id+'\',\''+row.code+'\',\''+row.description+'\');" class="btn btn-primary">Seleccionar</button></td></tr>' );
                    let table_end = '</tbody></table>'
                    $('#codes_result').html(table_start + thead + rows.join('') + table_end);
                }catch(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
            },
            error: function(error){
                swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                $('#search_level4').removeAttr("disabled");
                console.log(error);
            }
        });
    });

    seleccionar = function(id, code, description){
    $('#level4_catalog_code_id').val(id);
    $('#level4_catalog_code').val(code);
    // $('#level4_catalog_description').html('Ìtem seleccionado: ' + description);
    $('#level4_catalog_description').html(description);
    // $('input[name=level4_catalog_description]').val('Ìtem seleccionado: ' + description);
    $('input[name=level4_catalog_description]').val(description);
    $('#codes_result').html('');
}

$('#level4_catalog_code').keyup(function(){
    $('#level4_catalog_code_id').val('');
    $('#level4_catalog_description').html('');
    $('input[name=level4_catalog_description]').val('');
}); 
</script>
@endpush