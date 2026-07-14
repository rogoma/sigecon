<table>
        <thead>
            <tr>
                <th>#</th>
                <th>N°CONTRATO-CONTRATISTA</th>
                <th>N°ORDEN-FECHA</th>
                <th>MONTO ORDEN</th>
                <th>FECHA INICIO PLAZO</th>
                <th>DIAS PLAZO</th>
                {{-- <th>FECHA FIN PLAZO</th> --}}
                <th>FISCAL</th>
                <th>DPTO-DISTRITO-LOCALIDAD</th>
                <th>SUB_COMPONENTE</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < count($orders); $i++)                
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td> {{ $orders[$i]->nro_contrato }} - {{ $orders[$i]->contratista }}</td>
                    <td> {{ $orders[$i]->nro_orden }} - {{ date('d/m/Y', strtotime($orders[$i]->fecha_orden)) }}</td>                    
                    <td> {{ number_format($orders[$i]->monto_orden, '0', ',', '.') }} </td>

                    <td> {{ $orders[$i]->plazo }}</td>
                    <td> {{ date('d/m/Y', strtotime($orders[$i]->fecha_acuse_contr)) }}</td> 
                    
                    
                    <td> {{ $orders[$i]->nombre_fiscal }}</td>                    
                    <td> {{ $orders[$i]->dpto }}-{{ $orders[$i]->distrito }}-{{ $orders[$i]->localidad }}</td>
                    <td> {{ $orders[$i]->sub_componente }}</td>
                </tr>
            @endfor
        </tbody>
    </table>