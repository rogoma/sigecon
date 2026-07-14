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
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                                Contrato N°: {{ $contract->description }} - Contratista: {{ $contract->provider->description }}
                            </h5>
                        </div>
                        <br>
                        <div class="d-inline">
                            {{-- <h5 style="color: red;">Detalle de Rubros de Componente: {{ $items[0]->component->code }} -
                                {{ $items[0]->component->description }}</h5> --}}
                            <br>
                            {{-- <h5 style="color: red;">Localidad: {{ $order->locality }} </h5>                             --}}
                            {{-- <label for="order_id">Order ID:</label> --}}
                            <input type="hidden" id="order_id" value="{{ $order->id }}">
                            <input type="hidden" id="creator_user_id" value="{{ Auth::user()->id }}">
                            index2_orig2.blade.php
                            {{-- <br> --}}
                            {{-- <h5 style="color: blue;">contract.itemscontracts.index2_orig2</h5> --}}
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
                                        {{-- <div class="float-left"> --}}                                        
                                        <h4 style="color: blue;"> Detalle de Rubros de Componente: {{ $items[0]->component->code }} - {{ $items[0]->component->description }}</h4>
                                        @if($order->locality)
                                            Localidad: {{ $order->locality->description }}
                                        @else
                                            Localidad: Sin localidad asignada
                                        @endif
                                        {{-- <h4 style="color: blue;"> Localidad: {{ $order->locality->description ?? 'Sin localidad' }} - Rubros para procesar en la Orden de Ejecución N°: {{ $order->component->code }} - {{ $order->number }}</h4> --}}
                                        {{-- </div> --}}
                                    </div>
                                    <div class="card-block">
                                        <div class="dt-responsive table-responsive">
                                            <table id="items" class="display" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
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

                                                    @foreach ($items->sortBy('rubro.id') as $i => $item)
                                                        <tr>
                                                            @if ($item->rubro_id == '9999')
                                                                <td class="item_id" style="text-align: center;">
                                                                {{ $item->id }}</td>

                                                                <td class="item_number" style="text-align: center;">
                                                                    {{ $item->item_number }}</td>

                                                                <td class="rubro-id" style="text-align: center;">
                                                                    {{ $item->rubro->id }}</td>
                                                                <td style="font-size: 16px; font-weight: bold;">
                                                                    {{ $item->subitem->description }}</td>                                                               
                                                                <td></td>
                                                                <td></td>                                                                
                                                                <td></td>   
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>                                                                
                                                            @else
                                                                <td class="item_id" style="text-align: center;">
                                                                {{ $item->id }}</td>

                                                                <td class="item_number" style="text-align: center;">
                                                                    {{ $item->item_number }}</td>

                                                                <td class="rubro-id" style="text-align: center;">
                                                                    {{ $item->rubro->id }}</td>

                                                                <td class="rubro" style="text-align: left;">
                                                                    {{ $item->rubro->code }}-{{ $item->rubro->description }}
                                                                </td>

                                                                <td style="text-align: center;">
                                                                    <input type="number" class="quantity"
                                                                        data-index="{{ $i }}" value="0"
                                                                        min="0" required step="any"
                                                                        style="width: 80px; text-align: center;"
                                                                        oninput="if (this.value === '' || this.value < 0) this.value = 0;">
                                                                </td>

                                                                <td class="unidad" style="text-align: center;">
                                                                    {{ $item->rubro->orderPresentations->description }}
                                                                </td>

                                                                <td class="price-unit-mo" style="text-align: center;"
                                                                    id="unit_price_mo_{{ $i }}"
                                                                    data-value="{{ $item->unit_price_mo }}">
                                                                    {{ number_format($item->unit_price_mo, 0, ',', '.') }}
                                                                </td>

                                                                <td class="price-unit-mat" style="text-align: center;"
                                                                    id="unit_price_mat_{{ $i }}"
                                                                    data-value="{{ $item->unit_price_mat }}">
                                                                    {{ number_format($item->unit_price_mat, 0, ',', '.') }}
                                                                </td>

                                                                <td class="price-total-mo" style="text-align: center;"
                                                                    id="total_mo_{{ $i }}">0</td>
                                                                <td class="price-total-mat" style="text-align: center;"
                                                                    id="total_mat_{{ $i }}">0</td>                                                                
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="7"></td>
                                                        <td
                                                            style="font-size: 16px; font-weight: bold; color: red; background-color: yellow;">
                                                            SUB-TOTALES:</td>
                                                        <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow; text-align: center;"
                                                            id="tot_price_mo"></td>
                                                        <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow; text-align: center;"
                                                            id="tot_price_mat"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="7"></td>
                                                        <td
                                                            style="font-size: 16px; font-weight: bold; color: red; background-color: yellow;">
                                                            TOTALES:</td>
                                                        <td
                                                            style="font-size: 16px; font-weight: bold; color: red; background-color: yellow; text-align: center;">
                                                        </td>
                                                        <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow; text-align: center;"
                                                            id="tot_gral"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <div class="text-center">
                                                @if (in_array($contract->contract_state_id, [1, 2]))
                                                    <button id="saveButton" class="btn btn-primary">Grabar Rubros</button>
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

{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#items').DataTable({
            "pageLength": 60, // Muestra 60 filas por página
            "lengthMenu": [
                [25, 60, 100, -1],
                [25, 60, 100, "Todos"]
            ], // Opciones para cambiar la cantidad de filas
            "responsive": true,
            "autoWidth": false
        });

        function updateTotals() {
            let totalMo = 0;
            let totalMat = 0;
            let totalGral = 0;

            $(".quantity").each(function() {
                let index = $(this).data("index");

                if ($("#total_mo_" + index).length && $("#total_mat_" + index).length) {
                    let totalMoText = $("#total_mo_" + index).text().replace(/\./g, '').replace(',',
                        '.') || "0";
                    let totalMatText = $("#total_mat_" + index).text().replace(/\./g, '').replace(',',
                        '.') || "0";

                    totalMo += parseFloat(totalMoText) || 0;
                    totalMat += parseFloat(totalMatText) || 0;
                }
            });

            totalGral = totalMo + totalMat;

            $("#tot_price_mo").text(totalMo.toLocaleString('es-ES'));
            $("#tot_price_mat").text(totalMat.toLocaleString('es-ES'));
            $("#tot_gral").text(totalGral.toLocaleString('es-ES'));
        }

        $(".quantity").on("input", function() {
            let index = $(this).data("index");
            let quantity = parseFloat($(this).val()) || 0;

            let unitPrice = 0,
                unitPrice2 = 0;

            if ($("#unit_price_mo_" + index).length) {
                let unitPriceText = $("#unit_price_mo_" + index).text().replace(/\./g, '').replace(',',
                    '.') || "0";
                unitPrice = parseFloat(unitPriceText) || 0;
            }

            if ($("#unit_price_mat_" + index).length) {
                let unitPrice2Text = $("#unit_price_mat_" + index).text().replace(/\./g, '').replace(
                    ',', '.') || "0";
                unitPrice2 = parseFloat(unitPrice2Text) || 0;
            }

            let total = Math.round(quantity * unitPrice);
            let total2 = Math.round(quantity * unitPrice2);

            if ($("#total_mo_" + index).length) $("#total_mo_" + index).text(total.toLocaleString(
                'es-ES'));
            if ($("#total_mat_" + index).length) $("#total_mat_" + index).text(total2.toLocaleString(
                'es-ES'));

            updateTotals();
        });

        updateTotals();

        // Guardar datos con AJAX
        $('#saveButton').click(function() {
            const items = [];
            const orderId = $('#order_id').val();
            const creator_user_Id = $('#creator_user_id').val();

            $('#items tbody tr').each(function() {
                const row = $(this);

                let item_number = parseInt(row.find('.item_number').text().trim()) || 0;
                let rubro_id = parseInt(row.find('.rubro-id').text().trim()) || 0;
                let quantity = parseFloat(row.find('.quantity').val()) ||
                0; // Asegurar que es numérico
                let unit_price_mo = parseFloat(row.find('.price-unit-mo').text().replace(/\./g,
                    '').replace(',', '.')) || 0;
                let unit_price_mat = parseFloat(row.find('.price-unit-mat').text().replace(
                    /\./g, '').replace(',', '.')) || 0;
                let tot_price_mo = parseFloat(row.find('.price-total-mo').text().replace(/\./g,
                    '').replace(',', '.')) || 0;
                let tot_price_mat = parseFloat(row.find('.price-total-mat').text().replace(
                    /\./g, '').replace(',', '.')) || 0;

                if (item_number !== 0 && quantity !== 0) {
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
                    window.location.href = response.redirect_url;
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                },
            });
        });
    });
</script>