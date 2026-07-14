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
                                <a href="{{ route('contracts.show', $contract->id) }}">Contrato N°
                                    {{ $contract->number_year }}</a>
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
                                        <div class="col-sm-10 text-left">
                                            <h5>Llamado:
                                                {{ $contract->description . ' - ' . $contract->modality->description . ' - ' . $contract->provider->description }}
                                            </h5>
                                        </div>

                                        <h5>Agregar Orden al Contrato N° {{ $contract->number_year }}</h5>
                                        <br><br>
                                        <label id="fecha_actual" name="fecha_actual"
                                            style="font-size: 20px;color: #FF0000;float: left;"
                                            for="fecha_actual">{{ Carbon\Carbon::now()->format('d/m/Y') }}</label>
                                        <h3 style="text-align: center;">Agregar Orden</h3>
                                    </div>
                                    <div class="card-block">
                                        <form method="POST" action="{{ route('contracts.orders.store', $contract->id) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="container">

                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label for="number" class="col-form-label">N° de Orden</label>
                                                        <input type="text" id="number" name="number_display"
                                                            class="form-control @error('number') is-invalid @enderror"
                                                            value="{{ old('number') }}" maxlength="23" disabled>
                                                        <input type="hidden" id="number_hidden" name="number"
                                                            value="{{ old('number') }}">
                                                        @error('number')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <label for="total_amount" class="col-form-label">Monto</label>
                                                        <input type="text" id="total_amount" name="total_amount"
                                                            class="form-control @error('total_amount') is-invalid @enderror"
                                                            value="{{ old('total_amount', 0) }}" maxlength="23" disabled>
                                                        @error('total_amount')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label for="department_id"
                                                            class="col-form-label">Departamento</label>
                                                        <select id="department_id" name="department_id"
                                                            class="form-control @error('department_id') is-invalid @enderror">
                                                            <option value="">--- Seleccionar Departamento ---</option>
                                                            @foreach ($departments as $department)
                                                                <option value="{{ $department->id }}"
                                                                    @if ($department->id == old('department_id')) selected @endif>
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
                                                        <select id="district_id" name="district_id"
                                                            class="form-control @error('district_id') is-invalid @enderror">
                                                            <option value="">--- Seleccionar Distrito ---</option>
                                                        </select>
                                                        @error('district_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <label for="locality_id" class="col-form-label">Localidad</label>
                                                        <select id="locality_id" name="locality_id"
                                                            class="form-control @error('locality_id') is-invalid @enderror">
                                                            <option value="">--- Seleccionar Localidad ---</option>
                                                        </select>
                                                        @error('locality_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    
                                                </div>                                                

                                                {{-- <div class="form-group row">
                                                    <label for="locality" class="col-sm-6 col-form-label">Localidad (Hasta
                                                        200 caracteres)</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" id="locality" name="locality"
                                                            class="form-control @error('locality') is-invalid @enderror"
                                                            value="{{ old('locality') }}" maxlength="200">
                                                        @error('locality')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div> --}}

                                                <div class="form-group row">
                                                    {{-- <div class="col-sm-3">
                                                        <label for="sign_date" class="col-form-label">Fecha acuse recibo
                                                            Contratista</label>
                                                        <div class="input-group">
                                                            <input type="text" id="sign_date" name="sign_date"
                                                                class="form-control @error('sign_date') is-invalid @enderror"
                                                                value="{{ old('sign_date') }}" autocomplete="off"
                                                                disabled>
                                                            <span class="input-group-append">
                                                                <button type="button" class="btn btn-outline-secondary"
                                                                    onclick="show('sign_date');"><i
                                                                        class="fa fa-calendar"></i></button>
                                                            </span>
                                                        </div>
                                                        @error('sign_date')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div> --}}

                                                    <div class="col-sm-6">
                                                        <label for="component_id"
                                                            class="col-form-label">Componente</label>
                                                        <select id="component_id" name="component_id"
                                                            class="form-control @error('component_id') is-invalid @enderror"
                                                            data-url="{{ route('getMaxNumber') }}">
                                                            <option value="">--- Seleccionar Componente ---
                                                            </option>
                                                            @foreach ($components as $component)
                                                                <option value="{{ $component->id }}"
                                                                    @if ($component->id == old('component_id')) selected @endif>
                                                                    {{ $component->code }}-{{ $component->description }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('component_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label for="plazo" class="col-form-label">Plazo de ejecución
                                                            (En días)</label>
                                                        <input type="text" id="plazo" name="plazo"
                                                            class="form-control @error('plazo') is-invalid @enderror"
                                                            value="{{ old('plazo') }}" maxlength="3">
                                                        @error('plazo')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    {{-- <div class="col-sm-3">
                                                        <label for="order_state_id" class="col-form-label">Estado de la Orden:</label>
                                                        <br>
                                                        <label for="order_state_id" class="col-form-label"
                                                            style="color: red;">Pendiente Fecha Acuse recibo
                                                            Contratista</label>
                                                    </div> --}}

                                                    <div class="col-sm-9">
                                                        <label for="reference" class="col-form-label">Referencia (Hasta 500 caracteres)</label>
                                                        <textarea id="reference" name="reference" class="form-control @error('reference') is-invalid @enderror"
                                                            maxlength="500">{{ old('reference') }}</textarea>
                                                        @error('reference')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <label for="comments" class="col-form-label">Comentarios (Hasta
                                                            500 caracteres)</label>
                                                        <textarea id="comments" name="comments" class="form-control @error('comments') is-invalid @enderror"
                                                            maxlength="500">{{ old('comments') }}</textarea>
                                                        @error('comments')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <br> --}}
                                            <div class="text-center mt-4">
                                                <button id="saveButton1" type="submit" class="btn btn-primary">Grabar
                                                    Orden</button>
                                                {{-- <button id="saveButton1" type="submit" class="btn btn-primary">Ajuntar Rubros</button> --}}
                                            </div>
                                            <br>
                                            <br>
                                            <p><span style="color: red;"></span></p>
                                            <ul style="color: red;">
                                                REFERENCIAS: PLAZO EN DIAS:
                                                * Fuente de Provisión: 30 *
                                                Equipamiento: 30 *
                                                Tanque: 60 *
                                                Caseta: 30 *
                                                Extensión de línea: 45 *
                                                Red de distribución: 30 *
                                                Aductora: 30 *
                                                Cercado Perim.: 30
                                            </ul>
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
        $(document).ready(function() {
        // Evento para el cambio del componente
        $('#component_id').on('change', function() {
            const componentId = $(this).val(); // Obtiene el ID del componente seleccionado
            const url = $(this).data('url'); // Obtiene la URL desde el atributo data-url
            const locality = $('#locality_id').val(); // Obtiene la localidad seleccionada
            const numberInput = $('#number'); // Referencia al input donde se mostrará el número máximo

            // Limpia el campo de texto al cambiar de componente
            numberInput.val('');

            if (componentId && locality) {
                // Realiza la solicitud al backend incluyendo la localidad
                fetch(`${url}?component_id=${componentId}&locality_id=${locality}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const maxNumber = data.number || 0; // Si no hay registros, muestra 0
                            const nextNumber = maxNumber + 1;
                            numberInput.val(nextNumber); // Actualiza el valor del input
                            $('#number_hidden').val(
                                nextNumber); // Actualiza el valor del input oculto
                        } else {
                            console.error('Error al obtener el número:', data.message);
                            numberInput.val('Error'); // Muestra un mensaje en caso de error
                        }
                    })
                    .catch(error => {
                        console.error('Error en la solicitud:', error);
                        numberInput.val('Error'); // Muestra un mensaje en caso de error
                    });
            }
        });        

        // Evento para cargar distritos al cambiar el departamento
        $('#department_id').on('change', function() {
            var departmentId = $(this).val();
            var districtDropdown = $('#district_id');
            var localityDropdown = $('#locality_id');

            // Limpia el combo de distritos
            districtDropdown.empty().append('<option value="">--- Seleccionar Distrito ---</option>');
            localityDropdown.empty().append('<option value="">--- Seleccionar Localidad ---</option>');

            if (departmentId) {
                // Realiza la solicitud AJAX para obtener distritos
                $.ajax({
                    url: '/fetch-districts',
                    type: 'GET',
                    data: {
                        department_id: departmentId
                    },
                    success: function(data) {
                        // Agrega las opciones al combo de distritos
                        $.each(data, function(key, district) {
                            districtDropdown.append('<option value="' + district
                                .id + '">' + district.description + '</option>');
                        });
                    },
                    error: function(xhr) {
                        console.error('Error fetching districts:', xhr.responseText);
                    }
                });
            }
        });

        // Evento para cargar localidades al cambiar el distrito
        $('#district_id').on('change', function () {
            var districtId = $(this).val();
            var localityDropdown = $('#locality_id');

            // Limpia el combo de localidades
            localityDropdown.empty().append('<option value="">--- Seleccionar Localidad ---</option>');

            if (districtId) {
                // Realiza la solicitud AJAX para obtener localidades
                $.ajax({
                    url: '/fetch-localities',
                    type: 'GET',
                    data: {
                        district_id: districtId
                    },
                    success: function (data) {
                        // Agrega las opciones al combo de localidades
                        $.each(data, function (key, locality) {
                            localityDropdown.append('<option value="' + locality.id + '">' + locality.description + '</option>');
                        });
                    },
                    error: function (xhr) {
                        console.error('Error fetching localities:', xhr.responseText);
                    }
                });
            }
        });


        // Inicialización de select2 en los combos
        $('#component_id').select2();
        $('#order_state_id').select2();
        $('#department_id').select2();
        $('#district_id').select2();
        $('#locality_id').select2();

        // Inicialización del datepicker
        $('#sign_date').datepicker({
            language: 'es',
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
        });

        $('#locality_id').on('input', function() {
            $('#component_id').val($('#component_id option:first').val())
        .change(); // Establece la primera opción y dispara el evento change
        });

        // Cuando el usuario sale del input locality
        $('#locality_id').on('blur', function() {
            let url = $('#component_id').data('url'); // Obtener la URL desde el atributo data-url
            if (url) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log(response); // Manejar la respuesta aquí si es necesario
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al obtener los datos:', error);
                    }
                });
            }
        });
        
    });
    </script>
@endpush
