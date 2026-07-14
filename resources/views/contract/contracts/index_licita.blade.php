@extends('layouts.app')

@push('styles')
<style type="text/css">

.table td, .table th {
    padding: 0.2rem 0.5rem;
    font-size: 14px
}
.tab-content.card-block {
    padding: 1.25rem 0.5rem;
}

/* .columna1 { width: 3%; text-align: center;}
.columna2 { width: 50%; text-align: left;}
.columna3 { width: 5%; text-align: center;}
.columna4 { width: 1%; text-align: left;}
.columna5 { width: 5%; text-align: center;}
.columna6 { width: 5%; text-align: center;}
.columna7 { width: 5%; text-align: center;}
.columna8 { width: 5%; text-align: center;} */

p.centrado {

}
</style>
@endpush

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-list bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Llamados - Listado de Llamados de Licitaciones</h5>
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        <br>
                            <a href="pdf/panel_contracts0" class="btn btn-outline-primary" target="_blank"> TOTAL LLAMADOS</a>
                            <a href="pdf/panel_contracts1" class="btn btn-outline-warning" target="_blank">EN CURSO</a>
                            <a href="pdf/panel_contracts6" class="btn btn-outline-success" target="_blank">EN PROCESO RESCISIÓN</a>
                            <a href="pdf/panel_contracts2" class="btn btn-outline-warning" target="_blank">RESCINDIDOS</a>
                            <a href="pdf/panel_contracts3" class="btn btn-outline-danger" target="_blank">CERRADOS</a>

                            {{-- <a href="pdf/panel_contracts4" class="btn btn-outline-primary" target="_blank">DETALLE PÓLIZAS</a> --}}
                            {{-- <a href="/contracts/exportarexcel6" class="btn btn-outline-primary" target="_blank">EXCEL PÓLIZAS</a> --}}
                            {{-- <a href="/users/exportarexcel" class="btn btn-success">Bajar_en_Excel</a> --}}

                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class=" breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('contracts.index') }}">Llamados</a>
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
                                <div class="card-header">
                                    <div class="float-left">
                                        <h5>Listado de Llamados de Licitaciones</h5>
                                    </div>
                                    <br>
                                    {{-- @if (Auth::user()->hasPermission(['derive_contracts.contracts.show','contracts.contracts.create','admin.orders.create'])) --}}
                                    @if (Auth::user()->hasPermission(['admin.orders.create']))
                                        <div class="float-left">
                                            <br>
                                            <a href="{{ route('contracts.create') }}" title="Agregar llamado" class="btn btn-primary">Agregar Llamado</a>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-block">
                                    <div class="dt-responsive table-responsive">
                                        <table id="contracts" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Responsable</th>
                                                    <th>Llamado</th>
                                                    <th>IDDNCP</th>                                                    
                                                    <th>Link DNCP</th>
                                                    <th>AÑO</th>
                                                    <th>Monto total LLAMADO</th>
                                                    <th>Contratista-RUC</th>
                                                    <th>Modalidad</th>
                                                    <th>Estado</th>
                                                    <th>Tipo Contrato</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @for ($i = 0; $i < count($contracts); $i++)
                                                <tr>
                                                    <td> {{ ($i+1) }}</td>
                                                    <td> {{ $contracts[$i]->dependency->description }}</td>
                                                    <td> {{ $contracts[$i]->description }}</td>
                                                    <td> {{ number_format($contracts[$i]->iddncp,'0', ',','.') }} </td>                                                    
                                                    <td style="color:#ff0000">{{ $contracts[$i]->linkdncp }}</td>
                                                    <td> {{ number_format($contracts[$i]->year_adj,'0', ',','.') }} </td>
                                                    <td> Gs.{{ number_format($contracts[$i]->total_amount,'0', ',','.') }} </td>
                                                    <td>{{ $contracts[$i]->provider->description }}-{{ $contracts[$i]->provider->ruc }}</td>
                                                    <td>{{ $contracts[$i]->modality->code }}-{{ $contracts[$i]->modality->description }}</td>

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


{{-- <script type="text/javascript"> --}}
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#contracts').DataTable({
                "columnDefs": [
                    {
                        // { "width": "30%", "targets": 0 },  // Define el ancho de la primera columna
                        // { "width": "30%", "targets": 1 },  // Define el ancho de la segunda columna
                        "targets": 4, // Índice de la columna que deseas personalizar
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