<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Modalidades</title>
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
        padding:8px;
        font-size:8,5px;        
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
    .columna1 { width: 15%; text-align: left;} 
    .columna2 { width: 5%; text-align: center;}
    .columna3 { width: 5%; text-align: center;}
    .columna4 { width: 5%; text-align: center;}
    .columna5 { width: 5%; text-align: center;}
    .columna6 { width: 5%; text-align: center;}
    .columna7 { width: 5%; text-align: center;}
    .columna8 { width: 5%; text-align: center;}
    .columna9 { width: 5%; text-align: center;}    
</style> 
<body>
<img src="img/logoV.jpg">        
    <h2>Plazos para los procesos en los diferentes procedimientos son los siguientes, teniendo en cuenta</h2>
        <table>
            <tr>
                <th>Modalidad/Código</th>
                <th>Verif.DNCP</th>
                <th>Verif.Reparos DNCP</th>
                <th>Publicación Prensa</th>                
                <th>Difusión Portal</th>
                <th>Plazo tope p/recepción consultas</th>                
                <th>Verif. adendas y aclaraciones</th>                
                <th>Publicación de adendas</th>
                <th>Publicación de aclaraciones</th>                
            </tr>           
            @foreach($modals AS $f)
            <tr>                
                <td class="columna1">{{ $f->description }}-{{ $f->code }}</td>                                
                <td class="columna2">{{ $f->dncp_verification }}</td>
                <td class="columna3">{{ $f->dncp_objections_verification}}</td>
                <td class="columna4">{{ $f->press_publication }}</td>
                <td class="columna5">{{ $f->portal_difusion }}</td>
                <td class="columna6">{{ $f->inquiries_reception }}</td>
                <td class="columna7">{{ $f->addendas_verification }}</td>
                <td class="columna8">{{ $f->addenda_publication }}</td>
                <td class="columna9">{{ $f->clarifications_publication }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</body>
</html>