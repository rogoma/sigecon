<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Usuarios</title>
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
        text-align: center;
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
    .columna6 { width: 4%; text-align: center;}
    /* .columna6 { width: 8%; text-align: left;}     */
</style> 
<body>
<img src="img/logoV.jpg">        
    <h2>Usuarios del Sistema</h2>
        <table>
            <tr>
                {{-- PARA TITULOS EN COLORES --}}
                {{-- <th style="color:blue;font-weight: bold">CIN°</th> --}}
                <th>CIN°</th>                
                <th>Usuario</th>
                {{-- <th>Email</th> --}}
                <th>Dependencia</th>                
                <th>Cargo</th>
                <th>Rol</th>
                <th>Estado</th>
                {{-- <th>Permisos</th> --}}
            </tr>           
            @foreach($users AS $f)
            <tr>                
                <td class="columna1"> {{ number_format($f->ci,'0', ',','.') }}</td>
                <td class="columna2"> {{ $f->nombre }}-{{ $f->apellido }}</td>                                
                {{-- <td class="columna3"> {{ $f->email }}</td> --}}
                <td class="columna3"> {{ $f->dependencia }}</td>    --}}
                <td class="columna4"> {{ $f->cargo }}</td>   
                <td class="columna5"> {{ $f->rol }}</td>
                {{-- <td class="columna1"> {{ $f->state}}</td> --}}
                @if ($f->state == 1)
                    <td class="columna6">Activo</td>                    
                @else
                    {{-- <td class="columna6">Inactivo</td> --}}
                    <td style="color:red;font-weight: bold">Inactivo</td>                      
                @endif
                
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