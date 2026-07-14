<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTable Example</title>
    <!-- Agregar enlaces a DataTables y jQuery -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <h3>Agregar Items para la Orden</h3>
    <div>
        <label for="order_id">Order ID:</label>
        <input type="text" id="order_id" value="{{ $order->id }}" readonly>
        <input type="text" id="creator_user_id" value="{{ Auth::user()->id }}" readonly>
    </div>

    {{-- <table id="myDataTable" class="display" style="width:100%"> --}}
    <table id="items" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#Item</th>
                <th>Rubro ID</th>
                <th>Cod.- Rubro</th>
                <th>Cantidad</th>
                <th>Unidad Med.</th>
                <th>Precio UNIT MO</th>
                <th>Precio UNIT MAT</th>
                <th>Precio Total MO</th>
                <th>Precio Total MAT</th>
            </tr>
        </thead>
        <tbody>

            @php
                $tot_price_mo = 0;
                $tot_price_mat = 0;
            @endphp

            @foreach ($items as $i => $item)
                <tr>
                    @if ($item->rubro_id == '9999')
                        <td colspan="2"></td>
                        <td style="font-size: 16px; font-weight: bold;">{{ $item->subitem->description }}</td>
                        <td colspan="6"></td>
                    @else
                        <td class="item_number" style="text-align: center;">{{ $item->item_number }}</td>
                        <td class="rubro-id" style="text-align: center;">{{ $item->rubro->id }}</td>

                        <td class="rubro" style="text-align: left;">
                            {{ $item->rubro->code }}-{{ $item->rubro->description }}</td>

                        <td class="quantity" style="text-align: center;">
                                <input type="number" data-index="{{ $i }}" value="0" min="0" required
                                    step="any" style="width: 80px; text-align: center;"
                                    oninput="if (this.value === '' || this.value < 0) this.value = 0;">
                        </td>                         

                        <td class="unidad" style="text-align: center;">
                            {{ $item->rubro->orderPresentations->description }}</td>

                        <td class="price-unit-mo" style="text-align: center;" 
                            data-value="{{ $item->unit_price_mo }}">
                            {{ number_format($item->unit_price_mo, 0, ',', '.') }}
                        </td>

                        <td class="price-unit-mat" style="text-align: center;" 
                            data-value="{{ $item->unit_price_mat }}">
                            {{ number_format($item->unit_price_mat, 0, ',', '.') }}
                        </td>

                        <td class="price-total-mo" style="text-align: center;" data-value="0">0</td>

                        <td class="price-total-mat" style="text-align: center;" data-value="0">0</td>
                    @endif
                </tr>
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" style="text-align: right; font-weight: bold;">Totales:</td>
                <td style="text-align: center; font-weight: bold;" id="total_price_mo">0</td>
                <td style="text-align: center; font-weight: bold;" id="total_price_mat">0</td>
            </tr>
        </tfoot>
    </table>



    <button id="saveButton">Guardar Items</button>

   
   <script>
        // Inicializar DataTable
        $(document).ready(function() {
            $('#items').DataTable();
        });
        // Guardar datos con AJAX
        $('#saveButton').click(function() {
            const items = [];
            const orderId = $('#order_id').val();
            const creator_user_Id = $('#creator_user_id').val();

            $('#items tbody tr').each(function() {
                const row = $(this);

                let item_number = parseInt(row.find('.item_number').text().trim());
                let rubro_id = parseInt(row.find('.rubro-id').text().trim());
                let quantity = parseFloat(row.find('.quantity input').val());  // Cambiado parseInt a parseFloat para decimales                
                let unit_price_mo = parseInt(row.find('.price-unit-mo').attr('data-value'));
                let unit_price_mat = parseInt(row.find('.price-unit-mat').attr('data-value'));
                let tot_price_mo = parseInt(row.find('.price-total-mo').attr('data-value'));
                let tot_price_mat = parseInt(row.find('.price-total-mat').attr('data-value'));

                // Verificar si item_number es válido antes de agregarlo al array
                if (item_number !== null && item_number !== undefined && !isNaN(item_number) &&
                    item_number !== 0 && quantity !== 0 && quantity !== "") {
                    items.push({
                        item_number: item_number,
                        rubro_id: rubro_id,
                        rubro: row.find('.rubro').text().trim(),
                        quantity: quantity,
                        unit_price_mo: unit_price_mo,
                        unit_price_mat: unit_price_mat,
                        tot_price_mo: tot_price_mo,
                        tot_price_mat: tot_price_mat,
                    });
                }                
            });

            console.log(items);

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
</body>

</html>




{{-- @extends('layouts.app')

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
            border-right: 1px solid #ddd;            
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
                            <h5 style="color: red;">
                                Contrato N°: {{ $contract->description }}                            
                            </h5>                        
                        </div>
                        <br>
                        <div class="d-inline">
                            <h5 style="color: red;">Detalle de Rubros de Componente: {{ $items[0]->component->code }} - {{ $items[0]->component->description }} </h5>
                            <br>
                            <h5 style="color: red;">Localidad: {{ $order->locality }} </h5>
                            index2
                            <input type="text" id="order_id" value="{{ $order->id }}">

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
                                        <div class="float-left">
                                            <h4 style="color: blue;">Rubros para procesar en la Orden de Ejecución N°: {{$order->number}}
                                        </div>
                                        <div class="float-right">
                                        </div>
                                    </div>                                
                                    <div class="card-block">
                                        <div class="dt-responsive table-responsive">
                                            <table id="items" class="table table-striped table-bordered nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>N° Item</th>
                                                        <th style="display: none;">ID Rubro</th>
                                                        <th>Rubro</th>
                                                        <th>Cant.</th>
                                                        <th>Unid.</th>
                                                        <th>Precio UNIT. MO</th>
                                                        <th>Precio UNIT. MAT</th>
                                                        <th>Precio TOT. MO</th>
                                                        <th>Precio TOT. MAT</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $tot_price_mo = 0;
                                                        $tot_price_mat = 0;
                                                    @endphp
                                    
                                                    @foreach ($items as $i => $item)
                                                        <tr>
                                                            @if ($item->rubro_id == '9999')
                                                                <td></td>
                                                                <td style="display: none;"></td>
                                                                <td style="font-size: 16px; font-weight: bold;">
                                                                    {{ $item->subitem->description }}
                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            @else
                                                                <td style="text-align: center;">{{ $item->item_number }}</td>
                                                                <td style="text-align: center; display: none;">{{ $item->rubro->id }}</td>
                                                                <td style="text-align: left;">
                                                                    {{ $item->rubro->code }}-{{ $item->rubro->description }}
                                                                </td>
                                                                <td style="text-align: center;">
                                                                    <input type="number" class="quantity" data-index="{{ $i }}" value="0" min="0" step="any" style="width: 80px; text-align: center;">
                                                                </td>
                                                                <td style="text-align: center;">
                                                                    {{ $item->rubro->orderPresentations->description }}
                                                                </td>
                                                                <td style="text-align: center;" id="unit_price_mo_{{ $i }}">
                                                                    {{ number_format($item->unit_price_mo, 0, ',', '.') }}
                                                                </td>
                                                                <td style="text-align: center;" id="unit_price_mat_{{ $i }}">
                                                                    {{ number_format($item->unit_price_mat, 0, ',', '.') }}
                                                                </td>
                                                                <td style="text-align: center;" id="total_mo_{{ $i }}">0</td>
                                                                <td style="text-align: center;" id="total_mat_{{ $i }}">0</td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5"></td>
                                                        <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow;">TOTALES:</td>
                                                        <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow; text-align: center;" id="tot_price_mo"></td>
                                                        <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow; text-align: center;" id="tot_price_mat"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <div class="text-center">                                                
                                                    @if (in_array($contract->contract_state_id, [1, 2]))
                                                    <button id="guardarDatos" class="btn btn-primary">Guardar datos</button>                                                       
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        function updateTotals() {
            let totalMo = 0;
            let totalMat = 0;

            $(".quantity").each(function () {
                let index = $(this).data("index");

                // Validar existencia de elementos
                let totalMoText = $("#total_mo_" + index).text()?.replace(/\./g, '').replace(',', '.') || "0";
                let totalMatText = $("#total_mat_" + index).text()?.replace(/\./g, '').replace(',', '.') || "0";

                totalMo += parseFloat(totalMoText) || 0;
                totalMat += parseFloat(totalMatText) || 0;
            });

            $("#tot_price_mo").text(totalMo.toLocaleString('es-ES'));
            $("#tot_price_mat").text(totalMat.toLocaleString('es-ES'));
        }

        $(".quantity").on("input", function () {
            let index = $(this).data("index");
            let quantity = parseFloat($(this).val()) || 0;

            let unitPriceText = $("#unit_price_mo_" + index).text()?.replace(/\./g, '').replace(',', '.') || "0";
            let unitPrice2Text = $("#unit_price_mat_" + index).text()?.replace(/\./g, '').replace(',', '.') || "0";

            let unitPrice = parseFloat(unitPriceText) || 0;
            let unitPrice2 = parseFloat(unitPrice2Text) || 0;

            let total = Math.round(quantity * unitPrice);
            let total2 = Math.round(quantity * unitPrice2);

            $("#total_mo_" + index).text(total.toLocaleString('es-ES'));
            $("#total_mat_" + index).text(total2.toLocaleString('es-ES'));

            updateTotals();
        });

        updateTotals();

        $("#guardarDatos").on("click", function () {
            let items = [];
            let orderId = $("#order_id").val();

            $("#items tbody tr").each(function () {
                let rubroId = $(this).find("td:nth-child(2)").text()?.trim(); // ID Rubro
                let quantity = $(this).find(".quantity").val() || 0;
                let unitPriceMO = $(this).find(`[id^="unit_price_mo_"]`).text()?.replace(/\./g, '').replace(',', '.') || "0";
                let unitPriceMat = $(this).find(`[id^="unit_price_mat_"]`).text()?.replace(/\./g, '').replace(',', '.') || "0";
                let totalMO = $(this).find(`[id^="total_mo_"]`).text()?.replace(/\./g, '').replace(',', '.') || "0";
                let totalMat = $(this).find(`[id^="total_mat_"]`).text()?.replace(/\./g, '').replace(',', '.') || "0";

                if (rubroId && rubroId !== "9999") {
                    items.push({
                        rubro_id: rubroId,
                        quantity: parseFloat(quantity),
                        unit_price_mo: parseFloat(unitPriceMO),
                        unit_price_mat: parseFloat(unitPriceMat),
                        tot_price_mo: parseFloat(totalMO),
                        tot_price_mat: parseFloat(totalMat),
                        order_id: orderId,
                        item_state: 1,
                    });
                }
            });

            if (items.length > 0) {
                fetch("{{ route('items_orders.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({ items: items }),
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error("Error en el servidor");
                        }
                        return response.json();
                    })
                    .then((data) => {
                        alert("Datos guardados correctamente");
                        console.log("Respuesta del servidor:", data);
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        alert("Error al guardar los datos. Intenta nuevamente.");
                    });
            } else {
                alert("No hay datos válidos para guardar.");
            }
        });
    });
</script> --}}
