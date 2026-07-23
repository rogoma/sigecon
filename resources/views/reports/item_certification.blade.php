<!DOCTYPE html>
<html>
<head>
    <title>Acta de Medición N° {{ $certification->number }}</title>
</head>
<style type="text/css">
    @page {
        margin: 110px 25px 50px 25px;
    }
    body {
        font-family: arial, sans-serif;
        font-size: 10px;
    }
    header {
        position: fixed;
        top: -90px;
        left: 0;
        right: 0;
        height: 90px;
        text-align: center;
        line-height: 1.5;
        border-bottom: 1px solid #ddd;
    }
    footer {
        position: fixed;
        bottom: -40px;
        left: 0;
        right: 0;
        height: 30px;
        text-align: center;
        font-size: 9px;
        color: #777;
        border-top: 1px solid #ddd;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 10px;
    }
    /* Repite el encabezado de la tabla en cada página y evita que una fila se corte entre dos páginas */
    thead {
        display: table-header-group;
    }
    tr {
        page-break-inside: avoid;
    }
    td, th {
        border: 1px solid #000;
        padding: 2px 4px;
    }
    th {
        text-align: center;
        background-color: #dddddd;
    }
    h2 {
        text-align: center;
        font-size: 14px;
        margin-bottom: 4px;
    }
    h4 {
        text-align: center;
        font-size: 11px;
        margin: 2px 0;
    }
    .datos-generales p {
        margin: 2px 0;
        font-size: 11px;
    }
    .rubro-section td {
        font-weight: bold;
        text-align: left;
    }
    .firmas {
        page-break-inside: avoid;
    }
</style>
<body>
    <header>
        <img src="{{ public_path('img/logoVI_2.png') }}" alt="Logo" style="height: 65px;">
    </header>

    <footer></footer>

    <script type="text/php">
        if (isset($pdf)) {
            $texto = "Página {PAGE_NUM} de {PAGE_COUNT}";
            $fuente = $fontMetrics->getFont("arial", "normal");
            $tamanio = 9;
            $ancho = $fontMetrics->getTextWidth($texto, $fuente, $tamanio);
            $x = ($pdf->get_width() - $ancho) / 2;
            $y = $pdf->get_height() - 25;
            $pdf->page_text($x, $y, $texto, $fuente, $tamanio, [0.47, 0.47, 0.47]);
        }
    </script>

    <h2>MINISTERIO DE SALUD PÚBLICA Y BIENESTAR SOCIAL</h2>
    <h2>SERVICIO NACIONAL DE SANEMIENTO AMBIENTAL (SENASA)</h2>
    {{ $contract->description }}    
    <h4>LOTE N° {{ $order->component->code }}</h4>
    <h4>CONTRATO N° {{ $contract->number_year }} - EMPRESA CONSTRUCTORA: {{ $contract->provider->description }}</h4>
    {{-- <h4>EMPRESA CONSTRUCTORA: {{ $contract->provider->description }}</h4> --}}
    <h4>PLANILLA DE CERTIFICACIÓN N° {{ $certification->number }}</h4>
    <h4>PERIODO: {{ $certification->period }}</h4>
    <BR></BR>
    <h4 style="text-align: left;">ACTA DE MEDICIÓN</h4>
<h4 style="text-align: left;">PLANILLA N° {{ $order->component->componentType->code }}-{{ $order->component->componentType->description }}</h4>
    @if ($esMultiOrden)
        {{-- <h4 style="text-align: left;">
            ÓRDENES DE EJECUCIÓN: {{ $ordenesReferenciadas->map(fn ($o) => $o->component_code . '-' . $o->number)->implode(', ') }}
        </h4> --}}
    @endif

    <div class="datos-generales">
        <p>
            En la Localidad: <strong>{{ $order->locality->description }}</strong> -
            Distrito: <strong>{{ $order->locality->district->description }}</strong> -
            Departamento: <strong>{{ $order->locality->district->department->description }}</strong>,
            {{-- a los {{ \Carbon\Carbon::parse($certification->sign_date)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}, --}}
            <strong>a los {{ \Carbon\Carbon::parse($certification->sign_date)->locale('es')->isoFormat('D') }} días del mes de {{ \Carbon\Carbon::parse($certification->sign_date)->locale('es')->isoFormat('MMMM') }} de {{ \Carbon\Carbon::parse($certification->sign_date)->locale('es')->isoFormat('YYYY') }}</strong>,
            en presencia del
            @if ($certification->fiscalizacion_representative)
                <strong>{{ $certification->fiscalizacion_representative }}</strong>
            @else
                (sin representante asignado)
            @endif
            representante de la fiscalización y
            @if ($certification->contratista_representative)
                <strong>{{ $certification->contratista_representative }}</strong>
            @else
                (sin representante asignado)
            @endif
            representante del Contratista, se detalla a continuación:
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="3">N°</th>
                <th rowspan="3">Descripción</th>
                <th rowspan="3">Cant. Contractual</th>
                <th rowspan="3">Cant. en Orden Ejec.</th>
                <th rowspan="3">Unid.</th>
                <th colspan="6">CANTIDADES</th>
            </tr>
            <tr>
                <th colspan="2">ANTERIOR</th>
                <th colspan="2">ACTUAL</th>
                <th colspan="2">ACUMULADO</th>
            </tr>
            <tr>
                <th>mdo</th>
                <th>mat</th>
                <th>mdo</th>
                <th>mat</th>
                <th>mdo</th>
                <th>mat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rubros as $item)
                @if ($item->rubro_id == 9999)
                    <tr class="rubro-section">
                        <td>{{ $item->item_number }}</td>
                        <td colspan="9">{{ $item->subitem->description }}</td>
                    </tr>
                @else
                    @php
                        $medidoEnEstaActa = ($actuales[$item->rubro_id] ?? 0) > 0;
                        // Anterior, Actual y Acumulado sólo se muestran para rubros medidos en esta carga; el resto queda en blanco
                        $anterior = $medidoEnEstaActa ? ($anteriores[$item->rubro_id] ?? 0) : 0;
                        $actual = $medidoEnEstaActa ? ($actuales[$item->rubro_id] ?? 0) : 0;
                        $acumulado = $anterior + $actual;
                        $cantidadOrdenBase = $cantidadesOrden[$item->rubro_id] ?? 0;
                        // Cant. a Ejecutar sólo se muestra si el rubro fue medido en esta acta y tiene saldo asignado en la orden; caso contrario, 0
                        $cantidadOrden = ($medidoEnEstaActa && $cantidadOrdenBase > 0) ? $cantidadOrdenBase : 0;
                        $excedeSaldo = $acumulado > $cantidadOrden;
                        // De acuerdo al ítem: puede tener mano de obra (mdo), materiales (mat), o ambos
                        $tieneMdo = (float) ($item->unit_price_mo ?? 0) > 0;
                        $tieneMat = (float) ($item->unit_price_mat ?? 0) > 0;
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $item->item_number }}</td>
                        <td>{{ $item->rubro->description }}</td>
                        <td style="text-align: center;">{{ number_format($item->quantity, 2, ',', '.') }}</td>
                        <td style="text-align: center;">{{ number_format($cantidadOrden, 2, ',', '.') }}</td>
                        <td style="text-align: center;">{{ $item->rubro->orderPresentations->description }}</td>
                        <td style="text-align: right;">{{ ($tieneMdo && $anterior > 0) ? number_format($anterior, 2, ',', '.') : '-' }}</td>
                        <td style="text-align: right;">{{ ($tieneMat && $anterior > 0) ? number_format($anterior, 2, ',', '.') : '-' }}</td>
                        <td style="text-align: right; font-weight: bold;">{{ ($tieneMdo && $actual > 0) ? number_format($actual, 2, ',', '.') : '-' }}</td>
                        <td style="text-align: right; font-weight: bold;">{{ ($tieneMat && $actual > 0) ? number_format($actual, 2, ',', '.') : '-' }}</td>
                        <td style="text-align: right; {{ $excedeSaldo ? 'background-color: #ffe066;' : '' }}">{{ ($tieneMdo && $acumulado > 0) ? number_format($acumulado, 2, ',', '.') : '-' }}</td>
                        <td style="text-align: right; {{ $excedeSaldo ? 'background-color: #ffe066;' : '' }}">{{ ($tieneMat && $acumulado > 0) ? number_format($acumulado, 2, ',', '.') : '-' }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <br><br>
    <table class="firmas" style="border: none; width: 100%;">
        <tr style="border: none;">
            <td style="border: none; text-align: center; width: 50%;">
                _____________________________<br>
                Fiscalización
            </td>
            <td style="border: none; text-align: center; width: 50%;">
                _____________________________<br>
                Contratista
            </td>
        </tr>
    </table>
</body>
</html>
