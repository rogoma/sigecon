<!DOCTYPE html>
<html>
<head>
    <title>Llamado con Póliza</title>
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
        font-size:10px;
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

    #providers, #providers tr, #providers tr td {
        border: none;
    }
    #providers tr td {
        font-weight: bolder;
        padding: 4px;
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

{{-- SE PREGUNTA POR EL NOMBRE DEL METODO DE ACUERDO A ESO SE ESCOGE EL TITULO DEL REPORTE --}}
    <h2>INFORME DE VERIFICACIÓN DE PÓLIZAS Y GARANTÍAS</h2>
    <br>

    {{-- <h2>LLAMADOS</h2> --}}
    <table id="providers">
        @for ($i = 0; $i < count($contracts1); $i = $i+3)
        <tr>
            <td> Llamado N°: {{ $contracts1[$i]->iddncp }}  -  N°Contrato: {{ $contracts1[$i]->number_year }}</td>
        </tr>
        @endfor
        @for ($i = 0; $i < count($contracts1); $i = $i+3)
        <tr>
            <th> Descripción de la obra: {{ $contracts1[$i]->llamado }}</th>
        </tr>
        @endfor
        @for ($i = 0; $i < count($contracts1); $i = $i+3)
        <tr>
            <th> Empresa Contratista: {{ $contracts1[$i]->contratista }}</th>
        </tr>
        @endfor
        @for ($i = 0; $i < count($contracts1); $i = $i+3)
        <tr>
            <th> Dependencia Responsable: {{ $contracts1[$i]->dependencia }}</th>
        </tr>
        @endfor
    </table>
    {{-- <br> --}}
    <h2>PÓLIZAS Y GARANTÍAS</h2>
    <table>
        <tr>
            <th>Tipo Póliza</th>
            <th>N° Póliza</th>
            <th>Monto</th>
            <th>Vencimiento</th>
            {{-- <th>Cumple1</th>
            <th>Cumple2</th>
            <th>Cumple3</th> --}}
            <th>Comentarios</th>
        </tr>

        @for ($i = 0; $i < count($contracts2); $i++)
        <tr>
            <td> {{ $contracts2[$i]->polizas }}</td>
            <td> {{ $contracts2[$i]->number_policy }}</td>
            <td> {{ number_format($contracts2[$i]->amount,'0', ',','.') }} </td>
            <td> {{ date('d/m/Y', strtotime($contracts2[$i]->item_to )) }}</td>
            {{-- <td> {{ $contracts2[$i]->comments}}</td>
            <td> {{ $contracts2[$i]->comments}}</td>
            <td> {{ $contracts2[$i]->comments}}</td> --}}
            <td> {{ $contracts2[$i]->comments}}</td>
        </tr>
        @endfor
    </table>
    <br>
    {{-- MOSTRAR DATOS DE ITEMS_AWARD DEL CONTROLADOR --}}
    <h2>ENDOSOSO DE PÓLIZAS Y GARANTÍAS</h2>
    <table>
        <tr>
            <th>Ref. N° Póliza</th>
            <th>(Endoso)N° Póliza</th>
            <th>Monto</th>
            <th>Vencimiento</th>
            {{-- <th>Cumple1</th>
            <th>Cumple2</th>
            <th>Cumple3</th> --}}
            <th>Estado</th>
            <th>Comentarios</th>
        </tr>

        @for ($i = 0; $i < count($contracts3); $i++)
        <tr>
            {{-- <td> {{ $contracts3[$i]->number_policy}} - N° Póliza: {{ $contracts3[$i]->number_policy1}}</td> --}}
            <td> N° Póliza: {{ $contracts3[$i]->number_policy}}</td>
            <td> {{ $contracts3[$i]->number_policy1 }}</td>
            <td> {{ number_format($contracts3[$i]->amount1,'0', ',','.') }} </td>
            <td> {{ date('d/m/Y', strtotime($contracts3[$i]->item_to1 )) }}</td>
            {{-- <td> {{ $contracts3[$i]->comments1}}</td>
            <td> {{ $contracts3[$i]->comments1}}</td>
            <td> {{ $contracts3[$i]->comments1}}</td> --}}
            @if ($contracts3[$i]->state1 == 1)
                <td style="color:blue;font-weight">Activo</td>
            @else
                <td style="color:red;font-weight">Inactivo</td>
            @endif

            {{-- <td> {{ $contracts3[$i]->state1}}</td> --}}

            <td> {{ $contracts3[$i]->comments1}}</td>
        </tr>
        @endfor
    </table>


</body>
</html>
