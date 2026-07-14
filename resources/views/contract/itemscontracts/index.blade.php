@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/datatables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/buttons.datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/css/responsive.bootstrap4.min.css') }}">

    <style>
        #items td, 
        #items th {
            padding: 4px 8px;
            vertical-align: middle;
            border-left: 1px solid #ddd;  /* Línea vertical a la izquierda */
            border-right: 1px solid #ddd; /* Línea vertical a la derecha */
        }
    
        #items {
            border-collapse: collapse;
        }
    
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
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
                        <h5>Llamado:
                            {{ $contract->description . ' - ' . $contract->modality->description . ' - Contrato N° ' . $contract->number_year . ' - ' . $contract->provider->description }}
                        </h5>
                        <h5 style="color: red;">                            
                            Detalle de Rubros de Componente: {{ $items[0]->component->description }}
                        </h5>
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
                            <a href="{{ route('contracts.volver', $contract->id) }}">Contratos</a>
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
                                        <h5>Rubros del Componente</h5>
                                    </div>                                    
                                </div>                                
                                <div class="card-block">
                                    <div class="dt-responsive table-responsive">
                                        <table id="items" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>                                                    
                                                    <th>N° item</th>                                                    
                                                    <th>Rubro (Cod. - Descripción)</th>
                                                    <th>Cant.</th>
                                                    <th>Unid.</th>
                                                    <th>Precio UNIT. Mano de Obra</th>
                                                    <th>Precio UNIT. Materiales</th>                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $tot_price_mo = 0; 
                                                    $tot_price_mat = 0;    
                                                @endphp
                                                
                                                @for ($i = 0; $i < count($items); $i++)
                                                    <tr>                                                        
                                                        @if($items[$i]->rubro_id == '9999')
                                                            <td></td>
                                                            {{-- <td></td> --}}
                                                            <td style="font-size: 16px; font-weight: bold;">{{ $items[$i]->subitem->description }}</td>                                                            
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>                                                            
                                                        @else
                                                            <td style="text-align: center;">{{ $items[$i]->item_number }}</td>
                                                            <td style="text-align: left;">{{ $items[$i]->rubro->code }} - {{ $items[$i]->rubro->description }}</td>
                                                            {{-- <td>{{ $items[$i]->rubro->description }}</td>                                                         --}}
                                                            <td style="text-align: center;">{{ $items[$i]->quantity }}</td>
                                                            <td style="text-align: center;">{{ $items[$i]->rubro->orderPresentations->description }}</td>
                                                            <td style="text-align: center;">{{ number_format($items[$i]->unit_price_mo, '0', ',', '.') }}</td>
                                                            <td style="text-align: center;">{{ number_format($items[$i]->unit_price_mat, '0', ',', '.') }}</td>                                                           
                                    
                                                            @php
                                                                $tot_price_mo  += ($items[$i]->unit_price_mo * $items[$i]->quantity );
                                                                $tot_price_mat += ($items[$i]->unit_price_mat * $items[$i]->quantity );
                                                            @endphp
                                                        @endif                                                        
                                                    </tr>
                                                @endfor
                                            </tbody>                                            
                                                
                                            <tfoot>
                                                {{-- <tr>
                                                    <td colspan="4"></td>
                                                    <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow;">TOTALES:</td>
                                                    <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow; text-align: center;">{{ number_format($tot_price_mo, '0', ',', '.') }}</td>
                                                    <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow; text-align: center;">{{ number_format($tot_price_mat, '0', ',', '.') }}</td>
                                                    <td colspan="3"></td>
                                                </tr>                                                 --}}
                                            </tfoot>                                            
                                        </table>                                        
                                        <div class="text-center">
                                            <a href="{{ route('items-contracts.report', [$contract->id, $items[0]->component_id]) }}" class="btn btn-danger" target="_blank">VER EN PDF</a>
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
</div>
@endsection


@push('scripts')
    <script type="text/javascript">
        // $(document).ready(function() {
        //     $('#contracts').DataTable({
        //         "columnDefs": [
        //             {
        //                 // { "width": "30%", "targets": 0 },  // Define el ancho de la primera columna
        //                 // { "width": "30%", "targets": 1 },  // Define el ancho de la segunda columna
        //                 "targets": 5, // Índice de la columna que deseas personalizar
        //                 "data": "linkdncp",
        //                 "render": function (data, type, row, meta) {
        //                 // Puedes personalizar el contenido de la columna aquí
        //                 return '<a href="' + data + '" target="_blank" style="color:blue">Link DNCP</a>'; // Suponiendo que el campo a enlazar está en el índice 2
        //                 }
        //             }
        //         ]
        //     });
        // });

    </script>
@endpush
