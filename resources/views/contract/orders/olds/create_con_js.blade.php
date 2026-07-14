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
                                <input type="hidden" id="contract_id" name="contract_id" value="{{ $contract->id }}">
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
                                                            class="form-control"
                                                            value="{{ old('number', $nextContractNumber) }}" maxlength="23"
                                                            disabled>
                                                        <input type="hidden" id="number_hidden" name="number"
                                                            value="{{ old('number', $nextContractNumber) }}">
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <label for="total_amount" class="col-form-label">Monto</label>
                                                        <input type="text" id="total_amount" name="total_amount"
                                                            class="form-control" value="{{ old('total_amount', 0) }}"
                                                            maxlength="23" disabled>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="component_id" class="col-form-label">Componente</label>
                                                    <select id="component_id" name="component_id" class="form-control">
                                                        <option value="">--- Seleccionar Componente ---</option>
                                                        @foreach ($components as $component)
                                                            <option value="{{ $component->id }}">{{ $component->code }} -
                                                                {{ $component->description }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <br>
                                            <div class="text-center mt-4">
                                                <button id="showItemsButton" type="button" class="btn btn-primary">Mostrar
                                                    Rubros</button>
                                            </div>
                                            <br>
                                        </form>
                                    </div>

                                    <!-- Tabla para mostrar los items -->
                                    <div class="container mt-4">
                                        <table id="itemsTable" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Descripción</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                    <!-- Agregar jQuery y DataTables -->
                                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
                                    <link rel="stylesheet"
                                        href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

                                    <script>
                                        $(document).ready(function() {

                                            $('#component_id').select2();

                                            let table = $('#itemsTable').DataTable({
                                                columns: [{
                                                        data: 'id'
                                                    },
                                                    {
                                                        data: 'quantity',
                                                        render: function(data, type, row) {
                                                            if (type === 'display') {
                                                                return data.toLocaleString(); // Formato con separador de miles
                                                            }
                                                            return data; // Para otros tipos (ej. ordenamiento, búsqueda)
                                                        }
                                                    },
                                                    {
                                                        data: 'unit_price_mo',
                                                        render: function(data, type, row) {
                                                            if (type === 'display') {
                                                                return data.toLocaleString(); // Formato con separador de miles
                                                            }
                                                            return data; // Para otros tipos (ej. ordenamiento, búsqueda)
                                                        }
                                                    },
                                                    {
                                                        data: 'unit_price_mat'
                                                    }
                                                ]
                                            });

                                            $('#showItemsButton').on('click', function() {
                                                let componentId = $('#component_id').val();
                                                let contractId = '{{ $contract->id }}'; // Obtiene el ID del contrato desde Blade

                                                if (!componentId) {
                                                    alert('Seleccione un componente primero.');
                                                    return;
                                                }

                                                $.ajax({
                                                    url: '{{ route('get.items') }}',
                                                    type: 'GET',
                                                    data: {
                                                        component_id: componentId,
                                                        contract_id: contractId
                                                    },
                                                    success: function(response) {
                                                        table.clear().rows.add(response.data).draw();
                                                    },
                                                    error: function(xhr) {
                                                        console.log(xhr.responseText);
                                                        alert('Error al obtener los datos.');
                                                    }
                                                });
                                            });

                                        });
                                    </script>
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
