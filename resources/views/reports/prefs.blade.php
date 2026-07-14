<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Usuarios</title>
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
    .columna1 { width: 3%; text-align: center;} 
    .columna2 { width: 3%; text-align: left;}
    .columna3 { width: 3%; text-align: left;}
    .columna4 { width: 7%; text-align: left;} */
    .columna5 { width: 20%; text-align: left;}
    .columna6 { width: 5%; text-align: left;}
    .columna7 { width: 12%; text-align: left;}
    .columna8 { width: 8%; text-align: left;}

</style> 
<body>
<img src="img/logoV.jpg">        
    <h2>Listado de Tipos de Presentaciones</h2>        
        <table>             
            <tr>
                <th>N°Llam.</th>
                <th>N°item</th>
                <th>Cód-Cat</th>
                <th>Descripción</th>
                <th>DNCP ID</th>
                <th>Proveedor</th>
                <th>Monto</th>                
            </tr>                            
            @foreach($prefs AS $f)
            <tr>                
                <td class="columna2"> {{ $f->nro_llamado }}</td>
                <td class="columna3"> {{ $f->nro_item }}</td>
                <td class="columna4"> {{ $f->codc5 }}</td>
                <td class="columna5"> {{ $f->descrip_cc5 }}</td>
                <td class="columna6"> {{number_format($f->dncp_pac_id,'0', ',','.')}}</td>                                
                <td class="columna7"> {{ $f->proveedor }}</td>   
                <td class="columna8"> Gs.{{number_format($f->monto,'0', ',','.')}} </td>                                     
            </tr>
            @endforeach
        </table>
    </div>
</body>
</html>