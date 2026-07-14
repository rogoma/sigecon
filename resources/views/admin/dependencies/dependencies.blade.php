<table class="table table-busered table-hover mt-3">
    <thead class="thead-dark">
      <tr>
        <th>#</th>
        <th>Dependencia Superior</th>
        <th>Dependencia</th>
        <th>Tipo_dependencia</th>
        <th>Tipo UOC</th>
        <th>N° UOC</th>                
        <th>SICP</th>         
      </tr>
    </thead>
    <tbody>
        {{-- @foreach ($users as $user) --}}
        @for ($i = 0; $i < count($dependencies); $i++)         
          <tr>
            <td>{{($i+1)}}</td>
            <td>{{$dependencies[$i]->sup_dependencia}}</td>
            <td>{{$dependencies[$i]->dependencia}}</td>
            <td>{{$dependencies[$i]->tipo_dependencia}}</td>                        
            <td>{{$dependencies[$i]->tipo_uoc}}</td>
            <td>{{$dependencies[$i]->uoc_number}}</td>
            <td>{{$dependencies[$i]->sicp}}</td>
          </tr>
        @endfor
    </tbody>
  </table>