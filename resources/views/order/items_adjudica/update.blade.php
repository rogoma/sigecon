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
                        <span>Modificar Ítem</span>
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
                                    <h5>Modificar Ítem al pedido Nº {{ $order->id }}</h5>
                                        @if ($order->open_contract == 1)
                                            <h5><a style="font-size: 15px; font-weight: bold; color:red"> Tipo Contrato: Abierto</a></h5>
                                        @else
                                            @if ($order->open_contract == 2)
                                                <h5><a style="font-size: 15px; font-weight: bold; color:red"> Tipo Contrato: Cerrado</a></h5>
                                            @else
                                                <h5><a style="font-size: 15px; font-weight: bold; color:red"> Tipo Contrato: Abierto con MontoMin y MontoMáx</a></h5>
                                            @endif        
                                        @endif
                                        <br>
                                        @if ($order->covid==0)                                                
                                                <h5>{{ is_null($order->number)? $order->description : $order->modality->description." N° ".$order->number."/".$order->year."-".$order->description }}   
                                            @else                                                
                                                <h5>{{ is_null($order->number)? $order->description : $order->modality->description." N° ".$order->number."/".$order->year."-".$order->description }}    
                                                <label style="font-size: 16px; font-weight: bold; color:blue;background-color:yellow;">Proceso COVID</label></h5>
                                            @endif
                                </div>
                                <div class="card-block">
                                        <form method="POST" action="{{ route('orders.items_adjudica.update', [$order->id, $item->id]) }}">
                                            
                                        @csrf
                                        @method('PUT')

                                        {{-- CÓDIGO PARA MOSTRAR ERRORES --}}
                                        <div class="col-sm-12">
                                            @if ($errors->any())
                                           <div class="alert alert-danger">
                                           <ul>
                                               @foreach ($errors->all() as $error)
                                                 <li>{{ $error }}</li>
                                               @endforeach
                                           </ul>
                                           </div>
                                           @endif
                                        </div>

                                        <div class="form-group row @error('batch') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Lote</label>
                                            <div class="col-sm-4">
                                                <input type="number" id="batch" name="batch" value="{{ old('batch', $item->batch) }}" class="form-control @error('batch') form-control-danger @enderror" readonly>
                                                @error('batch')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        
                                        <div class="form-group row @error('item_number') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Ítem</label>
                                            <div class="col-sm-4">
                                                    {{-- Si usuario es de Plannings se muestra sólo lectura     --}}
                                                    @if ((Auth::user()->dependency->id == 64))
                                                        <input type="number" id="item_number" name="item_number" value="{{ old('item_number', $item->item_number)}}" class="form-control @error('item_number') form-control-danger @enderror" readonly>
                                                    @else
                                                        <input type="number" id="item_number" name="item_number" value="{{ old('item_number', $item->item_number)}}" class="form-control @error('item_number') form-control-danger @enderror">
                                                    @endif
                                                @error('item_number')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('level5_catalog_code_id') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Código de Catálogo Nivel 5</label>
                                            <div class="col-sm-4">
                                                <input type="text" id="level5_catalog_code" name="level5_catalog_code" value="{{ old('level5_catalog_code', $item->level5CatalogCode->code) }}" class="form-control @error('level5_catalog_code_id') form-control-danger @enderror" readonly>
                                                <h5><a style="font-size: 18px; font-weight: bold; color:red" id="level5_catalog_description">{{ old('level5_catalog_description', $item->level5CatalogCode->description) }}</a></h5>

                                                <input type="hidden" id="level5_catalog_code_id" name="level5_catalog_code_id" value="{{ old('level5_catalog_code_id', $item->level5_catalog_code_id) }}">
                                                <input type="hidden" name="level5_catalog_description" value={{ old('level5_catalog_description', $item->level5CatalogCode->description) }}>
                                            </div>

                                            {{-- <div class="col-sm-4">
                                                <button id="search_level5" type="button" class="btn btn-info">Buscar coincidencias</button>
                                            </div>
                                            @error('level5_catalog_code_id')
                                                <div class="col-form-label">{{ $message }}</div>
                                            @enderror
                                            <div id="codes_result" class="offset-sm-2 col-sm-8"></div> --}}
                                            
                                        </div>
                                        
                                        <div class="form-group row @error('order_presentation_id') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Presentación</label>
                                            <div class="col-sm-4">
                                                <select id="order_presentation_id" name="order_presentation_id" class="form-control">
                                                    <option value="">--- Seleccionar Presentación ---</option>
                                                    @foreach ($order_presentations as $order_presentation)
                                                        {{-- Si usuario es de Adjudica se muestra sólo lectura     --}}
                                                        {{-- @if ((Auth::user()->dependency->id == 64)) --}}
                                                            <option value="{{ $order_presentation->id }}" @if ($order_presentation->id == old('order_presentation_id', $item->order_presentation_id)) selected @endif>{{ $order_presentation->description }} </option>
                                                        {{-- @else --}}
                                                            {{-- <option value="{{ $order_presentation->id }}" @if ($order_presentation->id == old('order_presentation_id', $item->order_presentation_id)) selected @endif>{{ $order_presentation->description }} </option> --}}
                                                        {{-- @endif                                            --}}
                                            
                                                    @endforeach
                                                </select>
                                                @error('order_presentation_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('order_measurement_unit_id') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Unidad de Medida</label>
                                            <div class="col-sm-4">
                                                <select id="order_measurement_unit_id" name="order_measurement_unit_id" class="form-control">
                                                    <option value="">--- Seleccionar Unidad de Medida ---</option>
                                                    @foreach ($order_measurement_units as $order_measurement_unit)
                                                        {{-- Si usuario es de Adjudica se muestra sólo lectura     --}}
                                                        {{-- @if ((Auth::user()->dependency->id == 64)) --}}            
                                                            <option value="{{ $order_measurement_unit->id }}" @if ($order_measurement_unit->id == old('order_measurement_unit_id', $item->order_measurement_unit_id)) selected @endif>{{ $order_measurement_unit->description }}</option>
                                                        {{-- @else --}}
                                                            {{-- <option value="{{ $order_measurement_unit->id }}" @if ($order_measurement_unit->id == old('order_measurement_unit_id', $item->order_measurement_unit_id)) selected @endif>{{ $order_measurement_unit->description }}</option> --}} --}}
                                                        {{-- @endif --}}                                                        
                                                    @endforeach
                                                </select>
                                                @error('order_measurement_unit_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        

                                        <div class="form-group row @error('trademark') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Marca</label>
                                            <div class="col-sm-4">                                                    
                                                    <input type="text" id="trademark" name="trademark" value="{{ old('trademark', $item->trademark)}}" class="form-control @error('trademark') form-control-danger @enderror">                                                    
                                                @error('trademark')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('origin') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Procedencia</label>
                                            <div class="col-sm-4">                                                    
                                                    <input type="text" id="origin" name="origin" value="{{ old('origin', $item->origin)}}" class="form-control @error('origin') form-control-danger @enderror">                                                    
                                                @error('origin')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row @error('maker') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Fabricante</label>
                                            <div class="col-sm-4">                                                    
                                                    <input type="text" id="maker" name="maker" value="{{ old('maker', $item->maker)}}" class="form-control @error('maker') form-control-danger @enderror">                                                    
                                                @error('maker')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="form-group row @error('quantity') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Cantidad</label>
                                                <div class="col-sm-4">
                                                    <input type="text" id="quantity" name="quantity" value="{{ old('quantity', $item->quantity) }}" class="form-control quantity autonumber" data-a-sep="." data-a-dec=",">
                                                    {{-- <input type="text" id="total_amount" name="total_amount" value="{{ old('total_amount', $order->total_amount) }}" class="form-control total_amount autonumber" data-a-sep="." data-a-dec=",">                                                 --}}
                                                    @error('quantity')
                                                        <div class="col-form-label">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                        </div>

                                        <div class="form-group row @error('unit_price') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Precio Unitario</label>
                                                <div class="col-sm-4">
                                                    <input type="text" id="unit_price" name="unit_price" value="{{ old('unit_price', $item->unit_price) }}" class="form-control unit_price autonumber" data-a-sep="." data-a-dec=",">
                                                    {{-- <input type="text" id="total_amount" name="total_amount" value="{{ old('total_amount', $order->total_amount) }}" class="form-control total_amount autonumber" data-a-sep="." data-a-dec=",">                                                 --}}
                                                    @error('unit_price')
                                                        <div class="col-form-label">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Monto Total</label>
                                            <div class="col-sm-4">
                                            {{-- <input type="number" id="total_amount" name="total_amount" value="{{ $item->total_amount }}" class="form-control" readonly> --}}
                                            <input type="text" id="total_amount" name="total_amount" value="{{ $item->total_amount }}" class="form-control total_amount autonumber" data-a-sep="." data-a-dec="," readonly>                                             
                                            </div>
                                        </div>  

                                        <div class="form-group row @error('provider_id') has-danger @enderror">
                                            <label class="col-sm-2 col-form-label">Empresa</label>
                                            <div class="col-sm-4">
                                                <select id="provider_id" name="provider_id" class="form-control">
                                                    {{-- <option value="">--- Seleccionar Empresa ---</option> --}}
                                                    @foreach ($providers as $provider)
                                                        <option value="{{ $provider->id}}" @if ($provider->id == old('providers', $item->provider_id)) selected @endif>{{ $provider->description}}</option>
                                                        {{-- <option value="{{ $order_presentation->id }}" @if ($order_presentation->id == old('order_presentation_id', $item->order_presentation_id)) selected @endif>{{ $order_presentation->description }} </option> --}}
                                                    @endforeach
                                                </select>
                                                @error('providers')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>                                        

                                        <div class="form-group row">
                                            <label class="col-sm-2"></label>
                                            <div class="col-sm-10">
                                                <button type="submit" class="btn btn-warning m-b-0">Modificar Item</button>
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

const formatNumber = (
  number,
  minimumFractionDigits = 0,
  maximumFractionDigits = 2
) =>
  new Intl.NumberFormat('es-ES', {
    minimumFractionDigits,
    maximumFractionDigits
  }).format(number);


$(document).ready(function(){

    //MODIFICA TIPO DE COMBO
    $('#order_presentation_id').select2();
    $('#order_measurement_unit_id').select2();
    $('#provider_id').select2();
    

    //Original-PARA TODO TIPO DE CONTRATO
    $('#quantity').keyup(function(){
        // console.log($(this).val());

        if( $(this).val() != "" && $('#unit_price').val() != "" ){
            var quantity = Number($(this).val().replaceAll(".", ""));
            var unitPrice = Number($('#unit_price').val().replaceAll(".", ""));
            var totalAmount = (quantity * unitPrice);
            $('#total_amount').val(''+ formatNumber(totalAmount));
        }else{
            $('#total_amount').val('');
        }
        
    });

    $('#unit_price').keyup(function(){
        if( $(this).val() != "" && $('#quantity').val() != "" ){
            var unitPrice = Number($(this).val().replaceAll(".", ""));
            var quantity = Number($('#quantity').val().replaceAll(".", ""));
            var totalAmount = (unitPrice * quantity);            
            $('#total_amount').val(''+ formatNumber(totalAmount));         
        }else{
            $('#total_amount').val('');
        }
    });
    

    // $('#min_quantity').keyup(function(){
    //     if( $(this).val() != "" && $('#unit_price').val() != "" ){
    //         $('#total_amount_min').val( $(this).val() * $('#unit_price').val() );
    //     }else{
    //         $('#total_amount_min').val('');
    //     }
        
    //     // Verificamos que pedido mínimo no sea mayor a pedido máximo
    //     if( parseInt($(this).val()) > parseInt($('#max_quantity').val())){           
    //         $(this).val('');
    //         $('#total_amount_min').val('');

    //         swal({
    //         title: "Atención",
    //         text: "Pedido Mínimo no puede ser mayor a Pedido Máximo",
    //         type: "warning",            
    //         confirmButtonColor: "#DD6B55",            
    //         });            
    //     }
    // });

    // $('#max_quantity').keyup(function(){
    //     if( $(this).val() != "" && $('#unit_price').val() != "" ){
    //         $('#total_amount').val( $(this).val() * $('#unit_price').val() );
    //     }else{
    //         $('#total_amount').val('');
    //     }
    // });
    
    // $('#unit_price').keyup(function(){
    //     if( $(this).val() != "" && $('#quantity').val() != "" ){
    //         $('#total_amount_min').val( $(this).val() * $('#min_quantity').val() );
    //         $('#total_amount').val( $(this).val() * $('#max_quantity').val() );
    //     }else{
    //         $('#total_amount_min').val('');
    //         $('#total_amount').val('');
    //     }
    // });

    //Original-PARA TIPO CONTRATO 3 ABIERTO MM
    // $('#quantity3').keyup(function(){
    //     if( $(this).val() != "" && $('#unit_price3').val() != "" ){
    //         $('#total_amount3').val( $(this).val() * $('#unit_price3').val() );
    //     }else{
    //         $('#total_amount3').val('');
    //     }
        
    //     if( parseInt($('#total_amount_min3').val()) > parseInt($('#total_amount3').val())){           
    //         $(this).val('');
    //         $('#total_amount_min3').val('');

    //         swal({
    //         title: "Atención",
    //         text: "Monto Mínimo no puede ser mayor a Monto Máximo",
    //         type: "warning",            
    //         confirmButtonColor: "#DD6B55",            
    //         });            
    //     }
    // });
    // $('#unit_price3').keyup(function(){
    //     if( $(this).val() != "" && $('#quantity3').val() != "" ){
    //         $('#total_amount3').val( $(this).val() * $('#quantity3').val() );
    //     }else{
    //         $('#total_amount3').val('');
    //     }

    //     if( parseInt($('#total_amount_min3').val()) > parseInt($('#total_amount3').val())){           
    //         $(this).val('');
    //         $('#total_amount_min3').val('');

    //         swal({
    //         title: "Atención",
    //         text: "Monto Mínimo no puede ser mayor a Monto Máximo",
    //         type: "warning",            
    //         confirmButtonColor: "#DD6B55",            
    //         });            
    //     }
    // });


    // $('#total_amount_min3').keyup(function(){
    //     // if( $(this).val() != "" && $('#unit_price').val() != "" ){
    //     //     $('#total_amount_min').val( $(this).val() * $('#unit_price').val() );
    //     // }else{
    //     //     $('#total_amount_min').val('');
    //     // }
        
    //     // Verificamos que pedido mínimo no sea mayor a pedido máximo
    //     if( parseInt($(this).val()) > parseInt($('#total_amount3').val())){           
    //         $(this).val('');
    //         $('#total_amount_min3').val('');

    //         swal({
    //         title: "Atención",
    //         text: "Monto Mínimo no puede ser mayor a Monto Máximo",
    //         type: "warning",            
    //         confirmButtonColor: "#DD6B55",            
    //         });            
    //     }
    // });



    $('#search_level5').click(function(){
        $('#search_level5').attr("disabled", true);
        $.ajax({
            url : '/items/search',
            method : 'GET',
            data: { search: $('#level5_catalog_code').val(),  _token: '{{ csrf_token() }}'},
            success: function(data){
                try{
                    $('#search_level5').removeAttr("disabled");
                    let table_start = '<table class="table table-striped table-bordered table-sm"><tbody>'
                    let thead = '<thead><th>Código</th><th>Descripción</th><th>Acción</th></thead>'
                    let rows = data.map(row => '<tr><td>'+row.code+'</td>'+'<td>'+row.description+'</td>'+'<td><button type="button" onclick="seleccionar(\''+row.id+'\',\''+row.code+'\',\''+row.description+'\');" class="btn btn-primary">Seleccionar</button></td></tr>' );
                    let table_end = '</tbody></table>'
                    $('#codes_result').html(table_start + thead + rows.join('') + table_end);
                }catch(error){
                    swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                    console.log(error);
                }
            },
            error: function(error){
                swal("Error!", "Ocurrió un error intentado resolver la solicitud, por favor complete todos los campos o recargue de vuelta la pagina", "error");
                $('#search_level5').removeAttr("disabled");
                console.log(error);
            }
        });
    });

    seleccionar = function(id, code, description){
        $('#level5_catalog_code_id').val(id);
        $('#level5_catalog_code').val(code);
        $('#level5_catalog_description').html('Ìtem seleccionado: ' + description);
        $('input[name=level5_catalog_description]').val('Ìtem seleccionado: ' + description);
        $('#codes_result').html('');
    }

    $('#level5_catalog_code').keyup(function(){
        $('#level5_catalog_code_id').val('');
        $('#level5_catalog_description').html('');
        $('input[name=level5_catalog_description]').val('');
    });        

});
</script>
<script src="{{ asset('formatNumber.js') }}" type="text/javascript"></script>
@endpush