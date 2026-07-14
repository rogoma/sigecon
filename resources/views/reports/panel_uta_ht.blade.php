<!DOCTYPE html>
<html>
<head>
    {{-- VISTA QUE MUESTRA DATOS DE ORDERS EN HTML --}}

    <title>Panel General de Llamados</title>
    {{-- <link rel="stylesheet" type="text/css" media="screen" href="styles.css" /> --}}

    {{-- <link href="css/bootstrap.min.css" rel="stylesheet"> --}}
    
    
    {{-- <link rel="stylesheet" href= "C:\Proyectos\sistedoc\public\css\bootstrap.min.css"> --}}

    {{-- <link rel="stylesheet" href= "{{asset('/css/bootstrap.min.css')}}"> --}}

    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> --}}
</head>

<style type="text/css">

    table{
        /* font-family: arial,sans-serif; */
        border-collapse:collapse;
        width:100%;
        font-size:12px;
    }
    td{
        border:1px solid #dddddd;
        text-align: left;
        padding:4px;
        font-size:12px;        
    }
    th{
        border:1px solid #dddddd;
        text-align: left;
        padding:4px;
        font-size:14px;        
    }
    thead tr{
        background-color:#dddddd;        
        padding:2px;
        font-size:18px;
    }

    h2{
        text-align: center;
        font-size:18px;
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
        font-size:12px;
    }
    .pdf-btn{
        margin-top:30px;
    }
    .columna1 { width: 2%; text-align: center;} 
    .columna2 { width: 3%; text-align: left;}
    .columna3 { width: 16%; text-align: left;}    
    .columna4 { width: 4%; text-align: center;}
    .columna5 { width: 3%; text-align: center;} 
    .columna6 { width: 8%; text-align: left;} 
    .columna7 { width: 4%; text-align: center;} 
    .columna8 { width: 6%; text-align: center;} 
    .columna9 { width: 5%; text-align: left;} 
    .columna10 { width: 5%; text-align: left;} 
    .columna11 { width: 5%; text-align: center;} 

    p.centrado {
        text-align: center;
}

</style> 
<body>
{{-- Para HTML usar rutas relativas --}}
{{-- <img src="../img/logoV.jpg"> --}}
<p class="centrado"> <img src="../img/logoV.jpg"> </p> 
{{-- <p class="centrado"> </p>  --}}
        <h2>Listado de Procesos - Llamados</h2>
        <table>
            <tr>                
                <th style="font-weight: bold; color:black">Cód. de Catál.</th>
                <th style="font-weight: bold; color:black">#</th>
                <th style="font-weight: bold; color:black">ID.</th> 
                <th style="font-weight: bold; color:black">Nombre del Llamado</th>
                <th style="font-weight: bold; color:black">Modalidad</th>
                <th style="font-weight: bold; color:black">Objeto Gasto</th>
                <th style="font-weight: bold; color:black">Total del PAC</th>
                <th style="font-weight: bold; color:black">CDP N°</th>
                <th style="font-weight: bold; color:black">Fecha CDP</th>
                <th style="font-weight: bold; color:black">Estado</th>
                <th style="font-weight: bold; color:black">Estado Interno</th>
                <th style="font-weight: bold; color:black">Fecha de Apertura</th>                
                <th style="font-weight: bold; color:black">Proveedor Adjudicado</th>                               
                <th style="font-weight: bold; color:black">Monto Adjudicado</th>
                <th style="font-weight: bold; color:black">Contrato Nº</th>
                <th style="font-weight: bold; color:black">Fecha Contrato</th>
                <th style="font-weight: bold; color:black">Código de Contratación</th>
                <th style="font-weight: bold; color:black">Fecha C.C.</th>                
            </tr>           
            
            @for ($i = 0; $i < count($orders); $i++)
            <tr>
                <td class="columna1">{{ ($i+1) }}</td>
                <td class="columna2">{{ number_format($orders[$i]->dncp_pac_id,'0', ',','.') }}</td>
            
                @if ($orders[$i]->covid==0)                                                        
                    <td class="columna3"> {{ $orders[$i]->modality_code }} {{ is_null($orders[$i]->number)? "-" : $orders[$i]->number }}/{{$orders[$i]->year }} {{ $orders[$i]->order_description }}</td></td>
                @else                                                            
                    <td class="columna3"> {{ $orders[$i]->modality_code }} {{ is_null($orders[$i]->number)? "-" : $orders[$i]->number }}/{{$orders[$i]->year }} {{ $orders[$i]->order_description }} (PROCESO COVID)</td></td>
                @endif

                <td class="columna5"> {{ $orders[$i]->modality_code }}</td>
                <td class="columna5"> {{ $orders[$i]->exp_obj_code }}</td>
                <td class="columna9"> {{ number_format($orders[$i]->total_amount,'0', ',','.') }}</td>
                <td class="columna7"> {{ number_format($orders[$i]->cdp_number,'0', ',','.') }}</td>
                <td class="columna7"> {{ is_null($orders[$i]->cdp_date)? "-" : date('d/m/Y', strtotime($orders[$i]->cdp_date)) }}</td>
                <td class="columna9"> {{ $orders[$i]->order_state_description }}</td>
                <td class="columna10"> {{ $orders[$i]->order_state_description }}</td>
                <td class="columna7"> {{ is_null($orders[$i]->begin_date)? "-" : date('d/m/Y', strtotime($orders[$i]->begin_date)) }}</td>
                <td class="columna6"> {{ $orders[$i]->provider }}</td>
                <td class="columna9"> {{ number_format($orders[$i]->monto_adjudica,'0', ',','.') }}</td>
                <td class="columna4">{{ number_format($orders[$i]->contract_number,'0', ',','.') }}</td>
                <td class="columna4"> {{ is_null($orders[$i]->contract_date)? "-" : date('d/m/Y', strtotime($orders[$i]->contract_date)) }}</td>
                <td class="columna4">{{ $orders[$i]->cc_number }}</td> 
                <td class="columna6"> {{ is_null($orders[$i]->cc_date)? "-" : date('d/m/Y', strtotime($orders[$i]->cc_date)) }}</td>
            </tr>
            @endfor
            
        </table>
    </div>
</body>
</html>