@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/datatables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/buttons.datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/responsive.bootstrap4.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .acta-page {
            --acta-primary: #2f6f97;
            --acta-primary-dark: #204d69;
            --acta-accent: #2196f3;
            --acta-success: #2eb85c;
            --acta-warning: #f9b115;
            --acta-danger: #e55353;
        }

        /* ---------- Hero / encabezado ---------- */
        .acta-hero {
            background: linear-gradient(135deg, var(--acta-primary) 0%, var(--acta-primary-dark) 100%);
            border-radius: 16px;
            border: 0;
            color: #fff;
            box-shadow: 0 8px 24px rgba(32, 77, 105, .25);
            overflow: hidden;
        }

        .acta-hero .breadcrumb {
            background: rgba(255, 255, 255, .12);
            border-radius: 30px;
        }

        .acta-hero .breadcrumb-item a {
            color: #fff;
            opacity: .9;
        }

        .acta-hero-icon {
            width: 56px;
            height: 56px;
            min-width: 56px;
            border-radius: 16px;
            background: rgba(255, 255, 255, .18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .acta-hero-eyebrow {
            text-transform: uppercase;
            letter-spacing: .08em;
            font-size: 12px;
            opacity: .85;
            font-weight: 600;
        }

        .acta-date-badge {
            background: rgba(255, 255, 255, .15);
            border-radius: 30px;
            padding: 6px 16px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .acta-state-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, .15);
            border-radius: 30px;
            padding: 6px 16px;
            font-weight: 700;
            font-size: 13px;
        }

        .acta-dependency {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13.5px;
            opacity: .95;
        }

        /* ---------- Chips de la orden ---------- */
        .acta-chips {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
            padding: 14px 18px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .acta-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #eef5f9;
            color: var(--acta-primary-dark);
            border-radius: 30px;
            padding: 7px 14px;
            font-size: 13px;
            font-weight: 600;
        }

        .acta-chip i {
            color: var(--acta-primary);
        }

        /* ---------- Cards generales ---------- */
        .acta-card {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 2px 14px rgba(0, 0, 0, .06);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .acta-card-header {
            background: #f7fafc;
            border-bottom: 1px solid #edf2f7;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .acta-card-header h5 {
            margin: 0;
            font-weight: 700;
            color: var(--acta-primary-dark);
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .acta-card-body {
            padding: 20px;
        }

        /* ---------- Formulario nueva acta ---------- */
        .acta-field label {
            font-weight: 600;
            color: #495057;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .acta-field .input-group-text {
            background: var(--acta-primary);
            color: #fff;
            border: 0;
        }

        .acta-field .form-control {
            border: 1px solid #dee2e6;
        }

        .acta-field .form-control:focus {
            border-color: var(--acta-accent);
            box-shadow: 0 0 0 .2rem rgba(33, 150, 243, .15);
        }

        .acta-number-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #eef5f9, #e3edf3);
            border-radius: 12px;
            padding: 10px 16px;
            height: calc(1.5em + .75rem + 2px + 16px);
        }

        .acta-number-badge .num {
            font-size: 24px;
            font-weight: 800;
            color: var(--acta-primary-dark);
            line-height: 1;
        }

        /* ---------- Tabla actas generadas ---------- */
        .acta-generated-table thead th {
            background: #eef5f9;
            color: var(--acta-primary-dark);
            border-top: 0;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .acta-generated-table tbody tr:hover {
            background: #f7fbfd;
        }

        .acta-empty-state {
            text-align: center;
            padding: 30px 10px;
            color: #97a3ad;
        }

        .acta-empty-state i {
            font-size: 34px;
            margin-bottom: 8px;
            display: block;
            color: #cbd7de;
        }

        .acta-pdf-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff0ee;
            color: #d9534f;
            border-radius: 30px;
            padding: 5px 14px;
            font-weight: 600;
            font-size: 12.5px;
            text-decoration: none;
            transition: transform .15s ease;
        }

        .acta-pdf-link:hover {
            transform: translateY(-1px);
            color: #d9534f;
            text-decoration: none;
        }

        /* ---------- Grilla de rubros ---------- */
        #items td,
        #items th {
            padding: 8px 10px;
            vertical-align: middle;
            text-align: center;
        }

        #items {
            border-collapse: collapse;
        }

        #items thead th {
            background: #f7fafc;
            color: var(--acta-primary-dark);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .02em;
        }

        #items thead th.grupo-cantidades {
            background: linear-gradient(135deg, var(--acta-primary) 0%, var(--acta-primary-dark) 100%);
            color: #fff;
        }

        #items tbody tr:hover {
            background: #f5fbff;
        }

        #items tr.rubro-section td {
            background: #eef5f9 !important;
            font-weight: 700;
            text-align: left !important;
            color: var(--acta-primary-dark);
        }

        .rubro-desc {
            text-align: left !important;
            max-width: 280px;
        }

        .saldo-wrap {
            min-width: 130px;
        }

        .saldo-bar {
            height: 8px;
            border-radius: 30px;
            overflow: hidden;
            background: #e9ecef;
        }

        .saldo-bar .bar-anterior {
            background: #adb5bd;
        }

        .saldo-bar .bar-actual {
            background: var(--acta-success);
            transition: width .15s ease;
        }

        .saldo-bar .bar-actual.warn {
            background: var(--acta-warning);
        }

        .saldo-bar .bar-actual.danger {
            background: var(--acta-danger);
        }

        .saldo-text {
            font-size: 11px;
            color: #868e96;
            display: block;
            margin-top: 3px;
        }

        .medido-input {
            width: 100px;
            text-align: center;
            border-radius: 8px;
            font-weight: 700;
            border: 1px solid #ced4da;
        }

        .medido-input:focus {
            border-color: var(--acta-accent);
            box-shadow: 0 0 0 .2rem rgba(33, 150, 243, .15);
        }

        .medido-input.is-maxed {
            border-color: var(--acta-warning);
        }

        /* ---------- Barra de acción sticky ---------- */
        .acta-actionbar {
            position: sticky;
            bottom: 0;
            background: #fff;
            border-top: 1px solid #edf2f7;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            z-index: 5;
        }

        #itemsCounter {
            background: #eef5f9;
            color: var(--acta-primary-dark);
            font-weight: 600;
            padding: 8px 14px;
        }

        #saveButton {
            border-radius: 30px;
            padding: 10px 26px;
            font-weight: 700;
            background: var(--acta-primary);
            border-color: var(--acta-primary);
            transition: transform .15s ease, box-shadow .15s ease;
        }

        #saveButton:not(:disabled):hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(47, 111, 151, .35);
        }

        #saveButton:disabled {
            background: #cbd5db;
            border-color: #cbd5db;
        }

        /* ---------- Responsive ---------- */
        @media (max-width: 767.98px) {
            .acta-hero {
                border-radius: 12px;
                text-align: center;
            }

            .acta-hero .d-flex {
                justify-content: center;
            }

            .acta-card-body {
                padding: 14px;
            }

            .acta-number-badge {
                justify-content: center;
                margin-top: 10px;
            }

            .acta-actionbar {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }

            #saveButton {
                width: 100%;
            }

            .rubro-desc {
                max-width: 140px;
            }

            .saldo-wrap {
                min-width: 90px;
            }

            .saldo-text,
            .saldo-excede {
                font-size: 10px;
            }

            .medido-input {
                width: 70px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="pcoded-content acta-page">

        {{-- HERO (mismo encabezado que la vista de Órdenes de Ejecución que precede a esta pantalla) --}}
        <div class="page-header card acta-hero mb-3">
            <div class="row align-items-center p-2">
                <div class="col-12 col-lg-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="acta-hero-icon"><i class="fa-solid fa-file-signature"></i></div>
                        <div class="ml-3">
                            <div class="acta-hero-eyebrow">Módulo de Certificaciones</div>
                            <h4 class="mb-1 text-white">{{ $contract->description }} - {{ $contract->modality->description }} - Contrato N° {{ $contract->number_year }}</h4>
                            <div class="d-flex flex-wrap gap-2" style="gap:8px;">
                                <span class="acta-state-badge"><i class="fa-solid fa-circle-check"></i> {{ $contract->contractState->id }} - {{ $contract->contractState->description }}</span>
                                <span class="acta-dependency"><i class="fa-solid fa-building"></i> {{ $contract->dependency->description }}</span>
                            </div>
                            <div class="acta-dependency mt-1"><i class="fa-solid fa-user-tie"></i> Contratista: {{ $contract->provider->description }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 text-lg-right mt-3 mt-lg-0">
                    <ul class="breadcrumb breadcrumb-title justify-content-lg-end mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa-solid fa-house"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('contracts.volver', $contract->id) }}">Órdenes</a></li>
                    </ul>
                    <span class="acta-date-badge"><i class="fa-solid fa-calendar-day"></i> {{ Carbon\Carbon::now()->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <input type="hidden" id="order_id" value="{{ $order->id }}">
        <input type="hidden" id="creator_user_id" value="{{ Auth::user()->id }}">

        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">

                        {{-- CHIPS DE LA ORDEN --}}
                        <div class="acta-chips mb-3">
                            <span class="acta-chip"><i class="fa-solid fa-file-contract"></i> Orden N° {{ $order->component->code }}-{{ $order->number }}</span>
                            <span class="acta-chip"><i class="fa-solid fa-location-dot"></i> {{ $order->locality->description }}</span>
                            <span class="acta-chip"><i class="fa-solid fa-diagram-project"></i> {{ $items0[0]->component->code }} - {{ $items0[0]->component->description }}</span>
                        </div>

                        {{-- FORMULARIO NUEVA ACTA --}}
                        <div class="card acta-card">
                            <div class="acta-card-header">
                                <h5><i class="fa-solid fa-square-plus"></i> Nueva Acta de Medición</h5>
                            </div>
                            <div class="acta-card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-lg-4 acta-field mb-3 mb-lg-0">
                                        <label for="sign_date">Fecha de la Medición</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa-solid fa-calendar-check"></i></span>
                                            </div>
                                            <input type="text" id="sign_date" name="sign_date" placeholder="dd/mm/yyyy"
                                                class="form-control @error('sign_date') is-invalid @enderror"
                                                value="{{ old('sign_date') }}" autocomplete="off">
                                        </div>
                                        @error('sign_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-sm-6 col-lg-4 acta-field mb-3 mb-lg-0">
                                        <label for="month_date">Mes/Año</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                                            </div>
                                            <input type="text" id="month_date" name="month_date" placeholder="mm/yyyy"
                                                class="form-control @error('month_date') is-invalid @enderror"
                                                value="{{ old('month_date') }}" autocomplete="off">
                                        </div>
                                        @error('month_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-lg-4 acta-field">
                                        <label>N° Planilla de Certificación</label>
                                        <div class="acta-number-badge">
                                            <i class="fa-solid fa-hashtag" style="color: var(--acta-primary);"></i>
                                            <span class="num">{{ $nextCertificationNumber }}</span>
                                        </div>
                                        <input type="hidden" id="number_hidden" name="number" value="{{ $nextCertificationNumber }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ACTAS GENERADAS --}}
                        <div class="card acta-card">
                            <div class="acta-card-header">
                                <h5><i class="fa-solid fa-clock-rotate-left"></i> Actas de Medición Generadas</h5>
                                <span class="badge badge-pill" style="background:#eef5f9;color:var(--acta-primary-dark);">{{ $certifications->count() }} registradas</span>
                            </div>
                            <div class="acta-card-body p-0">
                                @if ($certifications->isNotEmpty())
                                    <div class="table-responsive">
                                        <table class="table acta-generated-table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>N° Planilla</th>
                                                    <th>Período</th>
                                                    <th>Fecha Medición</th>
                                                    <th class="text-right">PDF</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($certifications as $certification)
                                                    <tr>
                                                        <td><strong>{{ $certification->number }}</strong></td>
                                                        <td>{{ $certification->period }}</td>
                                                        <td>{{ $certification->signDateFormat() }}</td>
                                                        <td class="text-right">
                                                            <a href="{{ route('item_certifications.pdf', $certification->id) }}"
                                                                target="_blank" rel="noopener" class="acta-pdf-link">
                                                                <i class="fa-solid fa-file-pdf"></i> Ver PDF
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="acta-empty-state">
                                        <i class="fa-regular fa-folder-open"></i>
                                        Aún no se generaron actas de medición para esta orden.
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- DETALLE DE RUBROS --}}
                        <div class="card acta-card">
                            <div class="acta-card-header">
                                <h5><i class="fa-solid fa-list-check"></i> Detalle de Rubros - Medición Actual</h5>
                                <span class="text-muted" style="font-size:12.5px;">Solo se listan rubros con saldo disponible</span>
                            </div>
                            <div class="acta-card-body">
                                <div class="dt-responsive table-responsive">
                                    <table id="items" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#Item</th>
                                                <th>Descripción</th>
                                                <th>Unid. Med.</th>
                                                <th>Cant. Orden</th>
                                                <th>Saldo</th>
                                                <th class="grupo-cantidades">Ant. mdo</th>
                                                <th class="grupo-cantidades">Ant. mat</th>
                                                <th class="grupo-cantidades">Actual</th>
                                                <th class="grupo-cantidades">Acum. mdo</th>
                                                <th class="grupo-cantidades">Acum. mat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items->sortBy('rubro.id') as $i => $item)
                                                @php
                                                    $anterior = $anteriores[$item->rubro_id] ?? 0;
                                                @endphp
                                                @continue ($item->rubro_id != '9999' && $anterior >= $item->quantity)
                                                @if ($item->rubro_id == '9999')
                                                    <tr class="rubro-section">
                                                        <td class="item_number">{{ $item->item_number }}</td>
                                                        <td colspan="9">{{ $item->subitem->description }}</td>
                                                    </tr>
                                                @else
                                                    @php
                                                        $saldo = max(0, $item->quantity - $anterior);
                                                        $pctAnterior = $item->quantity > 0 ? min(100, ($anterior / $item->quantity) * 100) : 0;
                                                    @endphp
                                                    <tr>
                                                        <td class="item_number">{{ $item->item_number }}</td>

                                                        <td class="rubro rubro-desc">
                                                            {{ $item->rubro->code }}-{{ $item->rubro->description }}
                                                        </td>

                                                        <td class="unidad">
                                                            {{ $item->rubro->orderPresentations->description }}
                                                        </td>

                                                        <td class="quantity text-danger font-weight-bold">
                                                            {{ number_format($item->quantity, 2, ',', '.') }}
                                                        </td>

                                                        <td class="saldo-wrap">
                                                            <div class="saldo-bar">
                                                                <div class="bar-anterior" style="height:100%;float:left;width: {{ $pctAnterior }}%;"></div>
                                                                <div class="bar-actual" style="height:100%;float:left;width:0%;"></div>
                                                            </div>
                                                            <small class="saldo-text">Saldo: <span class="saldo-value">{{ number_format($saldo, 2, ',', '.') }}</span> de {{ number_format($item->quantity, 2, ',', '.') }}</small>
                                                            <small class="saldo-excede text-danger font-weight-bold" style="display:none;"><i class="fa-solid fa-triangle-exclamation"></i> Excede saldo en <span class="excede-value">0</span></small>
                                                        </td>

                                                        <td class="anterior-mdo">{{ number_format($anterior, 2, ',', '.') }}</td>
                                                        <td class="anterior-mat">{{ number_format($anterior, 2, ',', '.') }}</td>

                                                        <td>
                                                            <input type="number" class="medido rubro-id medido-input"
                                                                data-rubro-id="{{ $item->rubro_id }}"
                                                                data-anterior="{{ $anterior }}"
                                                                data-quantity="{{ $item->quantity }}"
                                                                value="0"
                                                                min="0" required step="any"
                                                                oninput="actualizaAcumulado(this);">
                                                        </td>

                                                        <td class="acumulado-mdo">{{ number_format($anterior, 2, ',', '.') }}</td>
                                                        <td class="acumulado-mat">{{ number_format($anterior, 2, ',', '.') }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if (in_array($contract->contract_state_id, [1, 2]))
                                <div class="acta-actionbar">
                                    <span class="badge badge-pill" id="itemsCounter"><i class="fa-solid fa-circle-check mr-1"></i> 0 rubros con medición</span>
                                    <button type="button" id="saveButton" class="btn btn-primary" disabled>
                                        <i class="fa-solid fa-floppy-disk mr-1"></i> Grabar Medición
                                    </button>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Actualiza en vivo la barra de saldo, el Acumulado y el contador/estado del botón al tipear la medición.
        // No limita el valor máximo: se permite cargar más del saldo disponible, mostrando una advertencia visual.
        function actualizaAcumulado(input) {
            const $input = $(input);
            const row = $input.closest('tr');
            const anterior = parseFloat($input.data('anterior')) || 0;
            const quantity = parseFloat($input.data('quantity')) || 0;

            let actual = parseFloat(input.value);
            if (isNaN(actual) || actual < 0) actual = 0;
            input.value = actual;

            const acumulado = anterior + actual;
            const excede = quantity > 0 && acumulado > quantity;
            $input.toggleClass('is-maxed', excede);

            row.find('.acumulado-mdo, .acumulado-mat').text(
                acumulado.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
            );

            if (quantity > 0) {
                const pctActual = Math.min(100, (actual / quantity) * 100);
                const pctTotal = (acumulado / quantity) * 100;
                const bar = row.find('.bar-actual');
                bar.css('width', pctActual + '%');
                bar.removeClass('warn danger');
                // Si lo medido coincide exactamente con la Cant. Orden, se deja en verde (color por defecto)
                if (Math.abs(actual - quantity) > 0.001) {
                    if (pctTotal > 100) bar.addClass('danger');
                    else if (pctTotal >= 60) bar.addClass('warn');
                }

                const saldoRestante = quantity - acumulado;
                row.find('.saldo-text').toggle(!excede);
                row.find('.saldo-excede').toggle(excede);
                if (excede) {
                    row.find('.excede-value').text(
                        Math.abs(saldoRestante).toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                    );
                } else {
                    row.find('.saldo-value').text(
                        saldoRestante.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                    );
                }
            }

            actualizaContador();
        }

        // Actualiza el contador de rubros con medición > 0 y habilita/deshabilita el botón de grabar
        function actualizaContador() {
            const count = $('.medido').filter(function() {
                return (parseFloat($(this).val()) || 0) > 0;
            }).length;

            $('#itemsCounter').html('<i class="fa-solid fa-circle-check mr-1"></i> ' + count + ' rubro' + (count === 1 ? '' : 's') + ' con medición');
            $('#saveButton').prop('disabled', count === 0);
        }

        $(document).ready(function() {

            // Inicialización del datepicker
            $('#month_date').datepicker({
                language: 'es',
                format: 'mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                minViewMode: 'months',
            });

            $('#sign_date').datepicker({
                language: 'es',
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
            });

            $('#items').DataTable({
                "pageLength": 60,
                "lengthMenu": [
                    [25, 60, 100, -1],
                    [25, 60, 100, "Todos"]
                ],
                "responsive": {
                    details: {
                        display: $.fn.dataTable.Responsive.display.childRowImmediate,
                        type: ''
                    }
                },
                "autoWidth": false,
                "ordering": false,
                "language": {
                    "search": "Buscar rubro:",
                    "lengthMenu": "Mostrar _MENU_ rubros",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ rubros",
                    "infoEmpty": "Sin rubros con saldo disponible",
                    "zeroRecords": "No se encontraron rubros",
                    "paginate": { "previous": "Anterior", "next": "Siguiente" }
                },
                "columnDefs": [
                    { "responsivePriority": 1, "targets": 0 },  // #Item
                    { "responsivePriority": 2, "targets": 1 },  // Descripción
                    { "responsivePriority": 3, "targets": 7 },  // Actual (input)
                    { "responsivePriority": 4, "targets": 4 },  // Saldo
                ],
            });

            actualizaContador();

            // Guardar Acta de Medición con AJAX
            $('#saveButton').click(function() {
                const $btn = $(this);
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

                swal({
                        title: "Atención",
                        text: "¿Está seguro que desea grabar esta Acta de Medición? Una vez grabada no podrá modificarla.",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sí, grabar",
                        cancelButtonText: "Cancelar",
                    },
                    function(isConfirm) {
                        if (!isConfirm) return;

                        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i> Guardando...');

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
                                    window.open(response.redirect_url, '_blank');
                                    window.location.href = "{{ route('contracts.volver', $contract->id) }}";
                                } else {
                                    swal("Error!", response.message, "error");
                                    $btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk mr-1"></i> Grabar Medición');
                                }
                            },
                            error: function(xhr) {
                                swal("Error!", "Ocurrió un error intentando grabar la medición, por favor verifique los datos e intente nuevamente.", "error");
                                console.error(xhr.responseText);
                                $btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk mr-1"></i> Grabar Medición');
                            },
                        });
                    }
                );
            });
        });
    </script>
@endpush
