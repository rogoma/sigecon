@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Rubros</h5>
                        <span>Importar Rubros</span>
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
                            <a href="{{ route('contracts.show', $order->contract_id) }}">Contrato Nº {{ $order->contract->number_year }}</a>                            
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
                                    {{-- <h5>Agregar Rubros al pedido Nº {{ $order->id }}</h5> --}}
                                    <h5><p style="font-size: 17px; font-weight: bold; color:blue">Agregar Rubros a la orden Nº {{ $order->id }}</p></h5>
                                    <br>
                                    <h5><p style="font-size: 17px; font-weight: bold; color:#FF0000">Componente: {{ $order->component->code }} - {{ $order->component->description }}</p></h5>
                                    <h5><p style="font-size: 17px; font-weight: bold; color:#FF0000">Localidad: {{ $order->locality }}</p></h5>
                                </div>
                                <div class="card-block">
                                        <form method="POST" action="{{ route('orders.items.storeExcel', $order->id) }}" enctype="multipart/form-data">
                                        
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