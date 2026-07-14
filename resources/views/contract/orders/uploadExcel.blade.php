@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Ítems</h5>
                        <span>Importar Ítemes</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class=" breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a><i class="fa-solid fa-house"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('orders.show', $order->id) }}">Pedido Nº {{ $order->id }}</a>
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
                                    {{-- <h5>{{ is_null($order->number)? $order->description : $order->modality->description." N° ".$order->number."-".$order->description }}<label class="label label-info m-l-5">Prioridad {{ $order->urgency_state }}</label></h5>                                            
                                    <h5>SIMESE: {{ is_null($order->simese->first()) ? '' : number_format($order->simese->first()['simese'],'0', ',','.')."-".$order->simese->first()['year'] }} </h5>
                                    <br><br> --}}
                                    {{-- <h5>Agregar Ítem al pedido Nº {{ $order->id }}</h5> --}}
                                    <h5><p style="font-size: 17px; font-weight: bold; color:#FF0000">Agregar Ítem al pedido Nº {{ $order->id }}</p></h5>
                                        @if ($order->open_contract == 1)
                                            <h5 style="color:blue;background-color:yellow;font-weight: bold">   -   Adjuntar Archivo Excel de Contrato Abierto</h5>                                            
                                        @else
                                            @if ($order->open_contract == 2)
                                                <h5 style="color:blue;background-color:yellow;font-weight: bold">   -   Adjuntar Archivo Excel de Contrato Cerrado</h5>                                        
                                            @else
                                                <h5 style="color:blue;background-color:yellow;font-weight: bold">   -   Adjuntar Archivo Excel de Contrato Abierto Mmin Mmáx</h5>
                                            @endif        
                                        @endif 
                                </div>
                                <div class="card-block">
                                        {{-- Elejimos el POST de acuerdo al tipo de Contrato --}}
                                        @if ($order->open_contract == 1)
                                            {{-- CONTRATO ABIERTO --}}
                                            <form method="POST" action="{{ route('orders.items.storeExcel', $order->id) }}" enctype="multipart/form-data">
                                        @else
                                            {{-- CONTRATO CERRADO --}}
                                            @if ($order->open_contract == 2)
                                                <form method="POST" action="{{ route('orders.items.storeExcel2', $order->id) }}" enctype="multipart/form-data">
                                            @else
                                                {{-- CONTRATO MIN MAX --}}    
                                                <form method="POST" action="{{ route('orders.items.storeExcel3', $order->id) }}" enctype="multipart/form-data">
                                            @endif        
                                        @endif

                                        @csrf

                                        <div class="form-group row @error('excel') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Cargar archivo Excel</label>
                                            <div class="col-sm-10">
                                                <input id="excel" type="file" class="form-control" name="excel">
                                                @error('excel')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        @if ($errors->any())
                                            <h4>Se encontraron errores al validar la fila {{ session('fila') }} del archivo</h4>
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="form-group row">
                                            <label class="col-sm-2"></label>
                                            <div class="col-sm-10">
                                                <button type="submit" class="btn btn-primary m-b-0">Importar</button>
                                            </div>
                                        </div>
                                    </form>
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