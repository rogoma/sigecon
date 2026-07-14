<!DOCTYPE html>
<html>
<head>
    <title>Panel General de Llamados</title>
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
    .columna1 { width: 1%; text-align: center;} 
    .columna2 { width: 25%; text-align: left;}
    .columna3 { width: 7%; text-align: left;}
    /* .columna4 { width: 7%; text-align: left;}  */
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
<p class="centrado"> <img src="img/logoV.jpg"> </p> 
    <h2>    </h2>
        <table>
            <tr>                
                <th>#</th>
                <th>MOD.</th> 
                <th>Nº LLAM.</th>
                <th>NOMBRE DEL LLAMADO</th>
                <th>METODO</th>
                <th>DEPENDENCIA</th>
                {{-- <th>ORIGEN</th> --}}                
                <th>RESOL.</th>                               
                <th>O.G.</th>
                <th>IDPAC</th>
                <th>MES PROGRAM.</th>
                <th>MONTO PROGRAMADO</th>
            </tr>           
            {{-- @foreach($orders AS $f) --}}
            @for ($i = 0; $i < count($orders); $i++)
            <tr>
                <td class="columna11">{{ ($i+1) }}</td>                
                {{-- <td>{{ is_null($orders[$i]->number)? $orders[$i]->description." - ".$orders[$i]->dependency->description : $orders[$i]->modality->code." N° ".$orders[$i]->number." - ".$orders[$i]->description." - ".$orders[$i]->dependency->description }}</td> --}}                
                <td class="columna6"> {{ $orders[$i]->modality_code }}</td>                                
                <td class="columna1"> {{ is_null($orders[$i]->number)? "-" : $orders[$i]->number }} </td>}}</td> 
                {{-- <td class="columna1"> {{ $orders[$i]->number }}</td> --}}
                <td class="columna2"> {{ $orders[$i]->order_description }}</td>
                <td class="columna3"> {{ $orders[$i]->modality_type }}</td>
                <td class="columna4"> {{ $orders[$i]->dependency }}</td>      
                {{-- <td class="columna4"> {{ $orders[$i]->modality_type }}</td>    --}}
                <td class="columna5"> {{ $orders[$i]->dncp_resolution_number }}</td>
                <td class="columna7"> {{ $orders[$i]->code_supexp }}</td>                     
                <td class="columna8">{{ number_format($orders[$i]->dncp_pac_id,'0', ',','.') }}</td>                
                {{-- <td class="columna9"> {{ date('d/m/Y', strtotime($orders[$i]->form4_date)) }}</td> --}}
                <td class="columna9"> {{ date('m/Y', strtotime($orders[$i]->begin_date)) }}</td>
                <td class="columna10">Gs.{{ number_format($orders[$i]->total_amount,'0', ',','.') }}</td>
            </tr>
            @endfor
            {{-- @endforeach --}}
        </table>
    </div>
</body>
</html>