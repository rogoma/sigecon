@extends('layouts.app')

@push('styles')
    <style type="text/css">
        .contracts-index-page {
            --acta-primary: #2f6f97;
            --acta-primary-dark: #204d69;
            --acta-accent: #2196f3;
            --acta-success: #2eb85c;
            --acta-warning: #f9b115;
            --acta-danger: #e55353;
        }

        /* ---------- Hero / encabezado ---------- */
        .contracts-index-page .acta-hero {
            background: linear-gradient(135deg, var(--acta-primary) 0%, var(--acta-primary-dark) 100%);
            border-radius: 16px;
            border: 0;
            color: #fff;
            box-shadow: 0 8px 24px rgba(32, 77, 105, .25);
            overflow: hidden;
        }

        .contracts-index-page .acta-hero .breadcrumb {
            background: rgba(255, 255, 255, .12);
            border-radius: 30px;
        }

        .contracts-index-page .acta-hero .breadcrumb-item a {
            color: #fff;
            opacity: .9;
        }

        .contracts-index-page .acta-hero-icon {
            width: 56px;
            height: 56px;
            min-width: 56px;
            border-radius: 16px;
            background: rgba(255, 255, 255, .18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .contracts-index-page .acta-hero-eyebrow {
            text-transform: uppercase;
            letter-spacing: .08em;
            font-size: 12px;
            opacity: .85;
            font-weight: 600;
        }

        .contracts-index-page .acta-hero .btn-light {
            border-radius: 30px;
            font-weight: 700;
            padding: 8px 20px;
        }

        /* ---------- Chips de accesos rápidos ---------- */
        .contracts-index-page .acta-chips {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
            padding: 14px 18px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .contracts-index-page .acta-chips .btn {
            border-radius: 30px;
            font-weight: 600;
            font-size: 13px;
            padding: 8px 16px;
        }

        /* ---------- Card general ---------- */
        .contracts-index-page .acta-card {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 2px 14px rgba(0, 0, 0, .06);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .contracts-index-page .acta-card-header {
            background: #f7fafc;
            border-bottom: 1px solid #edf2f7;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .contracts-index-page .acta-card-header h5 {
            margin: 0;
            font-weight: 700;
            color: var(--acta-primary-dark);
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ---------- Tabla de contratos ---------- */
        .contracts-index-page #contracts thead th {
            background: linear-gradient(135deg, var(--acta-primary) 0%, var(--acta-primary-dark) 100%);
            color: #fff;
            border-color: var(--acta-primary);
            white-space: nowrap;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .02em;
        }

        .contracts-index-page #contracts th:nth-child(3),
        .contracts-index-page #contracts td:nth-child(3) {
            min-width: 260px;
            white-space: normal;
        }

        .contracts-index-page #contracts tbody tr:hover {
            background: #f5fbff;
        }

        .contracts-index-page .badge-pill {
            padding: 5px 12px;
            font-weight: 600;
        }

        .contracts-index-page .btn-ver-mas {
            border-radius: 30px;
            font-weight: 600;
            padding: 6px 16px;
        }

        /* ---------- Estado vacío ---------- */
        .contracts-index-page .acta-empty-state {
            text-align: center;
            padding: 40px 10px;
            color: #97a3ad;
        }

        .contracts-index-page .acta-empty-state i {
            font-size: 38px;
            margin-bottom: 10px;
            display: block;
            color: #cbd7de;
        }

        /* ---------- Responsive ---------- */
        @media (max-width: 767.98px) {
            .contracts-index-page .acta-hero {
                border-radius: 12px;
                text-align: center;
            }

            .contracts-index-page .acta-hero .d-flex {
                justify-content: center;
            }

            .contracts-index-page .acta-chips {
                justify-content: center;
            }

            /* En mobile, el mínimo de 260px para "Llamado" no deja lugar a que ninguna otra columna
               (ni siquiera Acciones) quepa junto a ella; se reduce para que el listado siga siendo útil */
            .contracts-index-page #contracts th:nth-child(3),
            .contracts-index-page #contracts td:nth-child(3) {
                min-width: 140px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="pcoded-content contracts-index-page">

        {{-- HERO --}}
        <div class="page-header card acta-hero mb-3">
            <div class="row align-items-center p-2">
                <div class="col-12 col-lg-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="acta-hero-icon"><i class="fa-solid fa-file-contract"></i></div>
                        <div class="ml-3">
                            <div class="acta-hero-eyebrow">Contratos</div>
                            @if (Auth::user()->role->id == 30)
                                <h4 class="mb-0 text-white">Listado de Llamados</h4>
                            @elseif (Auth::user()->role->id == 4)
                                <h4 class="mb-0 text-white">Listado de Contratos para realizar Certificados</h4>
                            @else
                                <h4 class="mb-0 text-white">Listado de Contratos</h4>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 text-lg-right mt-3 mt-lg-0">
                    <ul class="breadcrumb breadcrumb-title justify-content-lg-end mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa-solid fa-house"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('contracts.index') }}">Contratos</a></li>
                    </ul>
                    @if (Auth::user()->hasPermission(['admin.orders.create']))
                        <a href="{{ route('contracts.create') }}" title="Agregar llamado" class="btn btn-light">
                            <i class="fa-solid fa-plus mr-1"></i> Agregar Llamado
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- ACCESOS RÁPIDOS / REPORTES --}}
        @if (Auth::user()->role->id == 30)
            <div class="acta-chips mb-3">
                <a href="pdf/panel_contracts0" class="btn btn-outline-primary" target="_blank"><i class="fa-solid fa-file-lines mr-1"></i> Total Llamados</a>
                <a href="pdf/panel_contracts1" class="btn btn-outline-warning" target="_blank"><i class="fa-solid fa-hourglass-half mr-1"></i> En Curso</a>
                <a href="pdf/panel_contracts6" class="btn btn-outline-success" target="_blank"><i class="fa-solid fa-rotate mr-1"></i> En Proceso Rescisión</a>
                <a href="pdf/panel_contracts2" class="btn btn-outline-warning" target="_blank"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Rescindidos</a>
                <a href="pdf/panel_contracts3" class="btn btn-outline-danger" target="_blank"><i class="fa-solid fa-box-archive mr-1"></i> Cerrados</a>
            </div>
        @elseif (Auth::user()->role->id != 4)
            <div class="acta-chips mb-3">
                <a href="pdf/panel_orders1" class="btn btn-outline-primary" target="_blank"><i class="fa-solid fa-file-lines mr-1"></i> Total Órdenes</a>
                <a href="pdf/tablero" class="btn btn-outline-danger" target="_blank"><i class="fa-solid fa-table-columns mr-1"></i> Tablero</a>
                <a href="/orders/exportarorders2" class="btn btn-outline-success"><i class="fa-solid fa-file-excel mr-1"></i> Total Órdenes Excel</a>
            </div>
        @endif

        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card acta-card">
                                    <div class="acta-card-header">
                                        <h5><i class="fa-solid fa-list-check"></i> Listado de Contratos</h5>
                                        <span class="badge badge-pill" style="background:#eef5f9;color:var(--acta-primary-dark);">{{ count($contracts) }} registro{{ count($contracts) === 1 ? '' : 's' }}</span>
                                    </div>

                                    @if (count($contracts) === 0)
                                        <div class="acta-empty-state">
                                            <i class="fa-regular fa-folder-open"></i>
                                            No hay contratos para mostrar.
                                        </div>
                                    @else
                                        <div class="dt-responsive table-responsive">
                                            <table id="contracts" class="table table-striped table-bordered mb-0">
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
                                                                <td><span class="badge badge-pill badge-danger">{{ $contracts[$i]->contractState->description }}</span></td>
                                                            @else
                                                                @if (in_array($contracts[$i]->contractState->id, [4]))
                                                                    <td><span class="badge badge-pill badge-warning">{{ $contracts[$i]->contractState->description }}</span></td>
                                                                @else
                                                                    <td><span class="badge badge-pill badge-success">{{ $contracts[$i]->contractState->description }}</span></td>
                                                                @endif
                                                            @endif

                                                            <td>{{ $contracts[$i]->contractType->description }}</td>
                                                            <td>
                                                                <a href="{{ route('contracts.show', $contracts[$i]->id) }}" class="btn btn-outline-success btn-ver-mas">Ver Más</a>
                                                            </td>
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
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
                "responsive": true,
                "language": {
                    "search": "Buscar contrato:",
                    "lengthMenu": "Mostrar _MENU_ contratos",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ contratos",
                    "infoEmpty": "Sin contratos",
                    "zeroRecords": "No se encontraron contratos",
                    "paginate": { "previous": "Anterior", "next": "Siguiente" }
                },
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
                    },
                    // Prioridad de columnas al colapsar en pantallas angostas: las más relevantes se mantienen visibles
                    { "responsivePriority": 1, "targets": 12 },  // Acciones
                    { "responsivePriority": 2, "targets": 2 },   // Llamado
                    { "responsivePriority": 3, "targets": 10 },  // Estado
                    { "responsivePriority": 4, "targets": 9 },   // Contratista
                    { "responsivePriority": 5, "targets": 8 },   // Monto
                    { "responsivePriority": 10, "targets": [0, 1, 3, 4, 5, 6, 7, 11] },
                ]
            });
        });
    </script>
@endpush
