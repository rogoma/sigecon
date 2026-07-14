<!DOCTYPE html>
<html>
<head>
    <title>Detalle de Pólizas en Llamados</title>
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> --}}
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
</head>
<style type="text/css">
    table{
        font-family: arial,sans-serif;
        border-collapse:collapse;
        width:100%;
        font-size:8px;
    }
    td, th{
        border:1px solid #dddddd;
        text-align: left;
        padding:4px;
        font-size:8px;
    }
    thead tr{
        background-color:#dddddd;
        padding:2px;
        font-size:8px;
    }

    h2{
        text-align: center;
        font-size:12px;
        margin-bottom:5px;
    }
    h3{
        text-align: left;
        font-size:12px;
        margin-bottom:15px;
    }
    body{
        /* background:#f2f2f2; */
    }
    .section{
        margin-top:30px;
        padding:50px;
        background:#fff;
        font-size:8px;
    }
    .pdf-btn{
        margin-top:30px;
    }
    .columna1 { width: 1%; text-align: center;}
    .columna2 { width: 25%; text-align: left;}
    .columna3 { width: 7%; text-align: left;}
    .columna4 { width: 16%; text-align: left;}
    .columna5 { width: 2%; text-align: center;}
    .columna6 { width: 4%; text-align: center;}
    .columna7 { width: 4%; text-align: center;}
    .columna8 { width: 4%; text-align: center;}
    .columna9 { width: 5%; text-align: left;}
    .columna10 { width: 9%; text-align: center;}
    .columna11 { width: 2%; text-align: center;}

    p.centrado {
        text-align: center;
}

</style>
<body>
<p class="centrado"> <img src="img/logoVI.png"> </p>

{{-- @php
    var_dump ($contracts_poli);
    var_dump ($contracts_endo);exit;
@endphp --}}

{{-- @if(!$contracts_poli->isEmpty()) --}}
@if(!$contracts_poli->isNotEmpty())
    {{-- @php
    var_dump ($contracts_poli);
    // var_dump ($contracts_endo);exit;
    @endphp --}}
@else
    <h2 style="color:#ff0000">ALERTA DE DETALLES DE PÓLIZAS</h2>
        <table>
            <tr>
                <th>#</th>
                <th>Dependencia</th>
                <th>Llamado</th>
                <th>IDDNCP</th>
                <th>N°Contrato</th>
                <th>Contratista</th>
                <th>Tipo Contrato</th>
                <th>Monto total LLAMADO</th>
                <th>Fecha Alerta</th>
                <th>Fecha Vcto.</th>
                <th>Tipo Póliza</th>
                <th>N° Póliza</th>
                <th>Comentarios</th>
                {{-- <th>Modalidad</th> --}}
            </tr>

            @for ($i = 0; $i < count($contracts_poli); $i++)
            <tr>
                <td>{{ ($i+1) }}</td>
                <td> {{ $contracts_poli[$i]->dependencia }}</td>
                <td> {{ $contracts_poli[$i]->llamado }}</td>
                <td> {{ number_format($contracts_poli[$i]->iddncp,'0', ',','.') }} </td>
                <td> {{ $contracts_poli[$i]->number_year }}</td>
                <td> {{ $contracts_poli[$i]->contratista}}</td>
                <td> {{ $contracts_poli[$i]->tipo_contrato}}</td>
                <td> Gs.{{ number_format($contracts_poli[$i]->total_amount,'0', ',','.') }} </td>

                @if ($contracts_poli[$i]->fecha_tope_advance <= today())
                    <td style="color:#ff0000">{{ is_null($contracts_poli[$i]->fecha_tope_advance)? "-" : date('d/m/Y', strtotime($contracts_poli[$i]->fecha_tope_advance )) }}</td>
                @else
                    <td>{{ is_null($contracts_poli[$i]->fecha_tope_advance)? "-" : date('d/m/Y', strtotime($contracts_poli[$i]->fecha_tope_advance )) }}</td>
                @endif

                <td>{{ is_null($contracts_poli[$i]->vcto_adv)? "-" : date('d/m/Y', strtotime($contracts_poli[$i]->vcto_adv )) }}</td>
                <td> {{ $contracts_poli[$i]->polizas}}</td>
                <td> {{ $contracts_poli[$i]->number_policy}}</td>
                <td> {{ $contracts_poli[$i]->comments}}</td>
            </tr>
            @endfor
        </table>
@endif
{{-- @if (empty($contracts_endo)) --}}
@if(!$contracts_endo->isNotEmpty())
    {{-- @php
    // var_dump ($contracts_poli);
    var_dump ($contracts_endo);exit;
    @endphp --}}
@else
        <br>
        <h2 style="color:#ff0000">ALERTA DE DETALLES DE ENDOSOS</h2>
        <table>
            <tr>
                <th>#</th>
                <th>Dependencia</th>
                <th>Llamado</th>
                <th>IDDNCP</th>
                <th>N°Contrato</th>
                <th>Contratista</th>
                <th>Tipo Contrato</th>
                <th>Monto total LLAMADO</th>
                <th>Fecha Alerta</th>
                <th>Fecha Vcto.</th>
                <th>Tipo Póliza</th>
                <th>N° Póliza</th>
                <th>Tipo Endoso</th>
                <th>Comentarios</th>
                {{-- <th>Modalidad</th> --}}
            </tr>
            @for ($i = 0; $i < count($contracts_endo); $i++)
            <tr>
                <td>{{ ($i+1) }}</td>
                <td> {{ $contracts_endo[$i]->dependencia }}</td>
                <td> {{ $contracts_endo[$i]->llamado }}</td>
                <td> {{ number_format($contracts_endo[$i]->iddncp,'0', ',','.') }} </td>
                <td> {{ $contracts_endo[$i]->number_year }}</td>
                <td> {{ $contracts_endo[$i]->contratista}}</td>
                <td> {{ $contracts_endo[$i]->tipo_contrato}}</td>
                <td> Gs.{{ number_format($contracts_endo[$i]->amount_endoso,'0', ',','.') }} </td>

                @if ($contracts_endo[$i]->fecha_tope_advance_endo <= today())
                    <td style="color:#ff0000">{{ is_null($contracts_endo[$i]->fecha_tope_advance_endo)? "-" : date('d/m/Y', strtotime($contracts_endo[$i]->fecha_tope_advance_endo )) }}</td>
                @else
                    <td>{{ is_null($contracts_endo[$i]->fecha_tope_advance_endo)? "-" : date('d/m/Y', strtotime($contracts_endo[$i]->fecha_tope_advance_endo )) }}</td>
                @endif

                <td>{{ is_null($contracts_endo[$i]->vcto_adv_endo)? "-" : date('d/m/Y', strtotime($contracts_endo[$i]->vcto_adv_endo )) }}</td>
                <td> {{ $contracts_endo[$i]->polizas}}</td>
                <td> {{ $contracts_endo[$i]->number_policy_endoso}}</td>
                <td> {{ $contracts_endo[$i]->award_type_description}}</td>
                <td> {{ $contracts_endo[$i]->comments_endoso}}</td>
            </tr>
            @endfor
        </table>
@endif
    </div>
</body>
</html>
