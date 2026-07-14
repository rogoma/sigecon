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
                <th>Dependencia Superior</th>
                <th>Dependencia</th>
                <th>Tipo_dependencia</th>
                <th>Tipo UOC</th>
                <th>N° UOC</th>                
                <th>SICP</th>                
                {{-- <th>Permisos</th> --}}
            </tr>           
            @foreach($dependencies AS $f)
            <tr>
                <td> {{ $f->sup_dependencia }}</td>                                
                <td> {{ $f->dependencia }}</td>
                <td> {{ $f->tipo_dependencia }}</td>    
                <td> {{ $f->tipo_uoc }}</td>   
                <td> {{ $f->uoc_number }}</td>                      
                <td> {{ $f->sicp }}</td>                                
                
                {{-- <td class="columna1"> {{ $f->sup_dependencia }}</td>                                
                <td class="columna2"> {{ $f->dependencia }}</td>
                <td class="columna3"> {{ $f->tipo_dependencia }}</td>    
                <td class="columna4"> {{ $f->tipo_uoc }}</td>   
                <td class="columna5"> {{ $f->uoc_number }}</td>                      
                <td class="columna6"> {{ $f->sicp }}</td>                                 --}}
                
                {{-- <td class="columna2"> {{ $f->dependency }}</td>   
                <td class="columna3"> {{ $f->ad_referendum }}</td>                   
                <td class="columna5"> {{ $f->plurianualidad }}</td>       --}}
            </tr>
            @endforeach
        </table>
    </div>
</body>
</html>