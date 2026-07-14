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
    .columna2 { width: 8%; text-align: left;}
    /* .columna3 { width: 11%; text-align: left;} */
    .columna4 { width: 7%; text-align: left;} */
    /* .columna5 { width: 5%; text-align: left;} */
    /* .columna6 { width: 8%; text-align: left;}     */
</style> 
<body>
<img src="img/logoV.jpg">        
    <h2>Usuarios del Sistema</h2>
        <table>
            <tr>
                <th>Documento</th>
                <th>Usuario</th>
                {{-- <th>Dependencia</th> --}}
                <th>Rol</th>
                {{-- <th>Cargo</th> --}}
                {{-- <th>Permisos</th> --}}
            </tr>           
            @foreach($users AS $f)
            <tr>                
                <td class="columna1"> {{ $f->document }}</td>
                <td class="columna2"> {{ $f->name }}-{{ $f->lastname }}</td>                                
                {{-- <td class="columna3"> {{ $f->dependency }}</td>    --}}
                <td class="columna4"> {{ $f->description }}</td>    --}}
                {{-- <td class="columna5"> {{ $f->position }}</td>    --}}
                {{-- <td class="columna6"> {{ $f->permission }}</td>       --}}
                
                {{-- <td class="columna1"> {{ $f->order_description }}</td>                                
                <td class="columna2"> {{ $f->dependency }}</td>   
                <td class="columna3"> {{ $f->ad_referendum }}</td>                   
                <td class="columna5"> {{ $f->plurianualidad }}</td>      --}}
            </tr>
            @endforeach
        </table>
    </div>
</body>
</html>