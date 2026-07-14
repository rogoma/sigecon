@extends('layouts.app')

{{-- Para usar sweet alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')
    <div class="pcoded-content">
        <div class="page-header card">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa fa-sitemap bg-c-blue"></i>
                        <div class="d-inline">
                            <h5>Órdenes</h5>
                            <span>Modificar Órdenes</span>
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
                                            <h4>Llamado:
                                                {{ $contract->description . ' - ' . $contract->modality->description . ' - ' . $contract->provider->description }}
                                                Modificar Orden del Contrato N° {{ $contract->number_year }}
                                            </h4>
                                        </div>


                                        <br><br>
                                        <label id="fecha_actual" name="fecha_actual"
                                            style="font-size: 20px;color: #FF0000;float: left;"
                                            for="fecha_actual">{{ Carbon\Carbon::now()->format('d/m/Y') }}</label>
                                        <h3 style="text-align: center;">Modificar Orden</h3>
                                    </div>
                                    <div class="card-block">
                                        <form method="POST"
                                            action="{{ route('contracts.orders.update', [$contract->id, $order->id]) }}"
                                            enctype="multipart/form-data" id="miForm">
                                            @csrf
                                            @method('PUT')

                                            <div class="container">
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                {{-- <div id="mensaje-componente" style="color: red;"></div> --}}
                                                <div id="mensaje-componente"
                                                    style="color: red; font-size: 18px; padding: 10px;"></div>
                                                <div id="mensaje-componente2"
                                                    style="color: red; font-size: 18px; padding: 10px;"></div>

                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label for="number" class="col-form-label">N° de Orden</label>
                                                        <input type="text" id="number" name="number_display"
                                                            class="form-control @error('number') is-invalid @enderror"
                                                            value="{{ old('number', $order->number) }}" maxlength="23"
                                                            disabled>
                                                        <input type="hidden" id="number_hidden" name="number"
                                                            value="{{ old('number', $order->number) }}">
                                                        @error('number')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <label for="total_amount" class="col-form-label">Monto</label>
                                                        <input type="text" id="total_amount" name="total_amount"
                                                            class="form-control @error('total_amount') is-invalid @enderror"
                                                            value="{{ old('total_amount', number_format($order->total_amount, 0, ',', '.')) }}"
                                                            maxlength="23" disabled>
                                                        @error('total_amount')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- <input type="hidden" name="department_id" value="{{ $order->locality_id }}"> --}}

                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label for="department_id"
                                                            class="col-form-label">Departamento</label>
                                                        <select id="department_id" name="department_id"
                                                            class="form-control @error('department_id') is-invalid @enderror">
                                                            <option value="">--- Seleccionar Departamento ---</option>
                                                            @foreach ($departments as $department)
                                                                <option value="{{ $department->id }}"
                                                                    @if ($department->id == old('department_id', $order->department_id)) selected @endif>
                                                                    {{ $department->description }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('department_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <input type="hidden" name="district_id" value="{{ $order->district_id }}">

                                                    <div class="col-sm-6">
                                                        <label for="district_id" class="col-form-label">Distrito</label>
                                                        <select id="district_id" name="district_id"
                                                            class="form-control @error('district_id') is-invalid @enderror"
                                                            disabled>
                                                            <option value="">--- Seleccionar Distrito ---</option>
                                                            @foreach ($districts as $district)
                                                                <option value="{{ $district->id }}"
                                                                    @if ($district->id == old('district_id', $order->district_id)) selected @endif>
                                                                    {{ $district->description }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('district_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <input type="hidden" name="locality_id" value="{{ $order->locality_id }}">

                                                    <div class="col-sm-12">
                                                        <label for="locality_id" class="col-form-label">Localidad</label>
                                                        <select id="locality_id" name="locality_id" class="form-control"
                                                            disabled>
                                                            <option value="">--- Seleccionar Localidad ---</option>
                                                            @foreach ($localities as $locality)
                                                                <option value="{{ $locality->id }}"
                                                                    @if ($locality->id == old('locality_id', $order->locality_id)) selected @endif>
                                                                    {{ $locality->description }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>                                                    
                                                </div>                                               

                                                <span style="color: red; font-size: 1.1rem; font-weight: bold">
                                                    Fecha de Orden: {{ $order->created_at->format('d/m/Y') }} 

                                                    @if ($order->order_state_id == 1)
                                                        - Estado de la Orden: 1- En curso
                                                    @endif
                                                    
                                                    @if ($order->order_state_id == 10)
                                                        - Estado de la Orden: 10 - Pendiente de carga de Fecha acuse de recibo Contratista
                                                    @endif
                                                    
                                                    @if ($order->order_state_id == 11)
                                                        - Estado de la Orden: 11 - Cargue Rubros de la Orden de Ejecución
                                                    @endif
                                                </span>

                                                <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label for="created_at" class="col-form-label">Fecha emisión de la orden</label>
                                                        <div class="input-group">
                                                            <input type="text"
                                                                id="created_at"
                                                                name="created_at"
                                                                class="form-control @error('created_at') is-invalid @enderror"
                                                                value="{{ old('created_at', $order->created_at ? $order->created_at->format('d/m/Y') : '') }}"
                                                                autocomplete="off"
                                                                {{-- Deshabilitar si no hay items o si el estado es distinto de 10 --}}
                                                                @if($order->items->count() == 0 || $order->order_state_id != 10) disabled @endif>

                                                            <span class="input-group-append">
                                                                <button type="button"
                                                                        class="btn btn-outline-secondary"
                                                                        onclick="show('created_at');"
                                                                        @if($order->items->count() == 0 || $order->order_state_id != 10) disabled @endif>
                                                                    <i class="fa fa-calendar"></i>
                                                                </button>
                                                            </span>
                                                        </div>

                                                        @error('created_at')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    {{-- HIDDEN PARA ENVIAR FECHA DE ORDEN PARA GRABAR --}}
                                                    <input type="hidden" name="created_at_hidd" value="{{ $order->created_at->format('d/m/Y') }}">

                                                    
                                                    <div class="col-sm-3">
                                                        <label for="sign_date" class="col-form-label">Fecha acuse recibo Contratista</label>
                                                        <div class="input-group">
                                                            <input type="text" id="sign_date" name="sign_date"
                                                                {{-- DISABLED INGRESO DE FECHA SI LA ORDEN NO TIENE DETALLE DE RUBROS --}}
                                                                class="form-control @error('sign_date') is-invalid @enderror"
                                                                @if ($order->items->count() > 0 && $order->order_state_id == 1)  @endif
                                                                value="{{ old('sign_date', !empty($order->sign_date) ? date('d/m/Y', strtotime($order->sign_date)) : '') }}"
                                                                autocomplete="off"
                                                                @if ($order->items->count() == 0) disabled @endif>
                                                            <span class="input-group-append">
                                                                <button type="button" class="btn btn-outline-secondary"
                                                                    onclick="show('sign_date');"
                                                                    {{ empty($order->sign_date) ? 'disabled' : '' }}>
                                                                    <i class="fa fa-calendar"></i>
                                                                </button>
                                                            </span>
                                                        </div>
                                                        @error('sign_date')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <label for="component_id"
                                                            class="col-form-label">Componente</label>
                                                        <!-- Campo oculto para enviar el valor cuando el select está deshabilitado -->
                                                        <input type="hidden" name="component_id" id="component_id_hidden"
                                                            value="{{ old('component_id', $order->component_id) }}">
                                                        <select id="component_id" name="component_id"
                                                            class="form-control @error('component_id') is-invalid @enderror" 
                                                            data-url="{{ route('getMaxNumber') }}">
                                                            @if ($order->items->count() > 0 && $order->order_state_id == 10)  @endif                                                            
                                                            onchange="confirmComponentChange(this)">
                                                            <option value="">--- Seleccionar Componente ---</option>
                                                            @foreach ($components as $component)
                                                                <option value="{{ $component->id }}"
                                                                    @if ($component->id == old('component_id', $order->component_id)) selected @endif>
                                                                    {{ $component->code }}-{{ $component->description }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>


                                                    <div class="col-sm-3">
                                                        <label for="plazo" class="col-form-label">Plazo de ejecución (En días)</label>
                                                        <input type="text" id="plazo" name="plazo"
                                                            class="form-control @error('plazo') is-invalid @enderror"
                                                            @if ($order->items->count() > 0 && $order->order_state_id == 1)  @endif
                                                            value="{{ old('plazo', $order->plazo) }}" maxlength="3">
                                                        @error('plazo')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>                                                    

                                                    <div class="col-sm-3">
                                                        <label for="sign_date_fin" class="col-form-label">Fecha final de Orden</label>
                                                        <div class="input-group">                                                            
                                                            <input 
                                                                type="text" 
                                                                id="sign_date_fin" 
                                                                name="sign_date_fin"                                                                
                                                                class="form-control @error('sign_date_fin') is-invalid @enderror"
                                                                value="{{ old('sign_date_fin', isset($order->sign_date_fin) ? date('d/m/Y', strtotime($order->sign_date_fin)) : '') }}"
                                                                autocomplete="off"
                                                                oninput="toggleFileUpload()"
                                                                @if ($order->sign_date === null) disabled @endif>                                                    
                                                                <span class="input-group-append">
                                                                    <button 
                                                                        type="button" 
                                                                        class="btn btn-outline-secondary" 
                                                                        onclick="show('sign_date_fin');" 
                                                                        {{ empty($order->sign_date_fin) ? 'disabled' : '' }}>
                                                                        <i class="fa fa-calendar"></i>
                                                                    </button>
                                                                </span>
                                                        </div>
                                                        @error('sign_date_fin')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    
                                                    {{-- <div class="form-group @error('file') has-danger @enderror">                                                        
                                                        <label class="col-form-label">Cargar Archivo: <h7>(Tipo de archivos permitidos: WORD, PDF)</h7></label>
                                                        <input id="file" type="file" class="form-control" name="file" disabled>
                                                        @error('file')
                                                            <div class="col-form-label">{{ $message }}</div>
                                                        @enderror
                                                    </div> --}}

                                                    <div class="col-sm-4">                                                    
                                                        <label class="col-form-label text-danger">Cargar Archivo: <h7>(Tipo:PDF,DOC,DOCX hasta 5MB)</h7></label>
                                                        <input id="file" type="file"  name="file"  class="form-control @error('file') has-danger @enderror">
                                                        @error('file')                                                            
                                                            <div class="col-form-label text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-5">
                                                        <label for="observation" class="col-form-label">Observación (Hasta 500 caracteres)</label>
                                                        <textarea id="observation" name="observation" class="form-control @error('observation') is-invalid @enderror"
                                                            maxlength="500" disabled>{{ old('observation', $order->observation) }}</textarea>
                                                        @error('observation')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    
                                                    <div class="col-sm-9">
                                                        <label for="reference" class="col-form-label">Referencia (Hasta 500 caracteres)</label>
                                                        <textarea id="reference" name="reference" class="form-control @error('reference') is-invalid @enderror"
                                                            maxlength="500">{{ old('reference', $order->reference) }}</textarea>
                                                        @error('reference')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <label for="comments" class="col-form-label">Comentarios (Hasta 500 caracteres)</label>
                                                        <textarea id="comments" name="comments" class="form-control @error('comments') is-invalid @enderror"
                                                            maxlength="500">{{ old('comments', $order->comments) }}</textarea>
                                                        @error('comments')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>                                                
                                            </div>
                                            
                                            <div class="col-sm-12">
                                                <br>
                                                <div class="form-group text-center">
                                                    <button id="guardar" type="submit"
                                                        class="btn btn-primary">Modificar Orden</button>
                                                </div>                                                
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

        // Manejo del cambio en el select component_id
        let select = document.getElementById("component_id");
        select.dataset.previousValue = select.value; // Guardamos el valor actual
        let orderStateId = {{ $order->order_state_id ?? 'null' }}; // Pasar el estado de la orden desde PHP

        // Evento para el cambio del componente
        $('#component_id').on('change', function() {
            // Controla el  cambio de componente y debe eliminar los detalles de orders_items
            let previousValue = this.dataset.previousValue; // Valor antes del cambio
            let newValue = this.value; // Nuevo valor seleccionado

            if (newValue !== previousValue) {
            // if (newValue !== previousValue && (orderStateId === 10 || orderStateId === 1)) {    
                    let selectedOptionText = this.options[this.selectedIndex].text;                   
                    let mensaje = "Cambiado por el Componente: " + selectedOptionText;
                    let mensaje2 = "Edite rubros para recalcular valores del nuevo Componente";
                    document.getElementById('mensaje-componente').textContent = mensaje;
                    document.getElementById('mensaje-componente2').textContent = mensaje2;                    
                    this.dataset.previousValue = newValue;
            }

            //Asigna un número de order de acuerdo a la localidad y tipo de componente
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
                        $('#district_id').prop('disabled', false);
                        $('#locality_id').prop('disabled', false);
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
        $('#component_id, #order_state_id, #department_id, #district_id, #locality_id').select2();
        
        
        // Inicializa el primer datepicker
        $('#sign_date').datepicker({
            language: 'es',
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
            startDate: "{{ $order->created_at->format('d/m/Y') }}",
            endDate: "today"
        }).on('changeDate', function (e) {
            // Cuando cambie, actualiza el mínimo del segundo
            $('#sign_date_fin').datepicker('setStartDate', e.date);
        });

        // Inicializa el segundo datepicker
        $('#sign_date_fin').datepicker({
            language: 'es',
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
            startDate: "{{ $order->created_at->format('d/m/Y') }}",
            endDate: "today"
        });

        // 🔑 Ajusta el mínimo del segundo usando el valor ya cargado en #sign_date
        var signDateValue = $('#sign_date').val();
        if (signDateValue) {
            // Convertir dd/mm/yyyy a objeto Date
            var parts = signDateValue.split('/');
            var initialDate = new Date(parts[2], parts[1] - 1, parts[0]);
            $('#sign_date_fin').datepicker('setStartDate', initialDate);
        }

        $('#created_at').datepicker({
                language: 'es',
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                endDate: "Today", // No permite fechas menores a la fecha de acuse de recibo
        });

        // $('#sign_date').on('changeDate', function (selected) {
        //     let minDate = new Date(selected.date.valueOf());
        //     $('#sign_date_fin').datepicker('setStartDate', minDate);
        // });
        
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

        // Control de habilitación de sign_date_fin
        function toggleSignDateFin() {
                let signDate = $('#sign_date');
                let signDateFin = $('#sign_date_fin');
                // signDateFin.prop('disabled', FALSE);

                if ($.trim(signDate.val()) === "") {
                    signDateFin.val("").prop('disabled', true);                    
                } else {                    
                    signDateFin.prop('disabled', false);
                }
            }

            // Ejecutar la función al cargar la página
            toggleSignDateFin();

            // Evento al cambiar sign_date
            $('#sign_date').on('change', toggleSignDateFin);

            // Evento al cambiar sign_date_fin (deshabilitar sign_date si se selecciona)
            // $('#sign_date_fin').on('change', function () {
            //     let signDate = $('#sign_date');
            //     if ($.trim($(this).val()) !== "") {
            //         signDate.prop('disabled', true);
            //     } else {
            //         signDate.prop('disabled', false);
            //     }
            // });

            // Control de habilitación del campo de archivo
            function toggleFileUpload() {
                let signDateFin = $('#sign_date_fin');
                let fileInput = $('#file');
                let textarea = $('#observation');

                if ($.trim(signDateFin.val()) !== '') {
                    fileInput.prop('disabled', false);
                    textarea.prop('disabled', false);
                    // textarea.val('');
                } else {
                    fileInput.prop('disabled', true);
                    textarea.prop('disabled', true);
                    textarea.val('');
                }
            }

            // Ejecutar la función al cargar la página para mantener el estado correcto
            toggleFileUpload();

            // Evento al cambiar sign_date_fin
            $('#sign_date_fin').on('change', toggleFileUpload);

            $('#file').bind('change', function() {
            max_upload_size = {{ $post_max_size }};
            if(this.files[0].size > max_upload_size){
                $('#guardar').attr("disabled", "disabled");
                file_size = Math.ceil((this.files[0].size/1024)/1024);
                max_allowed = Math.ceil((max_upload_size/1024)/1024);
                swal("Error!", "El tamaño del archivo seleccionado ("+file_size+" Mb) supera el tamaño maximo de carga permitido ("+max_allowed+" Mb).", "error");
            }else{
                $('#guardar').removeAttr("disabled");
            }
        });

        //Controla que no grabe si es que estado es 1 en curso  (que ya tiene fecha de acuse de recibo) y que se borre la fecha de acuse de recibo
        $('#guardar').on('click', function (e) {
            let orderStateId = {{ $order->order_state_id ?? 'null' }};
            let signDate = $('#sign_date');

            // Validación: si estado = 1 y sign_date vacío
            if (orderStateId === 1 && $.trim(signDate.val()) === "") {
                e.preventDefault(); // Bloquea el envío
                swal("Atención", "No se puede grabar la orden con el estado 'EN CURSO' y sin fecha de acuse recibo.", "warning");
                return false;
            }
        });        

        //CONTROLA QUE FECHA DE ORDEN NO ESTE VACIO PARA GRABAR
        $('#miForm').on('submit', function (e) {
        const valor = $('#created_at').val().trim();
        if (valor === '') {
            e.preventDefault(); // Evita el envío del formulario

                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Seleccione fecha para la orden antes de guardar.'
                }).then(() => {
                    $('#created_at').focus(); // Pone el cursor en el campo
                });
            }
        });

        //CONTROLA QUE NO TENGA FECHA ACUSE SIN FECHA DE ORDEN
        function toggleSignDate() {
            const createdVal = $('#created_at').val().trim();
            if (createdVal === '') {
                $('#sign_date').prop('disabled', true).val(''); // deshabilita y limpia
            } else {
                $('#sign_date').prop('disabled', false);       // habilita
            }
        }

        // Al cargar la página
        toggleSignDate();

        // Cada vez que cambie el campo created_at (datepicker o input)
        $('#created_at').on('change keyup', toggleSignDate);

    });
    </script>
@endpush