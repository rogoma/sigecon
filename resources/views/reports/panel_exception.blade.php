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
        font-size:8px;        
    }
    thead tr{
        background-color:#dddddd;        
        padding:2px;
        font-size:8px;
    }

    h2{
        text-align: center;
        font-size:10px;
        margin-bottom:5px;
    }
    h3{
        text-align: left;
        font-size:10px;
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
    .columna2 { width: 3%; text-align: left;}
    .columna3 { width: 20%; text-align: left;}    
    .columna4 { width: 4%; text-align: center;}
    .columna5 { width: 3%; text-align: center;} 
    .columna6 { width: 8%; text-align: left;} 
    .columna7 { width: 4%; text-align: center;} 
    .columna8 { width: 4%; text-align: center;} 
    .columna9 { width: 5%; text-align: left;} 
    .columna10 { width: 5%; text-align: left;} 
    .columna11 { width: 5%; text-align: center;} 

    p.centrado {
        text-align: center;
}

</style> 
<body>
<p class="centrado"> <img src="img/logoV.jpg"> </p> 
        <h2>Listado de Procesos - Llamados</h2>
        <table>
            <tr>                
                <th>#</th>
                <th>ID.</th> 
                <th>Nombre del Llamado</th>
                <th>Modalidad</th>
                <th>Objeto Gasto</th>
                <th>Total del PAC</th>
                <th>CDP N°</th>
                <th>Fecha CDP</th>
                <th>Estado</th>
                <th>Estado Interno</th>
                <th>Fecha de Apertura</th>                
                <th>Proveedor Adjudicado</th>                               
                <th>Monto Adjudicado</th>
                <th>Contrato Nº</th>
                <th>Fecha Contrato</th>
                <th>Código de Contratación</th>
                <th>Fecha C.C.</th>                
            </tr>           
            {{-- @foreach($orders AS $f) --}}
            @for ($i = 0; $i < count($orders); $i++)
            <tr>
                <td class="columna1">{{ ($i+1) }}</td>
                <td class="columna2">{{ number_format($orders[$i]->dncp_pac_id,'0', ',','.') }}</td> 

                {{-- <td class="columna3"> {{ $orders[$i]->modality_code }} {{ is_null($orders[$i]->number)? "-" : $orders[$i]->number }} {{ $orders[$i]->order_description }}</td>}}</td>              --}}
                @if ($orders[$i]->covid==0)                                                        
                    <td class="columna3"> {{ $orders[$i]->modality_code }} {{ is_null($orders[$i]->number)? "-" : $orders[$i]->number }}/{{$orders[$i]->year }} {{ $orders[$i]->order_description }}</td>}}</td>             
                @else                                                            
                    <td class="columna3"> {{ $orders[$i]->modality_code }} {{ is_null($orders[$i]->number)? "-" : $orders[$i]->number }}/{{$orders[$i]->year }} {{ $orders[$i]->order_description }} (PROCESO COVID)</td>}}</td>             
                    {{-- <td style="font-size: 13px; font-weight: bold; color:blue;background-color:yellow;">Proceso COVID</td> --}}
                @endif
                
                <td class="columna4"> {{ $orders[$i]->modality_code }}</td>
                <td class="columna5"> {{ $orders[$i]->exp_obj_code }}</td>
                <td class="columna6"> {{ number_format($orders[$i]->total_amount,'0', ',','.') }}</td>
                <td class="columna7"> {{ number_format($orders[$i]->cdp_number,'0', ',','.') }}</td>
                <td class="columna8"> {{ is_null($orders[$i]->cdp_date)? "-" : date('d/m/Y', strtotime($orders[$i]->cdp_date)) }}</td>
                <td class="columna9"> {{ $orders[$i]->order_state_description }}</td>
                <td class="columna10"> {{ $orders[$i]->order_state_description }}</td>
                <td class="columna11"> {{ is_null($orders[$i]->begin_date)? "-" : date('d/m/Y', strtotime($orders[$i]->begin_date)) }}</td>
                {{-- <td class="columna3"> {{ $orders[$i]->urgency_state }}</td> --}}
                {{-- <td class="columna8">{{ number_format($orders[$i]->dncp_pac_id,'0', ',','.') }}</td>  --}}
                {{-- <td class="columna4"> {{ $orders[$i]->dependency }}</td> --}}
                {{-- <td class="columna4"> {{ $orders[$i]->modality_type }}</td>    --}}
                {{-- <td class="columna5"> {{ $orders[$i]->dncp_resolution_number }}</td> --}}
                {{-- <td class="columna7"> {{ $orders[$i]->code_supexp }}</td>                            --}}
                {{-- <td class="columna9"> {{ date('d/m/Y', strtotime($orders[$i]->form4_date)) }}</td> --}}                
            </tr>
            @endfor
            {{-- @endforeach --}}
        </table>
    </div>
</body>
</html>