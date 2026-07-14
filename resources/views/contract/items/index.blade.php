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
                        {{-- <h5>Orden de Ejecución N° {{ $order->number }}</h5> --}}
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
                            <a href="{{ route('contracts.index',$contract->id ) }}">Órdenes</a>                            
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
                                        <h5>Rubro de Componentes</h5>
                                    </div>
                                    <div class="float-right">
                                    </div>
                                </div>                                
                                <div class="card-block">
                                    <div class="dt-responsive table-responsive">
                                        <table id="items" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>                                                    
                                                    <th>N° item</th>
                                                    <th>Cod_rubro</th>
                                                    <th>Rubro</th>
                                                    <th>Cant.</th>
                                                    <th>Unid.</th>
                                                    <th>Precio UNIT. MO</th>
                                                    <th>Precio UNIT. MAT</th>
                                                    <th>Precio TOT. MO</th>
                                                    <th>Precio TOT. MAT</th>
                                                    <th style="width: 120px; text-align: center;">Acciones</th>
                                                    {{-- <th style="width: 120px; text-align: center;"> Valor
                                                        <input type="checkbox" id="select-all" title="Seleccionar todos">
                                                    </th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- @php
                                                    $tot_price_mo = 0; 
                                                    $tot_price_mat = 0;    
                                                @endphp --}}
                                                
                                                @for ($i = 0; $i < count($items); $i++)
                                                    <tr>                                                        
                                                        @if($items[$i]->rubro_id == '9999')
                                                            <td></td>
                                                            <td></td>
                                                            <td style="font-size: 16px; font-weight: bold;">{{ $items[$i]->subitem->description }}</td>
                                                            {{-- <td>{{ $items[$i]->rubro->subitems->description }}</td> --}}
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        @else
                                                            <td style="text-align: center;">{{ $items[$i]->item_number }}</td>
                                                            <td style="text-align: center;">{{ $items[$i]->rubro->code }}</td>
                                                            <td>{{ $items[$i]->rubro->description }}</td>                                                        
                                                            <td style="text-align: center;">{{ $items[$i]->quantity }}</td>
                                                            <td style="text-align: center;">{{ $items[$i]->rubro->orderPresentations->description }}</td>
                                                            <td style="text-align: center;">{{ number_format($items[$i]->unit_price_mo, '0', ',', '.') }}</td>
                                                            <td style="text-align: center;">{{ number_format($items[$i]->unit_price_mat, '0', ',', '.') }}</td>
                                                            {{-- <td style="text-align: center;">{{ number_format($items[$i]->tot_price_mo, '0', ',', '.') }}</td>
                                                            <td style="text-align: center;">{{ number_format($items[$i]->tot_price_mat, '0', ',', '.') }}</td> --}}
                                    
                                                            {{-- @php
                                                                $tot_price_mo += $items[$i]->tot_price_mo;
                                                                $tot_price_mat += $items[$i]->tot_price_mat;
                                                            @endphp --}}
                                    
                                                            <td style="text-align: center;">                                                            
                                                                <button type="button" title="Editar" class="btn btn-warning btn-icon" onclick="updateItem({{ $items[$i]->id }})">
                                                                    <i class="fa fa-pencil"></i>
                                                                </button>

                                                                <button type="button" title="Borrar" class="btn btn-danger btn-icon" onclick="deleteItemAwardHistories({{$items[$i]->id }})">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        @endif                                                        
                                                    </tr>
                                                @endfor
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="6"></td>
                                                    <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow;">TOTALES:</td>
                                                    {{-- <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow; text-align: center;">{{ number_format($tot_price_mo, '0', ',', '.') }}</td>
                                                    <td style="font-size: 16px; font-weight: bold; color: red; background-color: yellow; text-align: center;">{{ number_format($tot_price_mat, '0', ',', '.') }}</td> --}}
                                                    <td colspan="3"></td>
                                                </tr>
                                            </tfoot>
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
