<!DOCTYPE html>
<html>
<head>
    <title>Reporte Formulario N° 1</title>
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> --}}
</head>
<style type="text/css">
    table{
        font-family: arial,sans-serif;
        border-collapse:collapse;
        width:100%;
        font-size:8px;
    }
    td{
        border:1px solid #dddddd;
        text-align: left;
        padding:8px;
    }
    tr:nth-child(even){
        background-color:#dddddd;
    }

    th {
       text-align: center;
    }

    h2{
        text-align: center;
        font-size:14px;
        margin-bottom:5px;
    }
    h3{
        text-align: left;
        font-size:12px;
        margin-bottom:5px;
    }
    body{
        /* background:#f2f2f2; */
    }
    .section{
        margin-top:15px;
        /* padding:5px; */
        background:#fff;
    }
    .pdf-btn{
        margin-top:30px;
    }
</style>
<body>
    <img src="img/logoH.jpg" width="100%">
    <h3 style="text-align: left">Formulario N° 1-PROYECTO DE PAC {{date("Y")}} - PARA APROBACIÓN</h3>
    <br>
    <h2>PROYECTO PROGRAMA ANUAL DE CONTRATACIONES {{date("Y")}}</h2>
    <br>
    <h3>DEPENDENCIA: MINISTERIO DE SALUD PÚBLICA Y BIENESTAR SOCIAL</h3>
    <h3>RESPONSABLE DE LA DEPENDENCIA: {{ $orders->first()->responsible }} </h3>
    <h3>UNIDAD/SUB UNIDAD A QUE CORRESPONDE: {{ $orders->first()->dependencies_description}} </h3>
        <table>
        <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>AÑOS</th>
                <th></th>
            </tr>
            <tr>
                <th>N°</th>
                <th>MODALIDAD</th>
                <th>DESCRIPCIÓN DEL LLAMADO</th>
                <th>FECHA ESTIM. DE INICIO</th>
                <th>OG</th>
                <th>TIPO PRESUP.</th>
                <th>PROGRAMA</th>
                <th>PROYECTO</th>
                <th>FF</th>
                <th>OF</th>
                <th>MONTO TOTAL</th>
                <th>{{date("Y")}}</th>
                <th>{{date("Y")+1}}</th>
                <th>{{date("Y")+2}}</th>
                <th>{{date("Y")+3}}</th>
                {{-- <th>2026</th> --}}
            </tr>
            @foreach($orders AS $f)
            <tr>
                <td style="text-align: center">{{ $f->number }}</td>
                <td style="text-align: center">{{ $f->modalities_code }}</td>
                <td>{{ $f->orders_description }}</td>
                <td style="text-align: center">{{ $f->begin_date}}</td>

                @if ($f->og2  == 0) && ($f->og3  == 0) && ($f->og4  == 0)&& ($f->og5  == 0) && ($f->og6  == 0)
                    <td style="text-align: center"> {{ $f->og1 }}</td>
                @else
                    @if ($f->og3  == 0) && ($f->og4  == 0)&& ($f->og5  == 0) && ($f->og6  == 0)
                        <td style="text-align: center"> {{ $f->og1 }}-{{ $f->og2 }} </td>
                    @else
                        @if ($f->og4  == 0)&& ($f->og5  == 0) && ($f->og6  == 0)
                            <td style="text-align: center"> {{ $f->og1 }}-{{ $f->og2 }}-{{ $f->og3 }} </td>
                        @else
                            @if ($f->og5  == 0) && ($f->og6  == 0)
                                <td style="text-align: center"> {{ $f->og1 }}-{{ $f->og2 }}-{{ $f->og3 }}-{{ $f->og4 }} </td>
                            @else
                                @if ($f->og6  == 0)
                                    <td style="text-align: center"> {{ $f->og1 }}-{{ $f->og2 }}-{{ $f->og3 }}-{{ $f->og4 }} -{{ $f->og5 }} </td>
                                @else
                                    <td style="text-align: center"> {{ $f->og1 }}-{{ $f->og2 }}-{{ $f->og3 }}-{{ $f->og4 }}-{{ $f->og5 }}-{{ $f->og6 }} </td>
                                @endif
                            @endif
                        @endif
                    @endif
                @endif

                <td style="text-align: center">{{ $f->pt }}</td>
                <td style="text-align: center">{{ $f->pc }}</td>
                <td style="text-align: center">{{ $f->activity_code }}</td>
                <td style="text-align: center">{{ $f->ff }}</td>
                <td style="text-align: center">{{ $f->fo }}</td>
                <td style="text-align: center">Gs.{{ number_format($f->total_amount,'0', ',','.') }}</td>
                {{-- <td style="text-align: center">Gs.{{ number_format($f->monto_2021,'0', ',','.') }}</td> --}}
                <td style="text-align: center">Gs.{{ number_format($f->monto_2022,'0', ',','.') }}</td>
                <td style="text-align: center">Gs.{{ number_format($f->monto_2023,'0', ',','.') }}</td>
                <td style="text-align: center">Gs.{{ number_format($f->monto_2024,'0', ',','.') }}</td>
                <td style="text-align: center">Gs.{{ number_format($f->monto_2025,'0', ',','.') }}</td>
            </tr>
            @endforeach

                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td style="text-align: right">TOTALES</td>
                <td style="text-align: center">Gs.{{ number_format($f->total_amount,'0', ',','.') }}</td>
                {{-- <td style="text-align: center">Gs.{{ number_format($f->total_amount_year,'0', ',','.') }}</td> --}}
        </table>
    </div>
</body>
</html>
