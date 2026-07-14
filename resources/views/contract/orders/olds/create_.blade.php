@extends('layouts.app')

{{-- @extends('layouts.app') --}}

@section('content')
<head>
    <title>Crear Orden</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Órdenes</h5>
                        <span>Agregar Orden</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}"><i class="fa-solid fa-house"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('contracts.show', $contract->id) }}">Contrato N° {{ $contract->number_year }}</a>
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
                                    <h5>Agregar Orden al Contrato N° {{ $contract->number_year }}</h5>
                                    <br><br>
                                    <label id="fecha_actual" name="fecha_actual"  style="font-size: 20px;color: #FF0000;float: left;" for="fecha_actual">{{ Carbon\Carbon::now()->format('d/m/Y') }}</label>
                                    <h3 style="text-align: center;">Agregar Orden</h3>
                                </div>
                                <div class="card-block">
                                    <form method="POST" action="{{ route('contracts.orders.store', $contract->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="container">

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="number" class="col-form-label">N° de Orden</label>
                                                    <input type="text" id="number" name="number_display" class="form-control @error('number') is-invalid @enderror" value="{{ old('number', $nextContractNumber) }}" maxlength="23" disabled>
                                                    <input type="hidden" id="number_hidden" name="number" value="{{ old('number', $nextContractNumber) }}">
                                                    @error('number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="total_amount" class="col-form-label">Monto</label>
                                                    <input type="text" id="total_amount" name="total_amount" class="form-control @error('total_amount') is-invalid @enderror" value="{{ old('total_amount', 0) }}" maxlength="23" disabled>
                                                    @error('total_amount')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="department_id" class="col-form-label">Departamento</label>
                                                    <select id="department_id" name="department_id" class="form-control @error('department_id') is-invalid @enderror">
                                                        <option value="">--- Seleccionar Departamento ---</option>
                                                        @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}" @if ($department->id == old('department_id')) selected @endif>
                                                            {{ $department->description }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @error('department_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="district_id" class="col-form-label">Distrito</label>
                                                    <select id="district_id" name="district_id" class="form-control @error('district_id') is-invalid @enderror">
                                                        <option value="">--- Seleccionar Distrito ---</option>
                                                    </select>
                                                    @error('district_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="locality" class="col-sm-6 col-form-label">Localidad (Hasta 200 caracteres)</label>
                                                <div class="col-sm-12">
                                                    <input type="text" id="locality" name="locality" class="form-control @error('locality') is-invalid @enderror" value="{{ old('locality') }}" maxlength="200">
                                                    @error('locality')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="sign_date" class="col-form-label">Fecha acuse recibo Contratista</label>
                                                    <div class="input-group">
                                                        <input type="text" id="sign_date" name="sign_date" class="form-control @error('sign_date') is-invalid @enderror" value="{{ old('sign_date') }}" autocomplete="off">
                                                        <span class="input-group-append">
                                                            <button type="button" class="btn btn-outline-secondary" onclick="show('sign_date');"><i class="fa fa-calendar"></i></button>
                                                        </span>
                                                    </div>
                                                    @error('sign_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="component_id" class="col-form-label">Sub-Componente</label>
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

                                                <div class="col-sm-3">
                                                    <label for="plazo" class="col-form-label">Plazo de ejecución (En días)</label>
                                                    <input type="text" id="plazo" name="plazo" class="form-control @error('plazo') is-invalid @enderror" value="{{ old('plazo') }}" maxlength="3">
                                                    @error('plazo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="order_state_id" class="col-form-label">Estado de la Orden</label>
                                                    <select id="order_state_id" name="order_state_id" class="form-control @error('order_state_id') is-invalid @enderror">
                                                        <option value="">--- Seleccionar Estado ---</option>
                                                        @foreach ($order_states as $order_state)
                                                        <option value="{{ $order_state->id }}" @if ($order_state->id == old('order_state_id')) selected @endif>{{ $order_state->description }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('order_state_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-sm-9">
                                                    <label for="comments" class="col-form-label">Referencias (Hasta 300 caracteres)</label>
                                                    <textarea id="comments" name="comments" class="form-control @error('comments') is-invalid @enderror" maxlength="300">{{ old('comments') }}</textarea>
                                                    @error('comments')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>                                        
                                        <br>
                                        <div class="text-center mt-4">
                                            <button id="saveButton1" type="submit" class="btn btn-primary">Grabar Orden</button>
                                            {{-- <button id="saveButton1" type="submit" class="btn btn-primary">Ajuntar Rubros</button> --}}
                                        </div>
                                        <br>
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

    $('#department_id').on('change', function() {
                var departmentId = $(this).val();
                $('#district_id').empty().append('<option value="">--- Seleccionar Distrito ---</option>');

                if (departmentId) {
                    $.ajax({
                        url: '/fetch-districts',
                        type: 'GET',
                        data: {
                            department_id: departmentId
                        },
                        success: function(data) {
                            $.each(data, function(key, district) {
                                $('#district_id').append('<option value="' + district.id +
                                    '">' + district.description + '</option>');
                            });
                        },
                        error: function(xhr) {
                            console.error('Error fetching districts:', xhr.responseText);
                        }
                    });
                }
    });

    // Validar antes de guardar
    // $('#saveButton').on('click', function(event) {
    //     var districtId = $('#district_id').val();
    //     if (!districtId) {
    //         event.preventDefault(); // Evita que el formulario se envíe
    //         alert('Por favor, seleccione un distrito antes de guardar.');
    //     } else {
    //         // Aquí puedes agregar la lógica para proceder con el guardado
    //         //console.log('Formulario válido. Procediendo con el guardado...');
    //     }
    // });

    $('#component_id').select2();
    $('#order_state_id').select2();
    $('#department_id').select2();
    $('#district_id').select2();

    $('#sign_date').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
    });
});
</script>
@endpush
