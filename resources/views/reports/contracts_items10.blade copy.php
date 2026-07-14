<!DOCTYPE html>
<html>

<head>
    <title>ORDEN DE EJECUCIÓN</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
</head>
<style type="text/css">
    @page {
        size: legal portrait;        
        margin: 100px 25px 100px 60px;        
    }

    body {
        margin-top: 3px;
        margin-bottom: 20px;
    }

    header {
        position: fixed;
        top: -80px;
        left: 0;
        right: 0;
        height: 100px;
        text-align: center;
        line-height: 1.5;
        font-size: 12px;
        border-bottom: 1px solid #ddd;
    }

    footer {
        position: fixed;
        bottom: -30px;
        left: 0;
        right: 0;
        height: 30px;
        text-align: center;
        font-size: 10px;
        color: #777;
        border-top: 1px solid solid #ddd;
    }

    table {
        font-family: arial, sans-serif;
        border: 1px solid black;
        width: 100%;
        font-size: 8px;
        line-height: 1;        
    }

    td,
    th {
        border: 1px solid #ddd;
        text-align: left;
        padding: 4px;
        font-size: 10px;
    }

    thead tr {
        background-color: white(0, 0, 0);
        padding: 2px;
        font-size: 8px;
    }

    h2 {
        text-align: center;
        font-size: 12px;
        margin-bottom: 5px;
    }

    h3 {
        text-align: left;
        font-size: 12px;
        margin-bottom: 15px;
    }

    .page-break {
        page-break-before: always;
    }

    .space {
        height: 500px; /* Espacio en blanco para la primera hoja */
    }
</style>

<body>
    <header>
        <img src="img/logoVI_2.png" alt="Logo" style="height: 70px;"><br>
        <h2>{{ $contracts1[0]->contracts_description }} - Lote {{ $contracts1[0]->batch }} - ID N° {{ $contracts1[0]->contracts_iddncp }}</h2>
    </header>

    <footer>
        Página <span class="page"></span> de <span class="topage"></span>
    </footer>

    <!-- Primera hoja -->
    <div>
        <br><br>
        <table id="orders_items">
            <br><br><br><br>
            @for ($i = 0; $i < count($contracts1); $i = $i + 3)
                <table border="1">
                    <tr>
                        <td colspan="1">FISCALIZACIÓN: </td>
                        <td colspan="6"> {{ $contracts0[$i]->description2 }}</td>
                        <td colspan="5">ORDEN DE EJECUCIÓN N°: {{ $contracts0[$i]->code }} - {{ $contracts0[$i]->number}}</td>
                        <br><br><br><br>                    
                    </tr>
                    <tr>
                        <td colspan="1">CONTRATO: </td>
                        <td colspan="11"> {{ $contracts0[$i]->number_year }} - Lote: {{ $contracts0[0]->batch }} <br> {{ $contracts0[$i]->description }}</td>
                        <br><br>
                    </tr>
                    <tr>
                        <td colspan="1">LOCALIDAD: </td>                    
                        <td colspan="11">{{ $contracts0[$i]->locality }}, Distrito de: {{ $contracts0[$i]->description3 }}, Departamento de: {{ $contracts0[$i]->description4 }}</td>

                    </tr>
                    <tr>
                        <td colspan="1">REFERENCIA: </td>                    
                        <td colspan="11">{{ $contracts0[$i]->code }} - {{ $contracts0[$i]->description1 }} - {{ $contracts0[$i]->reference }}</td>                        
                    </tr>                    
                </table>                
            @endfor                       
        </table>

        <table>
            <tfoot>             
                <tr>
                    <td colspan="12"
                        style="font-size: 12px; font-weight: bold; text-align: left; padding: 15px; position: relative; left: -80px; width: calc(100% + 160px);">
                        COMENTARIO: {{ $contracts1[0]->orders_comments }}
                        <span style="background-color: yellow; padding: 2px 4px;"> Se establece un PLAZO DE EJECUCIÓN DE
                            {{ $contracts1[0]->orders_plazo }} días a partir de la fecha de firma de acuse de recibo
                            por parte del Contratista.</span>
                            
                    </td>                    
                </tr>                

                <tr>
                    <td colspan="4"
                        style="font-size: 10px; font-weight: bold; text-align: center; padding-top: 30px;">
                        Fecha Emisión: {{ \Carbon\Carbon::parse($contracts1[0]->orders_date)->format('d/m/Y') }}
                    </td>
                    <td colspan="4"
                        style="font-size: 10px; font-weight: bold; text-align: center; padding-top: 30px;">
                        Firma Fiscalización
                    </td>
                    <td colspan="4"
                        style="font-size: 10px; font-weight: bold; text-align: center; padding-top: 30px;">
                        {{ $contracts1[0]->fiscal_name }} {{ $contracts1[0]->fiscal_lastname }}  <br> Aclaración Firma Fiscal
                    </td>
                </tr>
                <tr>
                    <td colspan="4"
                        style="font-size: 10px; font-weight: bold; text-align: center; padding-top: 30px;">                       
                        @if(!empty($contracts1[0]->sign_date))
                                Fecha Recepción: {{ \Carbon\Carbon::parse($contracts1[0]->sign_date)->format('d/m/Y') }}
                        @else
                                Fecha Recepción:
                        @endif
                    </td>
                    <td colspan="4"
                        style="font-size: 10px; font-weight: bold; text-align: center; padding-top: 30px;">
                        Firma Contratista
                    </td>
                    <td colspan="4"
                        style="font-size: 10px; font-weight: bold; text-align: center; padding-top: 30px;">
                        Aclaración Firma Contratista
                    </td>
                </tr>
                
                <tr>
                    @if($contracts1[0]->order_states_id == 5)
                        <td colspan="12">                                                    
                            <h1 style="color: red; text-align: center; font-size: 3em;">ORDEN ANULADA</h1>
                        </td>                    
                    @endif
                </tr>
            </tfoot> 
        </table>
                
    </div>

    <!-- Espacio en blanco para la primera hoja -->
    <div class="space"></div>

    

    <!-- Segunda hoja -->
    <div class="page-break"></div>

    <div>
        <br><br>
        @for ($i = 0; $i < count($contracts0); $i = $i + 3)
                <table border="1">
                    <tr>
                        <td colspan="1">FISCALIZACIÓN: </td>
                        <td colspan="6"> {{ $contracts0[$i]->description2 }}</td>
                        <td colspan="5">ORDEN DE EJECUCIÓN N°: {{ $contracts0[$i]->code }} - {{ $contracts0[$i]->number}}</td>
                        <br><br><br><br>                    
                    </tr>
                    <tr>
                        <td colspan="1">CONTRATO: </td>
                        <td colspan="11"> {{ $contracts0[$i]->number_year }} - Lote: {{ $contracts0[0]->batch }} <br> {{ $contracts0[$i]->description }}</td>
                        <br><br>
                    </tr>
                    <tr>
                        <td colspan="1">LOCALIDAD: </td>                    
                        <td colspan="11">{{ $contracts0[$i]->locality }}, Distrito de: {{ $contracts0[$i]->description3 }}, Departamento de: {{ $contracts0[$i]->description4 }}</td>

                    </tr>
                    <tr>
                        <td colspan="1">REFERENCIA: </td>                    
                        <td colspan="11">{{ $contracts0[$i]->code }} - {{ $contracts0[$i]->description1 }} - {{ $contracts0[$i]->reference }}</td>                        
                    </tr>                    
                </table>                
            @endfor
                        
        <h2>DETALLE DE RUBROS</h2>
        <table>
            <tr>
                <th style="text-align: center">N° item</th>
                <th style="text-align: center; width: 280px;">Cod. - Rubro</th>
                <th style="text-align: center">Cant.</th>
                <th style="text-align: center">Unid.</th>
                <th style="text-align: center; width: 60px;">Precio UNIT. MO</th>
                <th style="text-align: center; width: 60px;">Precio UNIT. MAT</th>
                <th style="text-align: center; width: 70px;">Precio TOT. MO</th>
                <th style="text-align: center; width: 70px;">Precio TOT. MAT</th>
            </tr>

            @php
                $tot_price_mo = 0;
                $tot_price_mat = 0;
            @endphp

            @for ($i = 0; $i < count($contracts2); $i++)
                <tr>
                    @if ($contracts2[$i]->rubros_code == '9999')
                        <td> </td>
                        <td><b> {{ $contracts2[$i]->sub_items_oi_description }} </b></td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                    @else
                        <td style="text-align: center"> {{ $contracts2[$i]->items_orders_item_number }}</td>
                        <td style="text-align: left"> {{ $contracts2[$i]->rubros_code }} -
                            {{ $contracts2[$i]->rubros_description }}</td>
                        <td style="text-align: center">
                            {{ number_format($contracts2[$i]->items_orders_quantity, 2, ',', ',') }}</td>

                        <td style="text-align: center"> {{ $contracts2[$i]->order_presentations_description }}</td>

                        <td style="text-align: center">
                            {{ number_format($contracts2[$i]->items_orders_unit_price_mo, '0', ',', '.') }} </td>

                        <td style="text-align: center">
                            {{ number_format($contracts2[$i]->items_orders_unit_price_mat, '0', ',', '.') }} </td>

                        <td style="text-align: center">
                            {{ number_format($contracts2[$i]->items_orders_quantity * $contracts2[$i]->items_orders_unit_price_mo, '0', ',', '.') }}
                        </td>
                        <td style="text-align: center">
                            {{ number_format($contracts2[$i]->items_orders_quantity * $contracts2[$i]->items_orders_unit_price_mat, '0', ',', '.') }}
                        </td>

                        @php
                            $tot_price_mo +=
                                $contracts2[$i]->items_orders_quantity * $contracts2[$i]->items_orders_unit_price_mo;
                            $tot_price_mat +=
                                $contracts2[$i]->items_orders_quantity * $contracts2[$i]->items_orders_unit_price_mat;
                        @endphp
                    @endif
                </tr>
            @endfor            
        </table>
        {{-- <br> --}}
        <table>
            <tfoot>
                <tr>
                    <td colspan="9"></td>
                    <td colspan="8" style="font-size: 10px; text-align: right; padding-right: 5px;">SUB-TOTAL GS.: </td>
                    <td style="font-size: 10px; text-align: center;"> {{ number_format($tot_price_mo, '0', ',', '.') }}
                    </td>
                    <td style="font-size: 10px; text-align: center;">
                        {{ number_format($tot_price_mat, '0', ',', '.') }}</td>
                </tr>

                <tr>
                    <td colspan="9"></td>
                    <td colspan="8" style="font-size: 10px; text-align: right; padding-right: 5px;">TOTAL GS. CON IVA:</td>
                    <td style="font-size: 10px; text-align: center;"> </td>
                    <td style="font-size: 10px; text-align: center;">
                        {{ number_format($tot_price_mo + $tot_price_mat, '0', ',', '.') }}
                </tr>                
            </tfoot>            
        </table>

        <table>
            <tr>
                <td colspan="16"
                    style="font-size: 12px; font-weight: bold; text-align: left; padding: 15px; position: relative; left: -80px; width: calc(100% + 160px);">
                    COMENTARIO: {{ $contracts1[0]->orders_comments }}
                    <span style="background-color: yellow; padding: 2px 4px;"> Se establece un PLAZO DE EJECUCIÓN DE
                        {{ $contracts1[0]->orders_plazo }} días a partir de la fecha de firma de acuse de recibo
                        por parte del Contratista.</span>
                </td>
            </tr> 
            
            <tr>
                @if($contracts1[0]->order_states_id == 5)
                    <td colspan="16">                                                    
                        <h1 style="color: red; text-align: center; font-size: 3em;">ORDEN ANULADA</h1>
                    </td>                    
                @endif
            </tr>
        </table>

    </div>
</body>

</html>

