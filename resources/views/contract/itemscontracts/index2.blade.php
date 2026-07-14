@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/datatables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/buttons.datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/responsive.bootstrap4.min.css') }}">

    <style>
        #items td,
        #items th {
            padding: 4px 8px;
            vertical-align: middle;
            border-left: 1px solid #ddd;
            /* Línea vertical a la izquierda */
            border-right: 1px solid #ddd;
            /* Línea vertical a la derecha */
        }

        #items {
            border-collapse: collapse;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }
    </style>
@endpush

@section('content')
    <div class="pcoded-content">
        <div class="page-header card">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa fa-list bg-c-blue"></i>
                        <div class="d-inline">
                            <h5 class="text-danger">Contrato N°: {{ $contract->description }}</h5>
                            <h5 class="text-danger">
                                Detalle de Rubros de Componente: {{ $items[0]->component->code }} -
                                {{ $items[0]->component->description }}
                            </h5>
                            <h5 class="text-danger">Localidad: {{ $order->locality }}</h5>
                            <label for="order_id">Order ID:</label>
                            <input type="text" id="order_id" value="{{ $order->id }}" readonly>
                            <input type="text" id="creator_user_id" value="{{ Auth::user()->id }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="page-header-breadcrumb">
                        <ul class="breadcrumb breadcrumb-title">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('contracts.volver', $contract->id) }}">Órdenes</a>
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
                                        <h4 class="text-primary float-left">
                                            Rubros para procesar en la Orden de Ejecución N°: {{ $order->number }}
                                        </h4>
                                    </div>
                                    <div class="card-block">
                                        <div class="dt-responsive table-responsive">
                                            <table id="myDataTable" class="display" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>#Item</th>
                                                        <th>Rubro ID</th>
                                                        <th>Cantidad</th>
                                                        <th>Precio UNIT MO</th>
                                                        <th>Precio UNIT MAT</th>
                                                        <th>Precio Total MO</th>
                                                        <th>Precio Total MAT</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="item_number">1</td>
                                                        <td class="rubro-id">101</td>
                                                        <td class="quantity">2</td>
                                                        <td class="price-unit-mo">550000</td>
                                                        <td class="price-unit-mat">302500</td>
                                                        <td class="price-total-mo">10074500</td>
                                                        <td class="price-total-mat">695000</td>
                                                    </tr>  
                                                    <tr>
                                                        <td class="item_number">2</td>
                                                        <td class="rubro-id">101</td>
                                                        <td class="quantity">2</td>
                                                        <td class="price-unit-mo">550000</td>
                                                        <td class="price-unit-mat">302500</td>
                                                        <td class="price-total-mo">10074500</td>
                                                        <td class="price-total-mat">695000</td>
                                                    </tr>    
                                                    {{-- @php
                                                    $tot_price_mo = 0;
                                                    $tot_price_mat = 0;
                                                @endphp
                                                @foreach ($items as $i => $item)
                                                    <tr>
                                                        @if ($item->rubro_id == '9999')
                                                            <td colspan="9" class="font-weight-bold">{{ $item->subitem->description }}</td>
                                                        @else
                                                            <td class="text-center" id="item_number">{{ $item->item_number }}</td>
                                                            <td class="d-none" id="rubro-id">{{ $item->rubro->id }}</td>
                                                            <td>{{ $item->rubro->code }} - {{ $item->rubro->description }}</td>
                                                            <td class="text-center" id="quantity">
                                                                <input type="number" class="quantity" data-index="{{ $i }}" value="0" min="0" step="any" style="width: 80px; text-align: center;">
                                                            </td>
                                                            <td class="text-center">{{ $item->rubro->orderPresentations->description }}</td>
                                                            <td class="text-center" id="price-unit-mo{{ $i }}">{{ number_format($item->unit_price_mo, 0, ',', '.') }}</td>
                                                            <td class="text-center" id="price-unit-mat{{ $i }}">{{ number_format($item->unit_price_mat, 0, ',', '.') }}</td>
                                                            <td class="text-center" id="price-total-mo_{{ $i }}">0</td>
                                                            <td class="text-center" id="price-total-mat_{{ $i }}">0</td>
                                                        @endif
                                                    </tr>
                                                @endforeach --}}
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5"></td>
                                                        <td class="font-weight-bold text-danger bg-warning text-center">
                                                            TOTALES:</td>
                                                        <td class="font-weight-bold text-danger bg-warning text-center"
                                                            id="tot_price_mo"></td>
                                                        <td class="font-weight-bold text-danger bg-warning text-center"
                                                            id="tot_price_mat"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <div class="text-center">
                                                @if (in_array($contract->contract_state_id, [1, 2]))
                                                    <button id="saveButton" class="btn btn-primary">Guardar datos</button>
                                                @endif
                                            </div>
                                        </div>
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



{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> --}}
{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

<script>

var $jq = jQuery.noConflict();
$jq(document).ready(function () {
    $jq('#myDataTable').DataTable();
});


    // Inicializar DataTable
    $(document).ready(function() {
        $('#myDataTable').DataTable();
    });

    // Guardar datos con AJAX
    $('#saveButton').click(function() {
        const items = [];
        const orderId = $('#order_id').val();
        const creator_user_Id = $('#creator_user_id').val();

        $('#myDataTable tbody tr').each(function() {
            const row = $(this);
            items.push({
                item_number: row.find('.item_number').text(),
                rubro_id: row.find('.rubro-id').text(),
                quantity: parseFloat(row.find('.quantity').text()),
                unit_price_mo: parseFloat(row.find('.price-unit-mo').text()),
                unit_price_mat: parseFloat(row.find('.price-unit-mat').text()),
                tot_price_mo: parseFloat(row.find('.price-total-mo').text()),
                tot_price_mat: parseFloat(row.find('.price-total-mat').text()),
            });
        });

        $.ajax({
            url: '/item-orders',
            type: 'POST',
            data: {
                items: items,
                order_id: orderId, 
                creator_user_id: creator_user_Id,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(response) {
                alert(response.message);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            },
        });
    });
</script>


{{-- <script>
    $(document).ready(function() {
        // Función para actualizar los totales generales
        function updateTotals() {
            let totalMo = 0;
            let totalMat = 0;

            $(".quantity").each(function() {
                let index = $(this).data("index");

                // Verificar que los elementos existan antes de acceder a ellos
                let totalMoText = $("#total_mo_" + index).text()?.replace(/\./g, '').replace(',',
                    '.') || "0";
                let totalMatText = $("#total_mat_" + index).text()?.replace(/\./g, '').replace(',',
                    '.') || "0";

                totalMo += parseFloat(totalMoText) || 0;
                totalMat += parseFloat(totalMatText) || 0;
            });

            // Actualizar totales en el footer
            $("#tot_price_mo").text(totalMo.toLocaleString('es-ES'));
            $("#tot_price_mat").text(totalMat.toLocaleString('es-ES'));
        }

        // Evento para actualizar los totales por fila
        $(".quantity").on("input", function() {
            let index = $(this).data("index");
            let quantity = parseFloat($(this).val()) || 0;

            // Verificar que los precios unitarios existan antes de acceder a ellos
            let unitPriceText = $("#unit_price_mo_" + index).text()?.replace(/\./g, '').replace(',',
                '.') || "0";
            let unitPrice2Text = $("#unit_price_mat_" + index).text()?.replace(/\./g, '').replace(',',
                '.') || "0";

            let unitPrice = parseFloat(unitPriceText) || 0;
            let unitPrice2 = parseFloat(unitPrice2Text) || 0;

            // Calcular los totales por fila
            let total = Math.round(quantity * unitPrice);
            let total2 = Math.round(quantity * unitPrice2);

            // Actualizar los valores de las celdas de total
            $("#total_mo_" + index).text(total.toLocaleString('es-ES'));
            $("#total_mat_" + index).text(total2.toLocaleString('es-ES'));

            // Actualizar los totales generales
            updateTotals();
        });

        // Llamar a la función para calcular totales al cargar la página
        updateTotals();

        // Inicializar DataTable
        $('#myDataTable').DataTable();

        // Guardar datos con AJAX
        $('#saveButton').click(function() {
            const items = [];
            const orderId = $('#order_id').val();
            const creator_user_Id = $('#creator_user_id').val();

            $('#myDataTable tbody tr').each(function() {
                const row = $(this);
                items.push({
                    item_number: row.find('.item_number').text(),
                    rubro_id: row.find('.rubro-id').text(),
                    quantity: parseFloat(row.find('.quantity').text()),
                    unit_price_mo: parseFloat(row.find('.price-unit-mo').text()),
                    unit_price_mat: parseFloat(row.find('.price-unit-mat').text()),
                    tot_price_mo: parseFloat(row.find('.price-total-mo').text()),
                    tot_price_mat: parseFloat(row.find('.price-total-mat').text()),
                });
            });

            $.ajax({
                url: '/item-orders',
                type: 'POST',
                data: {
                    items: items,
                    order_id: orderId,
                    creator_user_id: creator_user_Id,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    alert(response.message);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                },
            });
        });
    });
</script> --}}
