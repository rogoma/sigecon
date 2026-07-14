<table class="table table-busered table-hover mt-3">
  <thead class="thead-dark">
    <h2>ALERTA DE DETALLES DE PÓLIZAS</h2>
    <tr>
      <th>#</th>
      <th>Dependencia</th>
      <th>Llamado</th>
      <th>IDDNCP</th>
      <th>N°Contrato</th>
      <th>Contratista</th>
      <th>Tipo Contrato</th>
      <th>Monto total LLAMADO</th>
      <th>Fecha Alerta</th>
      <th>Fecha Vcto.</th>
      <th>Tipo Póliza</th>
      <th>N° Póliza</th>
      <th>Comentarios</th>
    </tr>
  </thead>
    <tbody>
        @for ($i = 0; $i < count($contracts); $i++)
            <tr>
                <td>{{ ($i+1) }}</td>
                <td> {{ $contracts[$i]->dependencia }}</td>
                <td> {{ $contracts[$i]->llamado }}</td>
                <td> {{ number_format($contracts[$i]->iddncp,'0', ',','.') }} </td>
                <td> {{ $contracts[$i]->number_year }}</td>
                <td> {{ $contracts[$i]->contratista}}</td>
                <td> {{ $contracts[$i]->tipo_contrato}}</td>
                <td> Gs.{{ number_format($contracts[$i]->total_amount,'0', ',','.') }} </td>

                @if ($contracts[$i]->fecha_tope_advance <= today())
                    <td style="color:#ff0000">{{ is_null($contracts[$i]->fecha_tope_advance)? "-" : date('d/m/Y', strtotime($contracts[$i]->fecha_tope_advance )) }}</td>
                @else
                    <td>{{ is_null($contracts[$i]->fecha_tope_advance)? "-" : date('d/m/Y', strtotime($contracts[$i]->fecha_tope_advance )) }}</td>
                @endif

                <td>{{ is_null($contracts[$i]->vcto_adv)? "-" : date('d/m/Y', strtotime($contracts[$i]->vcto_adv )) }}</td>
                <td> {{ $contracts[$i]->polizas}}</td>
                <td> {{ $contracts[$i]->number_policy}}</td>
                <td> {{ $contracts[$i]->comentarios}}</td>
            </tr>
        @endfor
    </tbody>
</table>
