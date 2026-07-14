<!DOCTYPE html>
<html>
<head>
    <title>Reporte Formulario N° 4</title>
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
    }
    tr:nth-child(even){
        /* background-color:#dddddd;         */
        padding:2px;
        font-size:8px;
    }

    h2{
        text-align: center;
        font-size:11px;
        margin-bottom:5px;
    }
    h3{
        text-align: left;
        font-size:11px;
        margin-bottom:5px;
    }
    body{
        /* background:#f2f2f2; */
    }
    #providers, #providers tr, #providers tr td {
        border: none;
    }
    #providers tr td {
        font-weight: bolder;
        padding: 4px;
    }
    .item thead tr {
        background-color: #f2f2f2;
    }
    .section{
        margin-top:30px;
        padding:50px;
        background:#fff;
    }
    .pdf-btn{
        margin-top:30px;
    }
</style>    
<body>
    <img src="img/logoH.jpg">    
    <h2><span class="f-w-600" style="text-decoration: underline">FORMULARIO N°4</span> - MODELO DE FORMATO DE ANALISIS DE PRECIO DE REFERENCIA (Adjuntar al expediente de Requerimiento) </h2>    
    <div class="text-right">
        {{-- <h2>{{ $f4_1->first()->form4_city }}, {{ $f4_1->first()->form4_date }}</h2> --}}
        {{-- <h2>{{ $f4_1->first()->form4_city }}</h2> --}}
    </div>
    <h3>Conforme a la Resolución DNCP N° {{ $order->dncp_resolution_number }} de fecha {{ $order->dncp_resolution_date }}, se procede a realizar el cálculo de precio referencial a ser aplicado a la solicitud de llamado denominado: "{{ $order->description }}"</h3>  
    
    <h2>1° parámetro utilizado: solicitud de presupuestos a empresas</h2>  
    <h3>Se ha solicitado presupuesto a varios potenciales oferentes, remitidas vía correo a cada empresa y en cada una consta el recibo conforme a lo siguiente:</h3>  
        <table id="providers">            
            @for ($i = 0; $i < count($f4_1); $i = $i+3)
            <tr>
                <td>{{ ($i+1) . '. ' . $f4_1[$i]->providers_description}}</td>
                @if ($i + 1 < count($f4_1))
                    <td>{{ ($i+2) . '. ' .$f4_1[$i+1]->providers_description}}</td>
                @endif
                @if ($i + 2 < count($f4_1))
                    <td>{{ ($i+3) . '. ' . $f4_1[$i+2]->providers_description}}</td>
                @endif
            </tr> 
            @endfor
        </table>
    <h3>2° parámetro utilizado: histórico de adjudicación (Fuente www.dncp.gov.py)</h3>      
    <h3>De acuerdo al histórico de adjudicaciones del SICP (Sistema de informaciones de Contrataciones Públicas) se establece precios ofertados y adjudicados de llamados anteriores</h3>      
    <h3>En combinación de las dos fuentes de obtención de precios referenciales se procede a promediar el precio referencial quedando de la siguiente manera:</h3>
            @foreach($order->items()->orderBy('item_number')->get() AS $item)
            <table class="item mb-2">
                <thead>
                    <tr>
                        <th>ITEM</th>
                        <th>DESCRIPCIÓN</th>
                        @foreach ($item->itemAwardHistories as $item_award_history)
                            @if (empty($item_award_history->budget_request_provider_id))
                                <th>I.D. Nº {{ $item_award_history->dncp_pac_id }}</th>
                            @else
                                <th> {{ $item_award_history->budgetRequestProvider->provider->description }} </th>
                            @endif
                        @endforeach
                        <th>PRECIO REFERENCIAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $item->item_number }}</td>
                        <td>{{ $item->level5CatalogCode->description }}</td>
                        @php
                            $total_amount = 0;
                        @endphp

                        @foreach ($item->itemAwardHistories as $item_award_history)
                            @php
                                $total_amount += $item_award_history->amount;
                            @endphp
                            <td>{{ 'Gs. '.$item_award_history->amountFormat() }}</td>
                        @endforeach
                            {{-- ***** Falta definir que mostrar cuando itemawardhistores da división / 0 *****  --}}
                            @if ($item->itemAwardHistories->count())>0
                                <td>{{ 'Gs. '.number_format(round($total_amount / $item->itemAwardHistories->count()),0,",",".") }}</td>
                            @endif
                    </tr>
                </tbody>
            </table>
            @endforeach
        </table>
    <h3>Observación: Los precios puestos en los llamados son referenciales, es decir al sólo efecto de determinar un presupuesto (una presunción de lo que podría costar el bien) sin embargo son los competidores (oferentes) quienes finalmente determinan el precio en función a sus propios costos, mercado, competitividad y márgen de utilidades no así la convocante.</h3>      
    <h3>También es necesario mencionar que los precios de mercado son dinámicos, por lo tanto, pueden variar constantemente en función a las condiciones económicas no sólo del país sino también de la región.</h3>      
    </div>
</body>
</html>


            