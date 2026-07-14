<!-- Incluye las librerías de Datepicker y jQuery -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-file-text bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Llamados</h5>
                        <span>Agregar Llamados</span>
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
                            <a href="{{ route('contracts.index') }}">Llamados</a>
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
                                    <h5>Agregar Llamado</h5>
                                    <br><br>
                                    <label id="fecha_actual" name="fecha_actual"  style="font-size: 20px;color: #FF0000;float: left;" for="fecha_actual">{{ Carbon\Carbon::now()->format('d/m/Y') }}</label>
                                    {{-- <label style="font-size: 20px;color: #FF0000;float: left;">FECHA: </label> --}}
                                </div>
                                <div class="card-block">
                                    <div class="form-group">
                                    <h3 class="text-center">Datos para cargar Llamado</h3>
                                    <form class="row" method="POST" action="{{ route('contracts.store') }}">
                                        @csrf
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

                                        {{-- #1 --}}
                                        <div class="col-sm-4">
                                            <div class="form-group @error('description') has-danger @enderror">
                                                <label class="col-form-label">Descripción</label>
                                                <textarea rows="2" id="description" name="description" class="form-control">{{ old('description') }}</textarea>
                                                @error('description')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- #2 --}}
                                        <div class="col-sm-2">
                                            <div class="form-group @error('iddncp') has-danger @enderror">
                                                <label class="col-form-label">ID DNCP</label>
                                                <input type="text" id="iddncp" name="iddncp" value="{{ old('iddncp') }}" class="form-control iddncp autonumber" data-a-sep="." data-a-dec=",">
                                                @error('iddncp')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- #3 --}}
                                        <div class="col-sm-6">
                                            <div class="form-group @error('linkdncp') has-danger @enderror">
                                                <label class="col-form-label">Link DNCP</label>
                                                <textarea rows="2" id="linkdncp" name="linkdncp" class="form-control">{{ old('linkdncp') }}</textarea>
                                                @error('linkdncp')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- #4 --}}
                                        <div class="col-sm-2">
                                            <div class="form-group @error('number_year') has-danger @enderror">
                                                <label class="col-form-label">N° Contrato/Año</label>
                                                <input type="text" id="number_year" name="number_year" maxlength="9" value= "{{ old('number_year') }}" class="form-control">
                                                @error('number_year')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- #5 --}}
                                        <div class="col-sm-2">
                                            <div class="form-group @error('year_adj') has-danger @enderror">
                                                <label class="col-form-label">AÑO </label>
                                                <input type="text" id="year_adj" name="year_adj" maxlength="4" value="{{ old('year_adj') }}" class="form-control">
                                                @error('year_adj')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- #6 --}}
                                        <div class="col-sm-2">
                                            <label class="col-form-label @error('sign_date') has-danger @enderror">Fecha Firma Contr.</label>
                                            <div class="input-group @error('sign_date') has-danger @enderror">
                                                <input type="text" id="sign_date" name="sign_date" value="{{ old('sign_date') }}" class="form-control" autocomplete="off">
                                                <span class="input-group-append" id="basic-addon">
                                                    <label class="input-group-text" onclick="show('sign_date');"><i class="fa fa-calendar"></i></label>
                                                </span>
                                            </div>
                                            @error('sign_date')
                                            <div class="has-danger">
                                                <div class="col-form-label">{{ $message }}</div>
                                            </div>
                                            @enderror
                                        </div>
                                        {{-- #7 --}}
                                        <div class="col-sm-4">
                                            <div class="form-group @error('provider_id') has-danger @enderror">
                                                <label class="col-form-label">Contratista</label>
                                                <select id="provider_id" name="provider_id" class="form-control">
                                                    <option value="">Seleccionar Contratista</option>
                                                @foreach ($providers as $provider)
                                                    <option value="{{ $provider->id }}" @if ($provider->id == old('provider_id')) selected @endif>{{ $provider->description }}</option>
                                                @endforeach
                                                </select>
                                                @error('provider_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- #8 --}}
                                        <div class="col-sm-2">
                                            <div class="form-group @error('contract_state_id') has-danger @enderror">
                                                <label class="col-form-label">Estado <br></label>
                                                <select id="contract_state_id" name="contract_state_id" class="form-control">
                                                    <option value="">Seleccionar Estado</option>
                                                @foreach ($contr_states as $state)
                                                    <option value="{{ $state->id }}" @if ($state->id == old('contract_state_id')) selected @endif>{{ $state->description }}</option>
                                                @endforeach
                                                </select>
                                                @error('contract_state_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- #9 --}}
                                        <div class="col-sm-3">
                                            <div class="form-group @error('modality_id') has-danger @enderror">
                                                <label class="col-form-label">Modalidad</label>
                                                <select id="modality_id" name="modality_id" class="form-control">
                                                    <option value="">Seleccionar Modalidad</option>
                                                @foreach ($modalities as $modality)
                                                    <option value="{{ $modality->id }}" @if ($modality->id == old('modality_id')) selected @endif>{{ $modality->description .' ('.$modality->code.')' }}</option>
                                                @endforeach
                                                </select>
                                                @error('modality_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- #10 --}}
                                        <div class="col-sm-3">
                                            <div class="form-group @error('financial_organism_id') has-danger @enderror">
                                                <label class="col-form-label">Organismo Financiador</label>
                                                <select id="financial_organism_id" name="financial_organism_id" class="form-control">
                                                    <option value="">Seleccionar Organismo Financiador</option>
                                                @foreach ($financial_organisms as $financial_organism)
                                                    <option value="{{ $financial_organism->id }}" @if ($financial_organism->id == old('financial_organism_id')) selected @endif>{{ $financial_organism->code.' - '.$financial_organism->description }}</option>
                                                @endforeach
                                                </select>
                                                @error('financial_organism_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- #11 --}}
                                        <div class="col-sm-2">
                                            <div class="form-group @error('contract_type_id') has-danger @enderror">
                                                <label class="col-form-label">Tipo</label>
                                                <select id="contract_type_id" name="contract_type_id" class="form-control">
                                                    <option value="">Seleccionar Tipo de Contrato</option>
                                                @foreach ($contract_types as $contract_type)
                                                    <option value="{{ $contract_type->id }}" @if ($contract_type->id == old('contract_type_id')) selected @endif>{{$contract_type->description }}</option>
                                                @endforeach
                                                </select>
                                                @error('contract_type_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- #12 --}}
                                        <div class="col-sm-2" style="padding-bottom: 10px;">
                                            <div class="form-group @error('minim_amount') has-danger @enderror">
                                                <label class="col-form-label">Monto Mínimo</label>
                                                <input type="text" id="minim_amount" name="minim_amount" value="{{ old('minim_amount') }}" class="form-control minim_amount autonumber" data-a-sep="." data-a-dec=",">
                                                @error('minim_amount')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2" style="padding-bottom: 10px;">
                                            <div class="form-group @error('total_amount') has-danger @enderror">
                                                <label class="col-form-label">Monto Máximo</label>
                                                <input type="text" id="total_amount" name="total_amount" value="{{ old('total_amount') }}" class="form-control total_amount autonumber" data-a-sep="." data-a-dec=",">
                                                @error('total_amount')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('open_contract') has-danger @enderror">
                                                <label class="col-form-label">Contrato (ABIERTO / CERRADO) <br></label>
                                                <select id="open_contract" name="open_contract" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach (array(1 => 'CONTRATO ABIERTO', 2 => 'CONTRATO CERRADO') as $index => $value)
                                                    <option value="{{ $index }}" @if ($index == old('open_contract')) selected @endif>{{ $value }}</option>                                                    
                                                @endforeach
                                                </select>
                                                @error('open_contract')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group @error('dependency_id') has-danger @enderror">
                                                <label class="col-form-label">Depedendencia Responsable </label>
                                                <select id="dependency_id" name="dependency_id" class="form-control">
                                                    <option value="">Seleccionar Depedendencia</option>
                                                @foreach ($dependencies as $dependency)
                                                    <option value="{{ $dependency->id }}" @if ($dependency->id == old('dependency_id')) selected @endif>{{ $dependency->description }}</option>
                                                @endforeach
                                                </select>
                                                @error('dependency_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- ORIGINAL NO BORRAR --}}
                                        {{-- <div class="col-sm-4">
                                            <div class="form-group @error('contract_admin_id') has-danger @enderror">
                                                <label class="col-form-label">Administrador del Contrato</label>
                                                <select id="contract_admin_id" name="contract_admin_id" class="form-control">
                                                    <option value="">Seleccionar Administrador del Contrato</option>
                                                @foreach ($dependencies as $dependency)
                                                    <option value="{{ $dependency->id }}" @if ($dependency->id == old('contract_admin_id')) selected @endif>{{ $dependency->description }}</option>
                                                @endforeach
                                                </select>
                                                @error('dependency_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div> --}}

                                        <div class="col-sm-4">
                                            <div class="form-group @error('contract_admin_id') has-danger @enderror">
                                                <label class="col-form-label">Administrador del Contrato</label>
                                                <select id="contract_admin_id" name="contract_admin_id" class="form-control">
                                                    <option value="">Seleccionar Administrador del Contrato</option>
                                                    @foreach ($users_admin as $user)
                                                        <option value="{{ $user->id}}" @if ($user->id == old('contract_admin_id')) selected @endif>{{ $user->name }} {{ $user->lastname }}</option>
                                                        
                                                    @endforeach
                                                </select>
                                                @error('contract_admin_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>                                        

                                        {{-- #13 --}}
                                        <div class="col-sm-12">
                                            <div class="form-group @error('comments') has-danger @enderror">
                                                <label class="col-form-label">Comentarios</label>
                                                <input type="text" id="comments" name="comments" maxlength="300" value="{{ old('comments') }}" class="form-control">
                                                @error('comments')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="container">

                                        <div class="col-sm-12">
                                            <br>
                                            <div class="form-group text-center">
                                                <button type="submit" class="btn btn-danger m-b-0 f-12">Guardar</button>
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

// Script para formatear el valor con separador de miles mientras se ingresa IDDNCP
document.getElementById('iddncp').addEventListener('input', function(event) {
    // Obtenemos el valor ingresado y eliminamos los separadores de miles existentes
    let monto = event.target.value.replace(/\./g, '');
    // Formateamos el valor con separador de miles
    monto = parseFloat(monto).toLocaleString('es-ES');
    // Actualizamos el valor en el input text
    event.target.value = monto;
});

// Script para formatear el valor con separador de miles mientras se ingresa MONTO MINIMO
document.getElementById('minim_amount').addEventListener('input', function(event) {
    // Obtenemos el valor ingresado y eliminamos los separadores de miles existentes
    let monto = event.target.value.replace(/\./g, '');
    // Formateamos el valor con separador de miles
    monto = parseFloat(monto).toLocaleString('es-ES');
    // Actualizamos el valor en el input text
    event.target.value = monto;
});

// Script para formatear el valor con separador de miles mientras se ingresa MONTO MAXIMO
document.getElementById('total_amount').addEventListener('input', function(event) {
    // Obtenemos el valor ingresado y eliminamos los separadores de miles existentes
    let monto = event.target.value.replace(/\./g, '');
    // Formateamos el valor con separador de miles
    monto = parseFloat(monto).toLocaleString('es-ES');
    // Actualizamos el valor en el input text
    event.target.value = monto;
});


$(document).ready(function(){

    $('#dependency').select2();
    $('#provider_id').select2();
    $('#modality_id').select2();
    $('#contract_state_id').select2();
    $('#contract_type_id').select2();
    $('#funding_source_id').select2();
    $('#financial_organism_id').select2();
    $('#dependency_id').select2();
    $('#contract_admin_id').select2();
    $('#open_contract').select2();


    // $('#expenditure_object').select2();

    $('#sign_date').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        // startDate: '-3d',
        // endDate: '+3d',
    });

    $('#advance_validity_from').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
    });

    $('#advance_validity_to').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        // startDate: 'today'
    });

    $('#fidelity_validity_from').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
    });
    $('#fidelity_validity_to').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        // startDate: 'today'
    });

    $('#accidents_validity_from').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
    });
    $('#accidents_validity_to').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        // startDate: 'today'
    });

    $('#risks_validity_from').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
    });
    $('#risks_validity_to').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        // startDate: 'today'
    });
    $('#civil_resp_validity_from').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
    });
    $('#civil_resp_validity_to').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        // startDate: 'today'
    });


    show = function(id){
        $('#'+id).datepicker('show');
    }

    //VALIDACIÓN DE FECHAS DE ANTICIPOS
    $('#advance_validity_from').on('changeDate', function() {
        var fechaInicio = $(this).datepicker('getDate').getTime();
        var fechaFin = $('#advance_validity_to').datepicker('getDate').getTime();

        if (fechaInicio === fechaFin){
            alert('La fecha final debe ser mayor a fecha de inicio');
            $('#advance_validity_to').datepicker('date', null); // Limpiar el datapicker
            $('#advance_validity_to').val('');
            $('#control_1').val('');
            $('#control_a').val('');
            return;
        }

        if (fechaFin == null){

        }else{
            if (fechaInicio > fechaFin) {
                alert('La fecha de inicio no puede ser mayor a la fecha final.');
                $('#advance_validity_to').datepicker('date', null); // Limpiar el datapicker
                $('#advance_validity_to').val('');
                $('#control_1').val('');
                $('#control_a').val('');
            }else{
                $('#advance_validity_to').datepicker('date', null); // Limpiar el datapicker
                $('#advance_validity_to').val('');
                $('#control_1').val('');
                $('#control_a').val('');

                //controla días para vigencia
                restaFechas = function(f1,f2)
                {
                    var aFecha1 = f1.split('/');
                    var aFecha2 = f2.split('/');
                    var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                    var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                    var dif = fFecha2 - fFecha1;
                    var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
                    return dias;
                }

                $('#control_1').val(restaFechas(f1,f2));
            }
        }
    });

    $('#advance_validity_to').on('changeDate', function() {
        var fechaInicio = $('#advance_validity_from').datepicker('getDate').getTime();
        var fechaFin = $(this).datepicker('getDate').getTime();

        if (fechaInicio === fechaFin) {
            alert('La fecha final debe ser mayor a fecha de inicio');
            $('#advance_validity_to').datepicker('date', null); // Limpiar el datapicker
            $('#advance_validity_to').val('');
            $('#control_1').val('');
            $('#control_a').val('');
            return;
        }

        if (fechaInicio > fechaFin) {
            alert('La fecha de inicio no puede ser mayor a la fecha final.');
            $('#advance_validity_to').datepicker('date', null); // Limpiar el datapicker
            $('#advance_validity_to').val('');
            $('#control_1').val('');
            $('#control_a').val('');
        }else{
            ///calcula dias de vigencia
            restaFechas = function(f1,f2)
            {
                var aFecha1 = f1.split('/');
                var aFecha2 = f2.split('/');
                var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                var dif = fFecha2 - fFecha1;
                var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
                return dias;
            }

            ///calcula dias que faltan para vencer
            restaFechas2 = function(f2,f3)
            {
                var aFecha1 = f3.split('/');
                var aFecha2 = f2.split('/');
                var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                var dif = fFecha2 - fFecha1;
                var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
                return dias;
            }

            var f1 = $('#advance_validity_from').val();//fecha dtpicker inicio
            var f2=  $('#advance_validity_to').val(); //fecha dtpicker final
            var f3= $('#fecha_actual').text();//fecha actual
            $('#control_1').val(restaFechas(f1,f2));//resultado fecha vigencia
            $('#control_a').val(restaFechas2(f2,f3));//resultado fecha días para vencer
        }
    });

    //VALIDACIÓN DE FECHAS DE FIEL CUMPLIMIENTO
    $('#fidelity_validity_from').on('changeDate', function() {
        var fechaInicio = $(this).datepicker('getDate').getTime();
        var fechaFin = $('#fidelity_validity_to').datepicker('getDate').getTime();

        if (fechaInicio === fechaFin){
            alert('La fecha final debe ser mayor a fecha de inicio');
            $('#fidelity_validity_to').datepicker('date', null); // Limpiar el datapicker
            $('#fidelity_validity_to').val('');
            $('#control_2').val('');
            $('#control_b').val('');
            return;
        }

        if (fechaFin == null){

        }else{
            if (fechaInicio > fechaFin) {
                alert('La fecha de inicio no puede ser mayor a la fecha final.');
                $('#fidelity_validity_to').datepicker('date', null); // Limpiar el datapicker
                $('#fidelity_validity_to').val('');
                $('#control_2').val('');
                $('#control_b').val('');
            }else{
                $('#fidelity_validity_to').datepicker('date', null); // Limpiar el datapicker
                $('#fidelity_validity_to').val('');
                $('#control_2').val('');
                $('#control_b').val('');

                //controla días para vigencia
                restaFechas = function(f1,f2)
                {
                    var aFecha1 = f1.split('/');
                    var aFecha2 = f2.split('/');
                    var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                    var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                    var dif = fFecha2 - fFecha1;
                    var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
                    return dias;
                }

                $('#control_2').val(restaFechas(f1,f2));

            }
        }
    });

    $('#fidelity_validity_to').on('changeDate', function() {
        var fechaInicio = $('#fidelity_validity_from').datepicker('getDate').getTime();
        var fechaFin = $(this).datepicker('getDate').getTime();

        if (fechaInicio === fechaFin){
            alert('La fecha final debe ser mayor a fecha de inicio');
            $('#fidelity_validity_to').datepicker('date', null); // Limpiar el datapicker
            $('#fidelity_validity_to').val('');
            $('#control_2').val('');
            $('#control_b').val('');
            return;
        }

        if (fechaInicio > fechaFin) {
            alert('La fecha de inicio no puede ser mayor a la fecha final.');
            $('#fidelity_validity_to').datepicker('date', null); // Limpiar el datapicker
            $('#fidelity_validity_to').val('');
            $('#control_2').val('');
            $('#control_b').val('');
        }else{
            ///calcula dias de vigencia
            restaFechas = function(f1,f2)
            {
                var aFecha1 = f1.split('/');
                var aFecha2 = f2.split('/');
                var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                var dif = fFecha2 - fFecha1;
                var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
                return dias;
            }

            ///calcula dias que faltan para vencer
            restaFechas2 = function(f2,f3)
            {
                var aFecha1 = f3.split('/');
                var aFecha2 = f2.split('/');
                var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                var dif = fFecha2 - fFecha1;
                var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
                return dias;
            }

            var f1 = $('#fidelity_validity_from').val();//fecha dtpicker inicio
            var f2=  $('#fidelity_validity_to').val(); //fecha dtpicker final
            var f3= $('#fecha_actual').text();//fecha actual
            $('#control_2').val(restaFechas(f1,f2));//resultado fecha vigencia
            $('#control_b').val(restaFechas2(f2,f3));//resultado fecha días para vencer
        }
    });

    //VALIDACIÓN DE FECHAS DE ACCIDENTES PERSONALES
    $('#accidents_validity_from').on('changeDate', function() {
        var fechaInicio = $(this).datepicker('getDate').getTime();
        var fechaFin = $('#accidents_validity_to').datepicker('getDate').getTime();

        if (fechaInicio === fechaFin){
            alert('La fecha final debe ser mayor a fecha de inicio');
            $('#accidents_validity_to').datepicker('date', null); // Limpiar el datapicker
            $('#accidents_validity_to').val('');
            $('#control_3').val('');
            $('#control_c').val('');
            return;
        }

        if (fechaFin == null){

        }else{
            if (fechaInicio > fechaFin) {
                alert('La fecha de inicio no puede ser mayor a la fecha final.');
                $('#accidents_validity_to').datepicker('date', null); // Limpiar el datapicker
                $('#accidents_validity_to').val('');
                $('#control_3').val('');
                $('#control_c').val('');
            }else{
                $('#accidents_validity_to').datepicker('date', null); // Limpiar el datapicker
                $('#accidents_validity_to').val('');
                $('#control_3').val('');
                $('#control_c').val('');

                //controla días para vigencia
                restaFechas = function(f1,f2)
                {
                    var aFecha1 = f1.split('/');
                    var aFecha2 = f2.split('/');
                    var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                    var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                    var dif = fFecha2 - fFecha1;
                    var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
                    return dias;
                }

                $('#control_3').val(restaFechas(f1,f2));

            }
        }
    });

    $('#accidents_validity_to').on('changeDate', function() {
        var fechaInicio = $('#accidents_validity_from').datepicker('getDate').getTime();
        var fechaFin = $(this).datepicker('getDate').getTime();

        if (fechaInicio === fechaFin){
            alert('La fecha final debe ser mayor a fecha de inicio');
            $('#accidents_validity_to').datepicker('date', null); // Limpiar el datapicker
            $('#accidents_validity_to').val('');
            $('#control_3').val('');
            $('#control_c').val('');
            return;
        }

        if (fechaInicio > fechaFin) {
            alert('La fecha de inicio no puede ser mayor a la fecha final.');
            $('#accidents_validity_to').datepicker('date', null); // Limpiar el datapicker
            $('#accidents_validity_to').val('');
            $('#control_3').val('');
            $('#control_c').val('');
        }else{

            ///calcula dias de vigencia
            restaFechas = function(f1,f2)
            {
                var aFecha1 = f1.split('/');
                var aFecha2 = f2.split('/');
                var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                var dif = fFecha2 - fFecha1;
                var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
                return dias;
            }

            ///calcula dias que faltan para vencer
            restaFechas2 = function(f2,f3)
            {
                var aFecha1 = f3.split('/');
                var aFecha2 = f2.split('/');
                var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                var dif = fFecha2 - fFecha1;
                var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
                return dias;
            }

            var f1 = $('#accidents_validity_from').val();//fecha dtpicker inicio
            var f2=  $('#accidents_validity_to').val(); //fecha dtpicker final
            var f3= $('#fecha_actual').text();//fecha actual
            $('#control_3').val(restaFechas(f1,f2));//resultado fecha vigencia
            $('#control_c').val(restaFechas2(f2,f3));//resultado fecha días para vencer
        }
    });

    //VALIDACIÓN DE FECHAS DE TODO RIESGO
    $('#risks_validity_from').on('changeDate', function() {
        var fechaInicio = $(this).datepicker('getDate').getTime();
        var fechaFin = $('#risks_validity_to').datepicker('getDate').getTime();

        if (fechaInicio === fechaFin){
            alert('La fecha final debe ser mayor a fecha de inicio');
            $('#risks_validity_to').datepicker('date', null); // Limpiar el datapicker
            $('#risks_validity_to').val('');
            $('#control_4').val('');
            $('#control_d').val('');
            return;
        }

        if (fechaFin == null){

        }else{
            if (fechaInicio > fechaFin) {
                alert('La fecha de inicio no puede ser mayor a la fecha final.');
                $('#risks_validity_to').datepicker('date', null); // Limpiar el datapicker
                $('#risks_validity_to').val('');
                $('#control_4').val('');
                $('#control_d').val('');
            }else{
                $('#risks_validity_to').datepicker('date', null); // Limpiar el datapicker
                $('#risks_validity_to').val('');
                $('#control_4').val('');
                $('#control_d').val('');

                //controla días para vigencia
                restaFechas = function(f1,f2)
                {
                    var aFecha1 = f1.split('/');
                    var aFecha2 = f2.split('/');
                    var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                    var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                    var dif = fFecha2 - fFecha1;
                    var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
                    return dias;
                }

                $('#control_4').val(restaFechas(f1,f2));

            }
        }
    });

    $('#risks_validity_to').on('changeDate', function() {
        var fechaInicio = $('#risks_validity_from').datepicker('getDate').getTime();
        var fechaFin = $(this).datepicker('getDate').getTime();

        if (fechaInicio === fechaFin){
            alert('La fecha final debe ser mayor a fecha de inicio');
            $('#risks_validity_to').datepicker('date', null); // Limpiar el datapicker
            $('#risks_validity_to').val('');
            $('#control_4').val('');
            $('#control_d').val('');
            return;
        }

        if (fechaInicio > fechaFin) {
            alert('La fecha de inicio no puede ser mayor a la fecha final.');
            $('#risks_validity_to').datepicker('date', null); // Limpiar el datapicker
            $('#risks_validity_to').val('');
            $('#control_4').val('');
            $('#control_d').val('');
        }else{

            ///calcula dias de vigencia
            restaFechas = function(f1,f2)
            {
                var aFecha1 = f1.split('/');
                var aFecha2 = f2.split('/');
                var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                var dif = fFecha2 - fFecha1;
                var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
                return dias;
            }

            ///calcula dias que faltan para vencer
            restaFechas2 = function(f2,f3)
            {
                var aFecha1 = f3.split('/');
                var aFecha2 = f2.split('/');
                var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                var dif = fFecha2 - fFecha1;
                var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
                return dias;
            }

            var f1 = $('#risks_validity_from').val();//fecha dtpicker inicio
            var f2=  $('#risks_validity_to').val(); //fecha dtpicker final
            var f3= $('#fecha_actual').text();//fecha actual
            $('#control_4').val(restaFechas(f1,f2));//resultado fecha vigencia
            $('#control_d').val(restaFechas2(f2,f3));//resultado fecha días para vencer
        }
    });

    //VALIDACIÓN DE FECHAS DE RESPONSABILIDAD CIVIL
    $('#civil_resp_validity_from').on('changeDate', function() {
        var fechaInicio = $(this).datepicker('getDate').getTime();
        var fechaFin = $('#civil_resp_validity_to').datepicker('getDate').getTime();

        if (fechaInicio === fechaFin){
            alert('La fecha final debe ser mayor a fecha de inicio');
            $('#civil_resp_validity_to').datepicker('date', null); // Limpiar el datapicker
            $('#civil_resp_validity_to').val('');
            $('#control_5').val('');
            $('#control_e').val('');
            return;
        }

        if (fechaFin == null){

        }else{
            if (fechaInicio > fechaFin) {
                alert('La fecha de inicio no puede ser mayor a la fecha final.');
                $('#civil_resp_validity_to').datepicker('date', null); // Limpiar el datapicker
                $('#civil_resp_validity_to').val('');
                $('#control_5').val('');
                $('#control_e').val('');
            }else{
                $('#civil_resp_validity_to').datepicker('date', null); // Limpiar el datapicker
                $('#civil_resp_validity_to').val('');
                $('#control_5').val('');
                $('#control_e').val('');

                //controla días para vigencia
                restaFechas = function(f1,f2)
                {
                    var aFecha1 = f1.split('/');
                    var aFecha2 = f2.split('/');
                    var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                    var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                    var dif = fFecha2 - fFecha1;
                    var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
                    return dias;
                }

                $('#control_5').val(restaFechas(f1,f2));

            }
        }
    });

    $('#civil_resp_validity_to').on('changeDate', function() {
        var fechaInicio = $('#civil_resp_validity_from').datepicker('getDate').getTime();
        var fechaFin = $(this).datepicker('getDate').getTime();

        if (fechaInicio === fechaFin){
            alert('La fecha final debe ser mayor a fecha de inicio');
            $('#civil_resp_validity_to').datepicker('date', null); // Limpiar el datapicker
            $('#civil_resp_validity_to').val('');
            $('#control_5').val('');
            $('#control_e').val('');
            return;
        }

        if (fechaInicio > fechaFin) {
            alert('La fecha de inicio no puede ser mayor a la fecha final.');
            $('#civil_resp_validity_to').datepicker('date', null); // Limpiar el datapicker
            $('#civil_resp_validity_to').val('');
            $('#control_5').val('');
            $('#control_e').val('');
        }else{

            ///calcula dias de vigencia
            restaFechas = function(f1,f2)
            {
                var aFecha1 = f1.split('/');
                var aFecha2 = f2.split('/');
                var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                var dif = fFecha2 - fFecha1;
                var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
                return dias;
            }

            ///calcula dias que faltan para vencer
            restaFechas2 = function(f2,f3)
            {
                var aFecha1 = f3.split('/');
                var aFecha2 = f2.split('/');
                var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                var dif = fFecha2 - fFecha1;
                var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
                return dias;
            }

            var f1 = $('#civil_resp_validity_from').val();//fecha dtpicker inicio
            var f2=  $('#civil_resp_validity_to').val(); //fecha dtpicker final
            var f3= $('#fecha_actual').text();//fecha actual
            $('#control_5').val(restaFechas(f1,f2));//resultado fecha vigencia
            $('#control_e').val(restaFechas2(f2,f3));//resultado fecha días para vencer
        }
    });
});
</script>
@endpush
