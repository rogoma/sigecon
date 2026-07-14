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
            text-align: center;
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

        #items thead th.grupo-cantidades {
            background-color: #347ead;
            color: #fff;
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
                            <input type="hidden" id="order_id" value="{{ $order->id }}">
                            <input type="hidden" id="creator_user_id" value="{{ Auth::user()->id }}">
                            <h4 style="color: blue;"> MEDICIÓN DE RUBROS PARA CERTIFICACIÓN</h4>
                            <label id="fecha_actual" name="fecha_actual"
                                            style="font-size: 20px;color: #FF0000;float: left;"
                                            for="fecha_actual">{{ Carbon\Carbon::now()->format('d/m/Y') }}</label>
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
                                        <h4 style="color: blue;"> Orden N°: {{ $order->component->code }}-{{ $order->number }} - Localidad: {{ $order->locality->description }} - Componente: {{ $items0[0]->component->code }} - {{ $items0[0]->component->description }} </h4>
                                        <div class="form-group row">
                                            <div class="col-sm-2">
                                                <label for="month_date" class="col-form-label" style="color: blue; font-size: 18px;">Mes/Año (mm/yyyy)</label>
                                                    <div class="input-group">
                                                        <input type="text" id="month_date" name="month_date"
                                                            class="form-control @error('month_date') is-invalid @enderror"
                                                            value="{{ old('month_date') }}" autocomplete="off">
                                                        <span class="input-group-append">
                                                            <button type="button" class="btn btn-outline-secondary"
                                                                onclick="show('month_date');"><i
                                                                class="fa fa-calendar"></i></button>
                                                        </span>
                                                    </div>
                                                @error('month_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-sm-2">
                                                <label for="sign_date" class="col-form-label" style="color: blue; font-size: 18px;">Fecha de la Medición</label>
                                                    <div class="input-group">
                                                        <input type="text" id="sign_date" name="sign_date"
                                                            class="form-control @error('sign_date') is-invalid @enderror"
                                                            value="{{ old('sign_date') }}" autocomplete="off">
                                                        <span class="input-group-append">
                                                            <button type="button" class="btn btn-outline-secondary"
                                                            onclick="show('sign_date');"><i
                                                            class="fa fa-calendar"></i></button>
                                                        </span>
                                                    </div>
                                                    @error('sign_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-sm-2">
                                                        <label for="number" class="col-form-label" style="color: blue; font-size: 18px;">N° Planilla de Certificación</label>
                                                        <input type="text" id="number" name="number_display"
                                                            class="form-control" value="{{ $nextCertificationNumber }}" disabled>
                                                        <input type="hidden" id="number_hidden" name="number"
                                                            value="{{ $nextCertificationNumber }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-block">
                                        <div class="dt-responsive table-responsive">
                                            <table id="items" class="display" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">#Item</th>
                                                        <th rowspan="2">Descripción</th>
                                                        <th rowspan="2">Cant. Orden</th>
                                                        <th rowspan="2">Unid. Med.</th>
                                                        <th colspan="2" class="grupo-cantidades">Anterior</th>
                                                        <th class="grupo-cantidades">Actual</th>
                                                        <th colspan="2" class="grupo-cantidades">Acumulado</th>
                                                    </tr>
                                                    <tr>
                                                        <th>mdo</th>
                                                        <th>mat</th>
                                                        <th>mdo/mat</th>
                                                        <th>mdo</th>
                                                        <th>mat</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($items->sortBy('rubro.id') as $i => $item)
                                                        @php
                                                            $anterior = $anteriores[$item->rubro_id] ?? 0;
                                                        @endphp
                                                        <tr>
                                                            @if ($item->rubro_id == '9999')
                                                                <td class="item_number">{{ $item->item_number }}</td>
                                                                <td style="font-size: 16px; font-weight: bold; text-align: left;">
                                                                    {{ $item->subitem->description }}</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            @else
                                                                <td class="item_number">{{ $item->item_number }}</td>

                                                                <td class="rubro" style="text-align: left;">
                                                                    {{ $item->rubro->code }}-{{ $item->rubro->description }}
                                                                </td>

                                                                <td class="quantity">
                                                                    {{ $item->quantity }}
                                                                </td>

                                                                <td class="unidad">
                                                                    {{ $item->rubro->orderPresentations->description }}
                                                                </td>

                                                                <td class="anterior-mdo">{{ number_format($anterior, 2, ',', '.') }}</td>
                                                                <td class="anterior-mat">{{ number_format($anterior, 2, ',', '.') }}</td>

                                                                <td>
                                                                    <input type="number" class="medido rubro-id"
                                                                        data-rubro-id="{{ $item->rubro_id }}"
                                                                        data-anterior="{{ $anterior }}"
                                                                        value="0"
                                                                        min="0" required step="any"
                                                                        style="width: 90px; text-align: center;"
                                                                        oninput="if (this.value === '' || this.value < 0) this.value = 0; actualizaAcumulado(this);">
                                                                </td>

                                                                <td class="acumulado-mdo">{{ number_format($anterior, 2, ',', '.') }}</td>
                                                                <td class="acumulado-mat">{{ number_format($anterior, 2, ',', '.') }}</td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="text-center">
                                                @if (in_array($contract->contract_state_id, [1, 2]))
                                                    <button type="button" id="saveButton" class="btn btn-primary">Grabar Medición</button>
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

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    // Actualiza en vivo la columna Acumulado (Anterior + Actual) al tipear la medición
    function actualizaAcumulado(input) {
        const row = $(input).closest('tr');
        const anterior = parseFloat($(input).data('anterior')) || 0;
        const actual = parseFloat(input.value) || 0;
        const acumulado = anterior + actual;
        row.find('.acumulado-mdo').text(acumulado.toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        row.find('.acumulado-mat').text(acumulado.toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    }

    $(document).ready(function() {

        // Inicialización del datepicker
        $('#month_date').datepicker({
            language: 'es',
            format: 'mm/yyyy',
            autoclose: true,
            todayHighlight: true,
        });

        $('#sign_date').datepicker({
            language: 'es',
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
        });

        $('#items').DataTable({
            "pageLength": 60, // Muestra 60 filas por página
            "lengthMenu": [
                [25, 60, 100, -1],
                [25, 60, 100, "Todos"]
            ], // Opciones para cambiar la cantidad de filas
            "responsive": true,
            "autoWidth": false,
            "ordering": false,
        });

        // Guardar Acta de Medición con AJAX
        $('#saveButton').click(function() {
            const orderId = $('#order_id').val();
            const monthDate = $('#month_date').val();
            const signDate = $('#sign_date').val();

            if (!monthDate || !signDate) {
                swal("Atención", "Debe completar el Mes/Año y la Fecha de la Medición.", "warning");
                return;
            }

            const items = [];
            $('.medido').each(function() {
                const quantity = parseFloat($(this).val()) || 0;
                if (quantity > 0) {
                    items.push({
                        rubro_id: $(this).data('rubro-id'),
                        quantity: quantity,
                    });
                }
            });

            if (items.length === 0) {
                swal("Atención", "Debe ingresar al menos una cantidad medida mayor a 0.", "warning");
                return;
            }

            $.ajax({
                url: '/orders/' + orderId + '/item_certifications',
                type: 'POST',
                data: {
                    items: items,
                    month_date: monthDate,
                    sign_date: signDate,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    if (response.status === 'success') {
                        window.location.href = response.redirect_url;
                    } else {
                        swal("Error!", response.message, "error");
                    }
                },
                error: function(xhr) {
                    swal("Error!", "Ocurrió un error intentando grabar la medición, por favor verifique los datos e intente nuevamente.", "error");
                    console.error(xhr.responseText);
                },
            });
        });
    });
</script>
