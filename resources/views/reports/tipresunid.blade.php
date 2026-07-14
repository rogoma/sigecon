<!DOCTYPE html>
<html>
<head>
    <title>Reporte de de Tipos de Unidades de medidas(Pedidos)</title>
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> --}}
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
        padding:2px;
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
        padding:20px;
        background:#fff;
        font-size:8px;
    }
    .pdf-btn{
        margin-top:30px;
    }
    /* .columna1 { width: 3%; text-align: center;} 
    .columna2 { width: 3%; text-align: left;}
    .columna3 { width: 3%; text-align: left;}
    .columna4 { width: 7%; text-align: left;}
    .columna5 { width: 20%; text-align: left;}
    .columna6 { width: 5%; text-align: left;}
    .columna7 { width: 12%; text-align: left;}
    .columna8 { width: 8%; text-align: left;} */

</style> 
<body>
<img src="img/logoV.jpg">        
    <h2>Listado de Tipos de Unidades de Medidas(Pedidos)</h2>       
        <table>             
            <tr>
                <th>Id.</th>
                <th>Descripción</th>                
            </tr>                            
            @foreach($tipresunid AS $f)
            <tr>                
                <td class="columna1"> {{ $f->id}}</td>
                <td class="columna2"> {{ $f->description}}</td>                
            </tr>
            @endforeach
        </table>
    </div>
</body>
</html>