<!DOCTYPE html>
<html>

<head>
    <title>Panel General de Llamados</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
</head>
<style type="text/css">
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
        font-size: 8px;
    }

    td,
    th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 4px;
        font-size: 10px;
    }

    thead tr {
        background-color: #dddddd;
        padding: 2px;
        font-size: 8px;
    }

    h2 {
        text-align: center;
        font-size: 12px;
        margin-bottom: 5px;
    }

    h3 {
        text-align: left;
        font-size: 12px;
        margin-bottom: 15px;
    }

    body {
        font-size: 12px;
        margin-top: 120px; /* Ajusta este valor según la altura de tu encabezado */
        margin-bottom: 50px; /* Ajusta este valor según la altura de tu pie de página */
    }

    .section {
        margin-top: 30px;
        padding: 50px;
        background: #fff;
        font-size: 8px;
    }

    .pdf-btn {
        margin-top: 30px;
    }

    .columna1 {
        width: 1%;
        text-align: center;
    }

    .columna2 {
        width: 25%;
        text-align: left;
    }

    .columna3 {
        width: 7%;
        text-align: left;
    }

    .columna4 {
        width: 16%;
        text-align: left;
    }

    .columna5 {
        width: 2%;
        text-align: center;
    }

    .columna6 {
        width: 4%;
        text-align: center;
    }

    .columna7 {
        width: 4%;
        text-align: center;
    }

    .columna8 {
        width: 4%;
        text-align: center;
    }

    .columna9 {
        width: 5%;
        text-align: left;
    }

    .columna10 {
        width: 9%;
        text-align: center;
    }

    .columna11 {
        width: 2%;
        text-align: center;
    }

    p.centrado {
        text-align: center;
    }

    header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 100px; /* Ajusta esta altura según el contenido del header */
        text-align: center;
        line-height: 1.5;
        font-size: 12px;
        /* border-bottom: 1px solid #ddd; */
        background-color: white; /* Asegura que el fondo del encabezado sea blanco */
    }

    footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 30px;
        text-align: center;
        font-size: 10px;
        color: #777;
        border-top: 1px solid #ddd;
        background-color: white; /* Asegura que el fondo del pie de página sea blanco */
    }

    .page-break {
        page-break-before: always;
    }
</style>

<body>
    @php
        use Carbon\Carbon;
        $fechaActual = Carbon::now()->format('d/m/Y H:i:s'); // Formato: día/mes/año hora:minuto:segundo
    @endphp

    <header>
        <img src="img/logoVI_2.png" alt="Logo" style="height: 65px;"><br> <!-- Ajusta el tamaño de la imagen -->
        <p style="text-align: right; font-weight: bold; margin: 5px 0;"> {{ $fechaActual }}</p>
        <h2 style="color:#ff0000; margin: 5px 0;">LISTADO TOTAL DE ORDENES</h2> 
    </header>

    <footer>
        Página <span class="page"></span> de <span class="topage"></span>
    </footer>   
    
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>N°CONTRATO-CONTRATISTA</th>
                <th>N°ORDEN-FECHA</th>
                <th>MONTO ORDEN</th>
                <th>FECHA INICIO PLAZO</th>
                <th>DIAS PLAZO</th>
                <th>FECHA FIN PLAZO</th>
                <th>FISCAL</th>
                <th>DPTO-DISTRITO-LOCALIDAD</th>
                <th>SUB_COMPONENTE</th>
                <th>ESTADO</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < count($orders); $i++)
                @php
                    $fechaFinPlazo = Carbon::parse($orders[$i]->fecha_fin_plazo);
                    $hoy = Carbon::now();
                    $fechaAcuse = $orders[$i]->fecha_acuse_contr; // Capturamos la fecha de acuse

                    // Solo calcular si fecha_acuse_contr NO es null
                    if ($fechaAcuse) {
                        $diferenciaDias = $hoy->diffInDays($fechaFinPlazo, false); // Puede ser negativa si ya venció

                        $vencido = $diferenciaDias < 0; // Si es menor que 0, ya venció
                        $vencidomsj = "PLAZO VENCIDO";

                        $porVencer = !$vencido && $diferenciaDias <= 3; // Si está dentro de los próximos 3 días
                        $porVencermsj = "PLAZO A VENCER";

                    } else {
                        $vencido = false;
                        $porVencer = false;
                    }
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td> {{ $orders[$i]->nro_contrato }} - {{ $orders[$i]->contratista }}</td>
                    <td> {{ $orders[$i]->nro_orden }}-{{ date('d/m/Y', strtotime($orders[$i]->fecha_orden)) }}</td>
                    <td> {{ number_format($orders[$i]->monto_orden, '0', ',', '.') }} </td>
                    <td> {{ $fechaAcuse ? date('d/m/Y', strtotime($fechaAcuse)) : '' }}</td> {{-- Mostrar fecha o vacío --}}
                    <td> {{ $orders[$i]->plazo }}</td>
                    <td>
                        <span
                            @if ($fechaAcuse && $vencido && $orders[$i]->order_state_id != 4) style="color: red; font-weight: bold;" {{-- Rojo si está vencido y no es estado 4 --}}                                
                            @elseif($fechaAcuse && $porVencer) 
                                style="color: orange; font-weight: bold;" {{-- Naranja si faltan 3 días o menos --}} 
                            @endif>
                                
                                @if($fechaAcuse && $vencido && $orders[$i]->order_state_id != 4)
                                    {{ date('d/m/Y', strtotime($orders[$i]->fecha_fin_plazo)) }} {{$vencidomsj}}
                                @elseif($fechaAcuse && $porVencer) 
                                    {{ date('d/m/Y', strtotime($orders[$i]->fecha_fin_plazo)) }} {{$porVencermsj}}
                                    @else
                                        {{-- {{ date('d/m/Y', strtotime($orders[$i]->fecha_fin_plazo)) }} --}}
                                        {{ $orders[$i]->fecha_fin_plazo ? date('d/m/Y', strtotime($orders[$i]->fecha_fin_plazo)) : '' }}
                                @endif

                        </span>
                    </td>
                    <td> {{ $orders[$i]->nombre_fiscal }}</td>
                    
                    <td> {{ $orders[$i]->dpto }}-{{ $orders[$i]->distrito }}-{{ $orders[$i]->localidad }}</td>
                    <td> {{ $orders[$i]->sub_componente }}</td>
                    <td> {{ $orders[$i]->estado_orden }}</td>
                </tr>
            @endfor
        </tbody>
    </table>
</body>

</html>