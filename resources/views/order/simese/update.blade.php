@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Pedidos</h5>
                        <span>Modificar Documentos Relacionados</span>
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
                            <a href="{{ route('orders.show', $order->id) }}">Pedido Nº {{ $order->id }}</a>
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
                                    <h5>Modificar Documentos Relacionados</h5>
                                </div>
                                <div class="card-block">
                                    <form id="formEdit" method="POST">

                                        <div class="row col-sm-12">
                                            <label class="col-form-label">Documentos SIMESE cargados por otras dependencias</label>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Año</th>
                                                        <th>Nro. SIMESE</th>
                                                        <th>Dependencia</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @for ($i=0; $i < count($related_simese); $i++)
                                                    <tr>
                                                        <td>{{ $i+1 }}</td>
                                                        <td>{{ $related_simese[$i]->year }}</td>
                                                        <td>{{ $related_simese[$i]->simese }}</td>
                                                        <td>{{ $related_simese[$i]->dependency->description }}</td>
                                                    </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                        </div>

                                        <div id="simese_orders" class="row form-group m-b-30">
                                            <label class="col-sm-6 col-form-label">Documentos SIMESE relacionados</label>
                                            <div class="col-sm-3 text-right m-b-20">
                                                <button type="button" id="addRow" title="Agregar Nueva Fila" class="btn btn-sm btn-success">
                                                    <span class="f-18">+</span>
                                                </button>
                                            </div>
                                            {{-- Documentos relacionados cargados --}}
                                            @foreach($related_simese_user as $simese)
                                            <div id="template" class="w-100">
                                                <div class="col-sm-12 row m-b-20">
                                                    <div class="col-sm-4 row">
                                                        <label class="col-sm-4 col-form-label">Año</label>
                                                        <div class="col-sm-8">
                                                            <select id="year" name="year[]" class="form-control year">
                                                            @for($year = date('Y'); $year > date('Y') - 5; $year--)
                                                                <option value="{{ $year }}" @if($year == $simese->year) selected @endif>{{ $year }}</option>
                                                            @endfor
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6 row">
                                                        <label class="col-sm-4 col-form-label">Nro. SIMESE</label>
                                                        <div class="col-sm-8">
                                                            <input type="number" id="simese" name="simese[]" class="form-control simese" value="{{ $simese->simese }}">
                                                        </div>
                                                    </div>

                                                    {{-- En caso de no ser el primer registro agregamos botón de eliminación --}}
                                                    <div class="col-sm-2 text-right">
                                                        <button type="button" title="Borrar Fila" onclick="delRow(this);" class="btn btn-sm btn-danger">
                                                            <span class="f-20">-</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>

                                        <div id="template" class="template w-100 d-none">
                                            <div class="col-sm-12 row m-b-20">
                                                <div class="col-sm-4 row">
                                                    <label class="col-sm-4 col-form-label">Año</label>
                                                    <div class="col-sm-8">
                                                        <select id="year" class="form-control">
                                                        @for($year = date('Y'); $year > date('Y') - 5; $year--)
                                                            <option value="{{ $year }}">{{ $year }}</option>
                                                        @endfor
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 row">
                                                    <label class="col-sm-4 col-form-label">Nro. SIMESE</label>
                                                    <div class="col-sm-8">
                                                        <input type="number" id="simese" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-sm-2 text-right">
                                                    <button type="button" title="Borrar Fila" onclick="delRow(this);" class="btn btn-sm btn-danger">
                                                        <span class="f-20">-</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="error" class="col-sm-12 d-none">
                                            <div class="alert alert-danger">
                                                <span id="error_message"></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 text-center">
                                            <button type="submit" class="btn btn-warning">Modificar</button>
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

    $('#addRow').click(function(){
        // clonamos el template creado
        new_row = $('.template').clone();
        new_row.removeClass('template d-none');
        // agregamos los name para el envio del formulario
        new_row.find('#year').attr('name', 'year[]');
        new_row.find('#year').addClass('year');
        new_row.find('#simese').attr('name', 'simese[]');
        new_row.find('#simese').addClass('simese');
        // agregamos el template creado al html
        $('#simese_orders').append(new_row);
    });
    delRow = function(element){
        element.closest('#template').remove();
    }

    $('#formEdit').submit(function(e){
        e.preventDefault();

        // VALIDACIONES
        error = false;
        years = document.getElementsByClassName('year');
        simeses = document.getElementsByClassName('simese');
        $('.year').each(function(index,element){
            // chequeamos que el año sea numerico y obligatorio
            if(isNaN(years[index].value) || years[index].value == ""){
                $('#error_message').html('El campo Año de Documentos SIMESE Relacionados en la fila '+(index+1)+' no debe estar vacío.');
                $('#error').removeClass('d-none');
                error = true;
                return false;
            }

            if(isNaN(simeses[index].value) || simeses[index].value == ""){
                $('#error_message').html('El campo Nro. SIMESE de Documentos SIMESE Relacionados en la fila '+(index+1)+' no debe estar vacío.');
                $('#error').removeClass('d-none');
                error = true;
                return false;
            }

            if(simeses[index].value <= 0){
                $('#error_message').html('El campo Nro. SIMESE de Documentos SIMESE Relacionados en la fila '+(index+1)+' no debe ser menor o igual a 0.');
                $('#error').removeClass('d-none');
                error = true;
                return false;
            }

            if(simeses[index].value > 999999){
                $('#error_message').html('El campo Nro. SIMESE de Documentos SIMESE Relacionados en la fila '+(index+1)+' no puede ser mayor a 999.999');
                $('#error').removeClass('d-none');
                error = true;
                return false;
            }
        });

        // SE PASARON TODAS LAS VALIDACIONES, enviamos los datos
        if(!error){
            $.ajax({
                url: "{{ route('orders.simese.update', $order->id) }}",
                method: 'PUT',
                data: $('#formEdit').serialize()+'&_token={{ csrf_token() }}',
                success: function(data){
                    try{
                        response = (typeof data == "object") ? data : JSON.parse(data);
                        if(response.status == "success"){
                            location.href = "{{ $show_route }}";
                        }else{
                            swal("Error!", response.message, "error");
                        }
                    }catch(error){
                        swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                        console.log(error);
                    }
                },
                error: function(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
            });
        }
    });

});
</script>
@endpush
