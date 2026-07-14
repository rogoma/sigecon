<table class="table table-bordered table-hover mt-3">
    <thead class="thead-dark">
      <tr>
          <th>PAC_ID</th>
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
    </thead>
    <tbody>
        @foreach ($orders as $order)
      <tr>
        {{-- <th scope="row">{{ $order->order_id }}</th>         --}}        
        {{-- <td>{{ number_format($order->dncp_pac_id,0,",",".")}}</td> --}}
        <td>{{ $order->dncp_pac_id}}</td>
        {{-- <td>{{ $order->modality_code }} {{ is_null($order->number)? "-" : $order->number }}/{{$order->year }} {{ $order->order_description }}</td> --}}
        @if ($order->covid==0)                                                        
          <td>{{ $order->modality_code }} {{ is_null($order->number)? "-" : $order->number }}/{{$order->year }} {{ $order->order_description }}</td>            
        @else                                                            
          <td>{{ $order->modality_code }} {{ is_null($order->number)? "-" : $order->number }}/{{$order->year }} {{ $order->order_description }} (PROCESO COVID)</td>
        @endif
        {{-- <td>{{$order->order_description}}</td> --}}
        <td>{{$order->modality_code}}</td>
        <td>{{$order->exp_obj_code}}</td>                
        
        {{-- SIN FORMATO SEPARADOR DE MILES - PARA PODER SUMAR EN PLANILLA --}}
        <td>{{ $order->total_amount}}</td>
        {{-- <td>{{ number_format($order->total_amount,0,",",".")}}</td> --}}

        <td>{{$order->cdp_number}}</td>

        @if ($order->cdp_date == null)
          <td></td>
        @else
          <td>{{ date('d/m/Y', strtotime($order->cdp_date))}}</td>
        @endif

        <td>{{$order->order_state_description }}</td>
        <td>{{$order->order_state_description }}</td>        
        
        @if ($order->begin_date == null)
          <td></td>
        @else
          <td>{{ date('d/m/Y', strtotime($order->begin_date))}}</td>
        @endif

        <td>{{$order->provider}}</td>

        {{-- SIN FORMATO SEPARADOR DE MILES - PARA PODER SUMAR EN PLANILLA --}}
        <td>{{ $order->monto_adjudica }}</td>        
        {{-- <td>{{ number_format($order->monto_adjudica,0,",",".")}}</td>         --}}
        
        <td>{{$order->contract_number}}</td>
        
        @if ($order->contract_date == null)
          <td></td>
        @else
          <td>{{ date('d/m/Y', strtotime($order->contract_date))}}</td>
        @endif

        <td>{{$order->cc_number}}</td> 
        
        @if ($order->cc_date == null)
          <td></td>
        @else
          <td>{{ date('d/m/Y', strtotime($order->cc_date))}}</td>
        @endif
      </tr>
      @endforeach
    </tbody>
  </table>