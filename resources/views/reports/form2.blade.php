<!DOCTYPE html>
<html>
<head>
    <title>Reporte Formulario N° 2</title>
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> --}}
</head>
<style type="text/css">
    table{
        font-family: arial,sans-serif;
        border-collapse:collapse;
        width:100%;
        font-size:10px;
    }
    td, th{
        border:1px solid #dddddd;
        text-align: left;
        padding:2px;        
    }
    tr:nth-child(even){
        /* background-color:#dddddd;         */
        padding:2px;
    }

    h2{
        text-align: center;
        font-size:14px;
        margin-bottom:5px;
    }
    h3{
        text-align: center;
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
    }
    .pdf-btn{
        margin-top:30px;
    }
    .columna1 { width: 5%; }
    .columna2 { width: 50%; }
    .columna3 { width: 45%; }
</style>    
<body>
    <img src="img/logoV.jpg">    
    <h2>FORMULARIO N°2 - PLANILLA DE REQUERIMIENTO-REQUISITO DE SOLICITUD DE ADQUISICIÓN DE BIENES Y SERVICIOS</h2>
    <h2>REQUISITO DE SOLICITUD DE ADQUISICIÓN DE BIENES Y SERVICIOS</h2>    
    <h3>EL PRESENTE FORMULARIO ESTA IDEADO PARA SERVIR DE GUÍA PARA LA PREPARACIÓN DE SOLICITUDES DE CONTRATACIÓN Y DEBERÁ VENIR ACOMPAÑADO DEL DESARROLLO EXPLÍCITO DE CADA REFERENCIA</h3>  
        <table>            
            @foreach($orders AS $f)            
            <tr>
                <td class="columna1">1</td>
                <td class="columna2">DEPENDENCIA:</td>
                <td class="columna3">{{$f->dependency}}</td>
            </tr>
            <tr>
                <td class="columna1">2</td>
                <td class="columna2">DESCRIPCIÓN:</td>
                <td class="columna3">{{$f->order_description}}</td>
            </tr>
            <tr>
                <td class="columna1">3</td>
                <td class="columna2">ADREFERENDUM:</td>
                <td class="columna3">{{ ($f->ad_referendum) ? "SÍ" : "NO" }}</td>
            </tr>
            <tr>
                <td class="columna1">4</td>
                <td class="columna2">PLURIANUALIDAD:</td>
                <td class="columna3">{{ ($f->plurianualidad) ? "SÍ" : "NO" }}</td>
            </tr>
            <tr>
                <td class="columna1">5</td>
                <td class="columna2">SISTEMA DE ADJUDICACIÓN POR:</td>
                <td class="columna3">{{$f->system_awarded_by}}</td>
            </tr>
            {{-- <tr>
                <td class="columna1">6</td>
                <td class="columna2">OBJETO DE GASTO A SER UTILIZADO:</td>
                <td class="columna3">{{$f->code_supexp,}}</td>
            </tr> --}}
            <tr>
                <td class="columna1">7</td>
                <td class="columna2">SUB OBJETO DE GASTO A SER UTILIZADO:</td>
                {{-- <td class="columna3">{{$f->exp_obj_code}}</td> --}}
                @if ($f->og2  == 0) && ($f->og3  == 0) && ($f->og4  == 0)&& ($f->og5  == 0) && ($f->og6  == 0)
                    <td class="columna3" style="text-align: left"> {{ $f->og1 }}</td>
                @else
                    @if ($f->og3  == 0) && ($f->og4  == 0)&& ($f->og5  == 0) && ($f->og6  == 0)
                        <td class="columna3" style="text-align: left"> {{ $f->og1 }}-{{ $f->og2 }} </td>
                    @else
                        @if ($f->og4  == 0)&& ($f->og5  == 0) && ($f->og6  == 0)
                            <td class="columna3" style="text-align: left"> {{ $f->og1 }}-{{ $f->og2 }}-{{ $f->og3 }} </td>
                        @else
                            @if ($f->og5  == 0) && ($f->og6  == 0)
                                <td class="columna3" style="text-align: left"> {{ $f->og1 }}-{{ $f->og2 }}-{{ $f->og3 }}-{{ $f->og4 }} </td>
                            @else
                                @if ($f->og6  == 0)
                                    <td class="columna3" style="text-align: left"> {{ $f->og1 }}-{{ $f->og2 }}-{{ $f->og3 }}-{{ $f->og4 }} -{{ $f->og5 }} </td>
                                @else
                                    <td class="columna3" style="text-align: left"> {{ $f->og1 }}-{{ $f->og2 }}-{{ $f->og3 }}-{{ $f->og4 }}-{{ $f->og5 }}-{{ $f->og6 }} </td>
                                @endif
                            @endif
                        @endif
                    @endif
                @endif
            </tr>                
            <tr>
                <td class="columna1">8</td>
                <td class="columna2">LINEAS PRESUPUESTARIAS A SER UTILIZADAS:</td>
                <td class="columna3">12-8-{{$f->program_type_code}}-{{$f->program_code}}-{{$f->activity_code}}-{{$f->ff_code}}-{{$f->of_code}}</td>
            </tr>
            <tr>
                <td class="columna1">9</td>
                <td class="columna2">FUENTE DE FINANCIAMIENTO Y MONTO A SER UTILIZADAS POR AÑO FISCAL:</td>
                <td class="columna3">{{$f->ff_code}}</td>
            </tr>                
            <tr>
                <td class="columna1">10</td>
                <td class="columna2">MONTO TOTAL DEL LLAMADO:</td>
                <td class="columna3"> Gs.{{number_format($f->total_amount,'0', ',','.')}}</td>
            </tr>
            <tr>
                <td class="columna1">11</td>
                <td class="columna2">FONACIDE:</td>
                <td class="columna3">{{ ($f->fonacide) ? "SÍ" : "NO" }}</td>                
            </tr>
            <tr>
                <td class="columna1">12</td>
                <td class="columna2">MODALIDAD DEL LLAMADO:</td>
                <td class="columna3">{{$f->modality_type}}</td>
            </tr>
            <tr>
                <td class="columna1">13</td>
                <td class="columna2">LA CONVOCANTE ACEPTARÁ CATÁLOGOS, ANEXOS TÉCNICOS. FOLLETOS Y OTROS TEXTOS:</td>
                <td class="columna3">{{ ($f->catalogs_technical_annexes) ? "SÍ" : "NO" }}</td>                
            </tr>
            <tr>
                <td class="columna1">14</td>
                <td class="columna2">SE CONSIDERARÁN OFERTAS ALTERNATIVAS:</td>
                <td class="columna3">{{ ($f->alternative_offers) ? "SÍ" : "NO" }}</td>
                
            </tr>
            <tr>
                <td class="columna1">15</td>
                <td class="columna2">SE UTILIZARÁ LA MODALIDAD CONTRATO ABIERTO:</td>
                <td class="columna3">{{ ($f->open_contract) ? "SÍ" : "NO" }}</td>
                
            </tr>
            <tr>
                <td class="columna1">16</td>
                <td class="columna2">EL PERIODO DE TIEMPO ESTIMADO DE FUNCIONAMIENTO DE LOS BIENES:</td>
                <td class="columna3">{{$f->period_time}}</td>
            </tr>
            <tr>
                <td class="columna1">17</td>
                <td class="columna2">AUTORIZACIÓN DEL FABRICANTE:</td>
                <td class="columna3">{{ ($f->manufacturer_authorization) ? "SÍ" : "NO" }}</td>                
            </tr>
            <tr>
                <td class="columna1">18</td>
                <td class="columna2">ANTICIPO FINANCIERO, PROCENTAJE MONTO:</td>
                <td class="columna3">{{ ($f->financial_advance_percentage_amount) ? "SÍ" : "NO" }}</td>                
            </tr>
            <tr>
                <td class="columna1">19</td>
                <td class="columna2">ESPECIFICACIONES TÉCNICAS:</td>
                <td class="columna3">{{$f->technical_specifications}}</td>
            </tr>
            <tr>
                <td class="columna1">20</td>
                <td class="columna2">SOLICITUD DE MUESTRAS:</td>
                <td class="columna3">{{ ($f->samples) ? "SÍ" : "NO" }}</td>                
            </tr>
            <tr>
                <td class="columna1">21</td>
                <td class="columna2">PLAN DE ENTREGAS:</td>
                <td class="columna3">{{$f->delivery_plan}}</td>
            </tr>
            <tr>
                <td class="columna1">22</td>
                <td class="columna2">PROPUESTA DE REPRESENTANTES DE MIEMBROS DE COMITÉ DE EVALUACIÓN:</td>
                <td class="columna3">{{$f->evaluation_committee_proposal}}</td>
            </tr>
            <tr>
                <td class="columna1">23</td>
                <td class="columna2">CONDICIONES DE PAGO:</td>
                <td class="columna3">{{$f->payment_conditions}}</td>
            </tr>
            <tr>
                <td class="columna1">24</td>
                <td class="columna2">GARANTIA DEL LLAMADO:</td>
                <td class="columna3">{{$f->contract_guarantee}}</td>
            </tr>
            <tr>
                <td class="columna1">25</td>
                <td class="columna2">GARANTIA DEL BIEN O SERVICIO:</td>
                <td class="columna3">{{$f->product_guarantee}}</td>
            </tr>
            <tr>
                <td class="columna1">26</td>
                <td class="columna2">ADMINISTRADOR DEL CONTRATO:</td>
                <td class="columna3">{{$f->contract_administrator}}</td>
            </tr>
            <tr>
                <td class="columna1">27</td>
                <td class="columna2">VIGENCIA DEL CONTRATO:</td>
                <td class="columna3">{{$f->contract_validity}}</td>
            </tr>
            <tr>
                <td class="columna1">28</td>
                <td class="columna2">DOCUMENTOS ADICIONALES QUE DEBERÁ PRESENTAR EL OFERENTE QUE DEMUESTRAN QUE LOS BIENES OFERTADOS, CUMPLEN CON LAS ESPECIFICACIONES TÉCNICAS:</td>
                <td class="columna3">{{$f->additional_technical_documents}}</td>
            </tr>
            <tr>
                <td class="columna1">29</td>
                <td class="columna2">DOCUMENTOS ADICIONALES QUE DEBERÁ PRESENTAR EL OFERENTE QUE DEMUESTRAN QUE EL OFERENTE SE HALLA CALIFICADO PARA EJECUTAR EL CONTRATO:</td>
                <td class="columna3">{{$f->additional_qualified_documents}}</td>
            </tr>
            <tr>
                <td class="columna1">30</td>
                <td class="columna2">PLANILLA DE PRECIOS (ANEXO 1):</td>
                <td class="columna3">{{$f->price_sheet}}</td>
            </tr>
            <tr>
                <td class="columna1">31</td>
                <td class="columna2">TITULO DE PROPIEDAD, PLANOS APROBADOS POR LA MUNICIPALIDAD, LICENCIA AMBIENTAL:</td>
                <td class="columna3">{{$f->property_title}}</td>
            </tr>
            <tr>
                <td class="columna1">32</td>
                <td class="columna2">MEDIO MAGNÉTICO:</td>
                <td class="columna3">{{$f->magnetic_medium}}</td>
            </tr>
            <tr>
                <td class="columna1">33</td>
                <td class="columna2">DATOS DE LA PERSONA REFERENTE:</td>
                <td class="columna3">{{$f->referring_person_data}}</td>
            </tr>    
            @endforeach
        </table>
    </div>
</body>
</html>