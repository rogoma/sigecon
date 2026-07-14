<table class="table table-busered table-hover mt-3">
    <thead class="thead-dark">
      <tr>
        <th>#</th>
        <th>CIN°</th>
        <th>USUARIO</th>
        <th>DEPENDENCIA</th>
        <th>CARGO</th>
        <th>ROL</th>
        <th>ESTADO</th>
      </tr>
    </thead>
    <tbody>        
        @for ($i = 0; $i < count($users); $i++)         
          <tr>
            <td>{{($i+1)}}</td>
            <td>{{$users[$i]->ci}}</td>
            <td>{{$users[$i]->nombre}} {{$users[$i]->apellido}}</td>
            <td>{{$users[$i]->dependencia}}</td>                        
            <td>{{$users[$i]->cargo}}</td>
            <td>{{$users[$i]->rol}}</td>            
            @if ($users[$i]->state == 1)                                                        
              <td>Activo</td>
            @else
              <td style="color:red;font-weight: bold">Inactivo</td>                                                        
            @endif
          </tr>
        @endfor
    </tbody>
  </table>