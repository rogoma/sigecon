<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Distritos</title>
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> --}}
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
</head>
<style type="text/css">
    table{
        font-family: arial,sans-serif;
        border-collapse:collapse;
        width:100%;
        font-size:10px;
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
    /* .columna1 { width: 2%; text-align: center;} 
    .columna2 { width: 8%; text-align: left;}
    .columna3 { width: 11%; text-align: left;}
    .columna4 { width: 7%; text-align: left;} */
    /* .columna5 { width: 5%; text-align: left;}
    .columna6 { width: 4%; text-align: center;} */ */
    /* .columna6 { width: 8%; text-align: left;}     */
</style> 
<body>
<img src="img/logoV.jpg">        
    <h2>Distritos del Sistema</h2>
        <table>
            <tr>
                {{-- PARA TITULOS EN COLORES --}}
                {{-- <th style="color:blue;font-weight: bold">CIN°</th> --}}
                <th>Departamento</th>
                <th>Distrito</th>                                
            </tr>           
            @foreach($districts AS $f)
            <tr>
                <td> {{ $f->departamento }}</td>
                <td> {{ $f->distrito }}</td>                   
            </tr>
            @endforeach
        </table>
    </div>
</body>
</html>