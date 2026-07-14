<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Dependencias</title>
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
    .section{
        margin-top:30px;
        padding:50px;
        background:#fff;
        font-size:8px;
    }
    .pdf-btn{
        margin-top:30px;
    }
    .columna1 { width: 2%; text-align: center;}
    .columna2 { width: 8%; text-align: left;}
    .columna3 { width: 11%; text-align: left;}
    .columna4 { width: 7%; text-align: left;} */
    .columna5 { width: 5%; text-align: left;}
    /* .columna6 { width: 8%; text-align: left;}     */
</style>
<body>
<img src="img/logoV.jpg">
    <h2>Dependencias</h2>
        <table>
            <tr>
                <th>#</th>
                <th>Nombre Contratista</th>
                <th>RUC</th>
                <th>Teléfono</th>
                <th>Email Oferta</th>
                <th>Email OCompra</th>
                <th>Representante</th>
            </tr>

             @php
                $counter = 1;
            @endphp

            @foreach($providers AS $f)
                <tr>
                    <td>{{ $counter }}</td>
                    <td> {{ $f->description }}</td>
                    <td> {{ $f->ruc }}</td>
                    <td> {{ $f->telefono }}</td>
                    <td> {{ $f->email_oferta }}</td>
                    <td> {{ $f->email_ocompra }}</td>
                    <td> {{ $f->representante }}</td>
                </tr>
                @php
                    $counter++;
                @endphp
            @endforeach
        </table>
    </div>
</body>
</html>
