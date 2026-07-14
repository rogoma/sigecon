<!DOCTYPE html>
<html>
<head>
    <title>Reporte Formulario N° 3</title>
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
        font-size:8px;        
    }
    thead tr{
        background-color:#dddddd;        
        padding:2px;
        font-size:8px;
    }

    h2{
        text-align: left;
        font-size:8px;
        margin-bottom:5px;
    }
    h3{
        text-align: left;
        font-size:8px;
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
    /* .columna1 { width: 5%; text-align: center;} 
    .columna2 { width: 5%; text-align: center;}
    .columna3 { width: 5%; text-align: center;}
    .columna4 { width: 5%; text-align: center;}
    .columna5 { width: 6%; text-align: center;}
    .columna6 { width: 5%; text-align: center;}
    .columna7 { width: 7%; text-align: center;}
    .columna8 { width: 12%; text-align: center;}
    .columna9 { width: 10%; text-align: center;}
    .columna10 { width: 11%; text-align: center;}
    .columna11 { width: 6%; text-align: center;}
    .columna12 { width: 5%; text-align: center;}
    .columna13 { width: 9%; text-align: center; }
    .columna14 { width: 10%; text-align: center; } */
</style>    
<body>
    <img src="img/logoH.jpg">    
    <h2>FORMULARIO N°3 - PLANILLA DE REQUERIMIENTO-ESPECIFICACIONES TÉCNICAS Y PRECIOS REFERENCIALES</h2>   
    <h3>DEPENDENCIA: {{ $orders->first()->dependency }} </h3>               
        <table> 
            <thead>           
                <tr>
                    <th>Lote</th>
                    <th>Ítem</th>
                    <th>O.G.</th>
                    <th>S.G.O.G.</th>
                    <th>0</th>
                    <th>F.F.</th>
                    <th>Cód.de Cátal.</th>
                    <th>Descripción del Bien/Servicio</th>
                    <th>EETT</th>
                    <th>Present.</th>
                    <th>U.M.</th>
                    <th>Precio Unitario</th>
                    <th>Pedido Mínimo</th>
                    <th>Pedido Máximo</th>                    
                    <th>Monto Mínimo</th>
                    <th>Monto Máximo</th>
                </tr>
                <tr>
                    <th>Utilizar cuando la adj. sea por lotes</th>
                    <th>Utilizar cuando la adj. sea por ítems</th>
                    <th>Indicar objeto de gasto</th>
                    <th>Indicar sub-grupo de objeto de gasto</th>
                    <th>Indicar</th>                    
                    <th>Indicar Fuente de Financiamiento</th>
                    <th>Indicar código de catálogo de la DNCP(de ser posible)</th>
                    <th>Descripción del bien o servicio</th>
                    <th>Especificaciones técnicas detalladas del bien o servicio a ser adquirido, en caso de obras anexar el cronograma de entrega, en caso de combustibles describir el valor en cupos y tarjetas</th>
                    <th>Indicar presentación (caja, envase, blíster, etc.)</th>
                    <th>Indicar unidad de medida (unidad,caja,evento, etc.)</th>                    
                    <th>Precio Unitario estimado (IVA incluido)</th>
                    <th>Cant. mínima</th>
                    <th>Cant. máxima</th>
                    <th>Monto mínimo</th>
                    <th>Monto máximo</th>
                </tr>
            <thead>
            <tbody>    
                @foreach($orders AS $f)
                <tr>
                    <td class="columna1">{{$f->batch}}</td>
                    <td class="columna2">{{$f->item_number}}</td>
                    <td class="columna3">{{$f->code_supexp}}</td>
                    <td class="columna4">{{$f->exp_obj_code}}</td>
                    <td class="columna5">12-8-{{$f->program_type_code}}-{{$f->program_code}}-{{$f->activity_code}}</td>
                    <td class="columna6">{{$f->ff_code}}</td>
                    <td class="columna7">{{$f->code}}</td>
                    <td class="columna8">{{$f->level5_catalog_codes_description}}</td>
                    <td class="columna9">{{$f->technical_specifications}}</td>
                    <td class="columna10">{{$f->order_presentations_description}}</td>
                    <td class="columna11">{{$f->order_measurement_units_description}}</td>
                    {{-- *** Ver si se agregará al reporte las cantidades y montos de min_quantity y total_amount_min *** --}}
                    <td class="columna13">Gs.{{number_format($f->unit_price,'0', ',','.')}}</td>
                    <td class="columna12">{{number_format($f->min_quantity,'0', ',','.')}}</td>
                    <td class="columna12">{{number_format($f->max_quantity,'0', ',','.')}}</td>
                    <td class="columna14">Gs.{{number_format($f->total_amount_min,'0', ',','.')}}</td>                   
                    <td class="columna14">Gs.{{number_format($f->total_amount,'0', ',','.')}}</td>                   
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>