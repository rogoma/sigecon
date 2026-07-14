@extends('layouts.app')

@push('styles')
<style type="text/css">

p.centrado {

}

#contracts thead th {
    background-color: #347ead;
    color: #fff;
    border-color: #347ead;
    white-space: nowrap;
}

#contracts th:nth-child(3),
#contracts td:nth-child(3) {
    min-width: 260px;
    white-space: normal;
}
</style>
@endpush

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fas fa-list bg-c-blue"></i>
                    <div class="d-inline">
                        

                        {{-- si es de UOC, role = 30  no muestra reportes --}}
                        @if (Auth::user()->role->id == 30)
                            <h5>Listado de Llamados</h5>
                            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                            <br>
                            <a href="pdf/panel_contracts0" class="btn btn-outline-primary" target="_blank"> TOTAL LLAMADOS</a>
                            <a href="pdf/panel_contracts1" class="btn btn-outline-warning" target="_blank">EN CURSO</a>
                            <a href="pdf/panel_contracts6" class="btn btn-outline-success" target="_blank">EN PROCESO RESCISIÓN</a>
                            <a href="pdf/panel_contracts2" class="btn btn-outline-warning" target="_blank">RESCINDIDOS</a>
                            <a href="pdf/panel_contracts3" class="btn btn-outline-danger" target="_blank">CERRADOS</a>
                        @else
                            {{-- si es contratista role = 4 no muestra reportes --}}
                            @if (Auth::user()->role->id == 4)                                
                                <br>
                                <h5>Listado de Contratos para realizar Certificados</h5>
                                <br>
                            @else
                                <h5>Listado de Contratos</h5>
                                <br>    
                                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                <br>    
                                <a href="pdf/panel_orders1" class="btn btn-outline-primary" target="_blank"> TOTAL ORDENES</a>
                                <a href="pdf/tablero" class="btn btn-outline-danger" target="_blank"> TABLERO</a>
                                <a href="/orders/exportarorders2" class="btn btn-outline-success">TOTAL ORDENES EXCEL</a>                            
                            @endif
                        @endif

                       
                    </div>                    
                </div>
                @if (Auth::user()->hasPermission(['admin.orders.create']))
                                        <div class="float-left">
                                            <br>
                                            <a href="{{ route('contracts.create') }}" title="Agregar llamado" class="btn btn-primary">Agregar Llamado</a>
                                        </div>
                @endif                
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class=" breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('contracts.index') }}">Contratos</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">                                
                                <div class="card-block">
                                    <div class="dt-responsive table-responsive">
                                        <table id="contracts" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Dependencia</th>
                                                    <th>Llamado</th>
                                                    <th>IDDNCP</th>
                                                    <th>Año</th>
                                                    <th>Link DNCP</th>
                                                    <th>N°/Año</th>
                                                    <th>Contrato</th>
                                                    <th>Monto</th>
                                                    <th>Contratista</th>
                                                    <th>Estado</th>
                                                    <th>Tipo</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @for ($i = 0; $i < count($contracts); $i++)
                                                    <tr>
                                                        <td style="max-width: 10px"> {{ ($i+1) }}</td>
                                                        <td> {{ $contracts[$i]->dependency->description }}</td>
                                                        <td> {{ $contracts[$i]->description }}</td>
                                                        <td> {{ number_format($contracts[$i]->iddncp,'0', ',','.') }} </td>
                                                        <td> {{ number_format($contracts[$i]->year_adj,'0', ',','.') }} </td>
                                                        <td style="color:#ff0000">{{ $contracts[$i]->linkdncp }}</td>

                                                        <td> {{ $contracts[$i]->number_year }}</td>

                                                        @if ($contracts[$i]->open_contract == 1)
                                                            <td>Contrato Abierto</td>
                                                        @else
                                                            <td>Contrato Cerrado</td>
                                                        @endif

                                                        <td style="max-width: 150px"> Gs.{{ number_format($contracts[$i]->total_amount,'0', ',','.') }} </td>

                                                        <td>{{ $contracts[$i]->provider->description }}</td>

                                                        @if (in_array($contracts[$i]->contractState->id, [2,3,6]))
                                                            <td style="color:#ff0000">{{ $contracts[$i]->contractState->description }}</td>
                                                        @else
                                                            @if (in_array($contracts[$i]->contractState->id, [4]))
                                                                <td style="color:red;font-weight: bold;background-color:yellow">{{ $contracts[$i]->contractState->description }}</td>
                                                            @else
                                                                <td style="color:green">{{ $contracts[$i]->contractState->description }}</td>
                                                            @endif
                                                        @endif

                                                        <td>{{ $contracts[$i]->contractType->description }}</td>
                                                        <td>
                                                            <a href="{{ route('contracts.show', $contracts[$i]->id) }}" class="btn btn-outline-success">Ver Más</a>
                                                        </td>
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#contracts').DataTable({
                "autoWidth": false,
                "columnDefs": [
                    {
                        "targets": 2, // Columna "Llamado"
                        "width": "25%"
                    },
                    {
                        "targets": 5, // Índice de la columna que deseas personalizar
                        "data": "linkdncp",
                        "render": function (data, type, row, meta) {
                        // Puedes personalizar el contenido de la columna aquí
                        return '<a href="' + data + '" target="_blank" style="color:blue">Link DNCP</a>'; // Suponiendo que el campo a enlazar está en el índice 2
                        }
                    }
                ]
            });
        });
    </script>
@endpush
