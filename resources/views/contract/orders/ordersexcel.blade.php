<table class="table table-bordered table-hover mt-3">
    <thead class="thead-dark">
      <tr>
            <th>Fiscal</th>
            <th>N° OE</th>
            <th>Fecha Orden</th>
            <th>Monto Orden</th>
            <th>Distrito-Localidad</th>
            <th>Sub-Componente</th>
            <th>Plazo Inicio</th>
            <th>Plazo días</th>
            {{-- <th>Fecha Alerta</th>
            <th>Plazo Final</th> --}}
            <th>Estado</th>
      </tr>
    </thead>
    <tbody>
        @foreach($orders AS $f)
            <tr>
                <td> {{ $f->fiscal_name }} {{ $f->fiscal_lastname }}</td>
                <td> {{ $f->components_code }} - {{ $f->orders_number }}</td>   
                <td> {{ \Carbon\Carbon::parse($f->orders_date)->format('d/m/Y') }}                
                <td> {{ $f->orders_total_amount }}</td>
                <td> {{ $f->districts_description }} - {{ $f->orders_locality }}</td>
                <td> {{ $f->components_code }} - {{ $f->components_description }}</td>                
                <td> {{ \Carbon\Carbon::parse($f->sign_date)->format('d/m/Y') }} 
                <td> {{ $f->orders_plazo }}</td>                                               
                <td> {{ $f->order_states_description }}</td>
            </tr>
        @endforeach
    </tbody>
  </table>