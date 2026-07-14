@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-sitemap bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Eventos</h5>
                        <span>Modificar Eventos</span>
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
                            {{-- <a href="{{ route('items.item_award_histories.index', $order->id) }}">Listado de Eventos de la Orden {{ $order->iddncp }}</a> --}}
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
                                    <h4>Contratista: {{$contract->provider->description }} - Localidad: {{ $order->locality }} 
                                    {{-- - SubComponente: {{ $order->component_id->components->description }} --}}
                                    {{-- - Componente: {{ $order->component_code->component->description }} --}}
                                    - Orden N°: {{ $order->component_code }} - {{ $order->number }} </h4>
                                    
                                    <label id="fecha_actual" name="fecha_actual"  style="font-size: 20px;color: #FF0000;float: left;" for="fecha_actual">{{ Carbon\Carbon::now()->format('d/m/Y') }}</label>
                                    {{-- <label style="font-size: 20px;color: #FF0000;float: left;">FECHA: </label> --}}
                                </div>
                                <div class="card-block">
                                    <form method="POST" action="{{ route('orders.events.store', $order->id)}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="container">
                                            {{-- se captura en modo hidden el monto para pasar al controlador, se debe controlar monto poliza vs monto endoso --}}
                                            {{-- <input type="hidden" id="tot" name="tot" value="{{ $order->event_days }}"> --}}

                                            <h3 style="text-align: center;">Modificar Evento</h3>
                                            <br>
                                            @php
                                                use Carbon\Carbon;

                                                $fechaInicio = Carbon::parse($order->sign_date);
                                                $fechaFin = $fechaInicio->copy()->addDays($order->plazo);
                                                $fechaFinal = $fechaInicio->addDays($order->plazo);
                                            @endphp
                                            
                                            @if(isset($events) && $events->isNotEmpty())
                                                @foreach ($events as $event)
                                                    @php
                                                        $fechaFinal = $fechaFin->copy()->addDays($event->event_days);
                                                    @endphp
                                                @endforeach
                                                <h4>Fecha Inicio: {{ \Carbon\Carbon::parse($order->sign_date)->format('d/m/Y') }}  *****  Plazo días: {{$order->plazo}}
                                                    ***** Fecha Fin: {{ $event->event_date_fin ? \Carbon\Carbon::parse($event->event_date_fin)->format('d/m/Y') : 'No definida' }}</h4>
                                            
                                            @else
                                                <h4>Fecha Inicio: {{ \Carbon\Carbon::parse($order->sign_date)->format('d/m/Y') }}  *****  Plazo días: {{$order->plazo}}
                                                    ***** Fecha Fin: {{ \Carbon\Carbon::parse($order->sign_date)->addDays($order->plazo)->format('d/m/Y') }} </h4>
                                            @endif
                                            
                                            
                                            

                                            <br>
                                            <div class="form-group row @error('event_type_id') has-danger @enderror">
                                                <label class="col-sm-2 col-form-label">Tipo de Evento</label>
                                                    <div class="col-sm-8">
                                                    <select id="event_type_id" name="event_type_id" class="form-control" onclick="checkAwardType()">
                                                            <option value="">Seleccionar Tipo de Evento</option>

                                                        @foreach ($event_types as $event_type)
                                                            <option value="{{ $event_type->id }}" @if ($event_type->id == old('event_type_id')) selected @endif>{{$event_type->description }}</option>
                                                        @endforeach
                                                        
                                                        </select>
                                                        @error('event_type_id')
                                                            <div class="col-form-label">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                            </div>                                           

                                            <div class="form-group row @error('event_date') has-danger @enderror">
                                                <label class="col-sm-2 col-form-label">Fecha Evento</label>
                                                <div class="col-md-3">
                                                        {{-- <label class="col-form-label @error('event_date') has-danger @enderror">Fecha Evento</label> --}}
                                                        <div class="input-group @error('event_date') has-danger @enderror">
                                                            <input type="text" id="event_date" name="event_date" value="{{ old('event_date') }}" class="form-control text-align: left" autocomplete="off">
                                                            <span class="input-group-append" id="basic-addon">
                                                                <label class="input-group-text" onclick="show('event_date');"><i class="fa fa-calendar"></i></label>
                                                            </span>
                                                        </div>
                                                        @error('event_date')
                                                        <div class="has-danger">
                                                            <div class="col-form-label">{{ $message }}</div>
                                                        </div>
                                                        @enderror
                                                </div>
                                            </div>    
                                           

                                            <div class="form-group row @error('event_days') has-danger @enderror">
                                                <label class="col-sm-2 col-form-label">Días Prórroga</label>
                                                <div class="col-sm-2">
                                                    <input type="text" id="event_days" name="event_days" value="{{ old('event_days') }}" class="form-control @error('event_days') form-control-danger @enderror" maxlength="2">
                                                    @error('event_days')
                                                        <div class="col-form-label">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row @error('comments') has-danger @enderror">
                                                <label for="comments" class="col-form-label">Comentarios (Hasta 500 caracteres)</label>
                                                <textarea id="comments" name="comments" class="form-control @error('comments') is-invalid @enderror"
                                                    maxlength="500">{{ old('comments')}}</textarea>
                                                @error('comments')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>                                           

                                            <br>
                                            <div class="form-group row @error('file') has-danger @enderror">
                                                {{-- <h3 style="text-align: center;">Agregar Póliza</h3> --}}
                                                <label class="col-form-label">Cargar Archivo: <h7>(Tipo de archivos permitidos: WORD, PDF)</h7></label>
                                                <input id="file" type="file" class="form-control" name="file">
                                                @error('file')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <br>
                                            <div class="form-group text-center">
                                                <button id="guardar" type="submit" class="btn btn-primary m-b-0">Guardar</button>
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

@push('scripts')
<script type="text/javascript">
$(document).ready(function(){

    $('#event_date').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        endDate: "today" // Restringe la selección de fechas futuras
    });

    $('#event_type_id').select2();


    

});
</script>
@endpush