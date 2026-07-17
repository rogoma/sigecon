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
    td, th {
        border: 1px solid #000;
        padding: 3px 5px;
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
</style>
<body>
    <header>
        <img src="img/logoVI_2.png" alt="Logo" style="height: 65px;">
    </header>

    <footer>
        Página <span class="page"></span> de <span class="topage"></span>
    </footer>

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
<h4 style="text-align: left;">PLANILLA N° {{ $certification->number }}-{{ $order->component->componentType->description }}</h4>

    <div class="datos-generales">
        <p>
            En la Localidad: <strong>{{ $order->locality->description }}</strong> -
            Distrito: <strong>{{ $order->locality->district->description }}</strong> -
            Departamento: <strong>{{ $order->locality->district->department->description }}</strong>,
            a los {{ \Carbon\Carbon::parse($certification->sign_date)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }},
            en presencia de
            @if ($order->creatorUser)
                {{ $order->creatorUser->name }} {{ $order->creatorUser->lastname }}
            @else
                (sin fiscal asignado)
            @endif
            representante de la fiscalización y
            @if ($certification->contratista_representative)
                {{ $certification->contratista_representative }}
            @else
                (sin representante asignado)
            @endif
            representante del Contratista, se detalla a continuación:
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">N°</th>
                <th rowspan="2">Descripción</th>
                <th rowspan="2">Cant. Contract.</th>
                <th rowspan="2">Cant. a Ejecutar</th>
                <th rowspan="2">Unid.</th>
                <th colspan="2">Anterior</th>
                <th colspan="2">Actual</th>
                <th colspan="2">Acumulado</th>
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
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $item->item_number }}</td>
                        <td>{{ $item->rubro->description }}</td>
                        <td style="text-align: center;">{{ number_format($item->quantity, 2, ',', '.') }}</td>
                        <td style="text-align: center;">{{ number_format($cantidadOrden, 2, ',', '.') }}</td>
                        <td style="text-align: center;">{{ $item->rubro->orderPresentations->description }}</td>
                        <td style="text-align: right;">{{ $anterior > 0 ? number_format($anterior, 2, ',', '.') : '-' }}</td>
                        <td style="text-align: right;">{{ $anterior > 0 ? number_format($anterior, 2, ',', '.') : '-' }}</td>
                        <td style="text-align: right;">{{ $actual > 0 ? number_format($actual, 2, ',', '.') : '-' }}</td>
                        <td style="text-align: right;">{{ $actual > 0 ? number_format($actual, 2, ',', '.') : '-' }}</td>
                        <td style="text-align: right; {{ $excedeSaldo ? 'background-color: #ffe066;' : '' }}">{{ $acumulado > 0 ? number_format($acumulado, 2, ',', '.') : '-' }}</td>
                        <td style="text-align: right; {{ $excedeSaldo ? 'background-color: #ffe066;' : '' }}">{{ $acumulado > 0 ? number_format($acumulado, 2, ',', '.') : '-' }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <br><br>
    <table style="border: none; width: 100%;">
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
