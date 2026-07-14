<table class="table table-busered table-hover mt-3">
    <thead class="thead-dark">
      <tr>
        <th>#</th>
        <th>ID</th>
        <th>Descripción</th>
        {{-- <th>Tipo_dependencia</th>
        <th>Tipo UOC</th>
        <th>N° UOC</th>                
        <th>SICP</th>          --}}
      </tr>
    </thead>
    <tbody>
        {{-- @foreach ($users as $user) --}}
        @for ($i = 0; $i < count($order_pres); $i++)         
          <tr>
            <td>{{($i+1)}}</td>
            <td>{{$order_pres[$i]->id}}</td>
            <td>{{$order_pres[$i]->description}}</td>            
          </tr>
        @endfor
    </tbody>
  </table>