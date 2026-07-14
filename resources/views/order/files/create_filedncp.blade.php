@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Pedidos</h5>
                        <span>Archivos</span>
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
                                    <h5>Cargar Archivo</h5>
                                </div>
                                <div class="col-sm-8 text-left">                                    
                                    <h5>{{ is_null($order->number)? $order->description : $order->modality->description." N° ".$order->number."-".$order->description }}<label class="label label-info m-l-5">Prioridad {{ $order->urgency_state }}</label></h5>
                                    <h5>SIMESE: {{ number_format($order->simese->first()['simese'],'0', ',','.') }} </h5>
                                </div>
                                <div class="card-block">
                                    <form method="POST" action="{{ route('orders.files.store_filedncp', $order->id) }}" enctype="multipart/form-data">
                                    @csrf
                                        
                                        <div class="form-group @error('description') has-danger @enderror">
                                            <label class="col-form-label">Descripción</label>
                                            <input type="text" id="description" name="description" value="{{ old('description') }}" class="form-control">
                                            @error('description')
                                                <div class="col-form-label">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('file') has-danger @enderror">
                                            <label class="col-form-label">Cargar archivo <small>(Archivos permitidos: WORD, PDF, EXCEL)</small></label>
                                            <input id="file" type="file" class="form-control" name="file">
                                            @error('file')
                                                <div class="col-form-label">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 text-center">
                                            <button id="guardar" type="submit" class="btn btn-primary m-b-0">Guardar</button>
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

@push('scripts')
<script type="text/javascript">
$(document).ready(function(){

    $('#file').bind('change', function() {
        max_upload_size = {{ $post_max_size }};
        if(this.files[0].size > max_upload_size){
            $('#guardar').attr("disabled", "disabled");
            file_size = Math.ceil((this.files[0].size/1024)/1024);
            max_allowed = Math.ceil((max_upload_size/1024)/1024);
            swal("Error!", "El tamaño del archivo seleccionado ("+file_size+" Mb) supera el tamaño maximo de carga permitido ("+max_allowed+" Mb).", "error");
        }else{
            $('#guardar').removeAttr("disabled");
        }
    });

});
</script>
@endpush