@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-file-text bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Pedidos</h5>
                        <span>Editar Pedido {{$order->id }}</span>
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
                            <a href="{{ route('orders.index') }}">Pedidos</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <br>
        {{-- <div class="card-header">
            <h5>Editar Pedido</h5>
        </div> --}}
        <div class="row">
        {{-- <div class="col-sm-10 text-left"> --}}
            {{-- <h5>{{ is_null($order->number)? $order->description : $order->modality->description." N° ".$order->number."-".$order->description }} --}}
            @if ($order->covid==0)
                <h5>{{ is_null($order->number)? $order->description : $order->modality->description." N° ".$order->number."/".$order->year."-".$order->description }}
                <h5><a style="font-size: 17px; font-weight: bold; color:red"> SIMESE:</a> <a style="font-size: 17px; font-weight: bold; color:blue;background-color:yellow;"> {{ is_null($order->simese->first()) ? 'Para derivar el pedido debe cargarse Ítems y N°SIMESE ' : number_format($order->simese->first()['simese'],'0', ',','.')."/".$order->simese->first()['year'] }}</a></h5>
            @else
                <h5>{{ is_null($order->number)? $order->description : $order->modality->description." N° ".$order->number."/".$order->year."-".$order->description }}
                <label style="font-size: 16px; font-weight: bold; color:blue;background-color:yellow;">Proceso COVID</label></h5>
                <h5><a style="font-size: 17px; font-weight: bold; color:red"> SIMESE:</a> <a style="font-size: 17px; font-weight: bold; color:blue;background-color:yellow;"> {{ is_null($order->simese->first()) ? 'Para derivar el pedido debe cargarse Ítems y N°SIMESE ' : number_format($order->simese->first()['simese'],'0', ',','.')."/".$order->simese->first()['year'] }}</a></h5>
            @endif

            @if ($order->urgency_state == "ALTA")
                <label class="label label-danger m-l-5">Prioridad {{ $order->urgency_state }}</label></h5>
            @else
                @if ($order->urgency_state == "MEDIA")
                    <label class="label label-warning m-l-5">Prioridad {{ $order->urgency_state }}</label></h5>
                @else
                    <label class="label label-info m-l-5">Prioridad {{ $order->urgency_state }}</label></h5>
                @endif
            @endif


            @if ($order->open_contract == 1)
                <h5><a style="font-size: 15px; font-weight: bold; color:red"> Tipo Contrato: Abierto</a></h5>
            @else
                @if ($order->open_contract == 2)
                    <h5><a style="font-size: 15px; font-weight: bold; color:red"> Tipo Contrato: Cerrado</a></h5>
                @else
                    <h5><a style="font-size: 15px; font-weight: bold; color:red"> Tipo Contrato: Abierto con MontoMin y MontoMáx</a></h5>
                @endif
            @endif
        {{-- </div> --}}
       </div>
    </div>

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                {{-- <div class="card-header">
                                    <h5>Editar Pedido</h5>
                                </div> --}}
                                <div class="card-block">
                                    <h6 class="text-center">Datos de cabecera para los formularios</h6>
                                    <form class="row" method="POST" action="{{ route('orders.update', $order->id) }}">
                                        @csrf
                                        @method('PUT')

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

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="col-form-label">Dependencia <br><small>(Dependencia solicitante)</small></label>
                                                <textarea class="form-control" readonly>{{ Auth::user()->dependency->description }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('responsible') has-danger @enderror">
                                                <label class="col-form-label">Responsable <br><small>(Responsable de la dependencia)</small></label>
                                                <input type="text" id="responsible" name="responsible" value="{{ old('responsible', $order->responsible) }}" class="form-control">
                                                @error('responsible')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('modality') has-danger @enderror">
                                                <label class="col-form-label">Modalidad <br><small>(Modalidad del llamado)</small></label>
                                                <select id="modality" name="modality" class="form-control"">
                                                    <option value="">Seleccionar</option>
                                                @foreach ($modalities as $modality)
                                                    @if ($order->modality->id == 28)
                                                        <option value="{{ $modality->id }}" @if ($modality->id == old('modality', $order->modality_id)) selected @endif>{{ $modality->description }}</option>
                                                    @else
                                                        <option value="{{ $modality->id }}" @if ($modality->id == old('modality', $order->modality_id)) selected @endif>{{ $modality->description }}</option>
                                                    @endif
                                                @endforeach
                                                </select>
                                                @error('modality')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('dncp_pac_id') has-danger @enderror">
                                                <label class="col-form-label">PAC ID <br><small>(ID del PAC)</small></label>
                                                <input type="number" id="dncp_pac_id" name="dncp_pac_id" value="{{ old('dncp_pac_id', $order->dncp_pac_id) }}" class="form-control">
                                                @error('dncp_pac_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('year') has-danger @enderror">
                                                <label class="col-form-label">AÑO <br><small>(AÑO)</small></label>
                                                <input type="number" id="year" name="year" value="{{ old('year', $order->year) }}" class="form-control">
                                                @error('year')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <label class="col-form-label @error('begin_date') has-danger @enderror">Fecha de Inicio <br><small>(Fecha estimada de inicio)</small></label>
                                            <div class="input-group @error('begin_date') has-danger @enderror">
                                                <input type="text" id="begin_date" name="begin_date" value="{{ old('begin_date', empty($order->begin_date) ? '' : date('d/m/Y', strtotime($order->begin_date))) }}" class="form-control" autocomplete="off">
                                                <span class="input-group-append" id="basic-addon">
                                                    <label class="input-group-text" onclick="show('begin_date');"><i class="fa fa-calendar"></i></label>
                                                </span>
                                            </div>
                                            @error('begin_date')
                                            <div class="has-danger">
                                                <div class="col-form-label">{{ $message }}</div>
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group @error('sub_program') has-danger @enderror">
                                                <label class="col-form-label">Línea <br><small>(Estructura presupuestaria)</small></label>
                                                <select id="sub_program" name="sub_program" class="form-control"">
                                                    <option value="">Seleccionar</option>
                                                @foreach ($sub_programs as $sub_program)
                                                    <option value="{{ $sub_program->id }}" @if ($sub_program->id == old('sub_program', $order->sub_program_id)) selected @endif>{{ $sub_program->budgetStructure() }}</option>
                                                @endforeach
                                                </select>
                                                @error('sub_program')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group @error('funding_source') has-danger @enderror">
                                                <label class="col-form-label">F.F.  <br><small>(Indicar Fuente de financiamiento)</small></label>
                                                <select id="funding_source" name="funding_source" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach ($funding_sources as $funding_source)
                                                    <option value="{{ $funding_source->id }}" @if ($funding_source->id == old('funding_source', $order->funding_source_id)) selected @endif>{{ $funding_source->code }}</option>
                                                @endforeach
                                                </select>
                                                @error('funding_source')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group @error('financial_organism') has-danger @enderror">
                                                <label class="col-form-label">O.F.  <br><small>(Organismo Financiador)</small></label>
                                                <select id="financial_organism" name="financial_organism" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach ($financial_organisms as $financial_organism)
                                                    <option value="{{ $financial_organism->id }}" @if ($financial_organism->id == old('financial_organism', $order->financial_organism_id)) selected @endif>{{ $financial_organism->code.' - '.$financial_organism->description }}</option>
                                                @endforeach
                                                </select>
                                                @error('financial_organism')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group @error('covid') has-danger @enderror">
                                                <label class="col-form-label">COVID <br><small>(Covid)</small></label>
                                                <select id="covid" name="covid" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach (array(0 => 'NO', 1 => 'SI') as $index => $value)
                                                    <option value="{{ $index }}" @if ($index == old('covid', $order->covid)) selected @endif>{{ $value }}</option>
                                                @endforeach
                                                </select>
                                                @error('covid')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group @error('system_awarded_by') has-danger @enderror">
                                                @php
                                                    $system_awarded_by = array('LOTE' => 'LOTE', 'ÍTEM' => 'ÍTEM', 'TOTAL' => 'TOTAL', 'COMBINADO' => 'COMBINADO');
                                                @endphp
                                                <label class="col-form-label">Sistema de Adjudicación por <br><small>(Ítem, etc.)</small></label>
                                                <select id="system_awarded_by" name="system_awarded_by" class="form-control">
                                                @foreach ($system_awarded_by as $index => $value)
                                                    <option value="{{ $index }}" @if ($index == old('system_awarded_by', $order->system_awarded_by)) selected @endif>{{ $value }}</option>
                                                @endforeach
                                                </select>
                                                @error('system_awarded_by')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group @error('total_amount') has-danger @enderror">
                                                <label class="col-form-label">Monto Total <br><small>(Monto total)</small></label>
                                                <input type="text" id="total_amount" name="total_amount" value="{{ old('total_amount', $order->total_amount) }}" class="form-control total_amount autonumber" data-a-sep="." data-a-dec="," readonly>
                                                {{-- <input type="text  " id="total_amount" name="total_amount" value="{{ $order->total_amount}}" class="form-control total_amount autonumber" data-a-sep="." data-a-dec="," readonly> --}}
                                                @error('total_amount')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('description') has-danger @enderror">
                                                <label class="col-form-label">Descripción <br><small>(Descripción del Pedido)</small></label>
                                                <textarea rows="2" id="description" name="description" class="form-control">{{ old('description', $order->description) }}</textarea>
                                                @error('description')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group @error('ad_referendum') has-danger @enderror">
                                                <label class="col-form-label">Ad-Referendum <br><small>(Ad-Referendum)</small></label>
                                                <select id="ad_referendum" name="ad_referendum" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach (array(0 => 'NO', 1 => 'SI') as $index => $value)
                                                    <option value="{{ $index }}" @if ($index == old('ad_referendum', $order->ad_referendum)) selected @endif>{{ $value }}</option>
                                                @endforeach
                                                </select>
                                                @error('ad_referendum')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- SI PEDIDO TIENE ITEMS PUEDE REALIZAR PLURIANUALIDAD --}}
                                        @if ($order->items->count() > 0 )
                                            <div class="col-sm-2">
                                                <div class="form-group @error('plurianualidad') has-danger @enderror">
                                                    <label style="color:red;font-weight: bold" class="col-form-label">Plurianualidad <br><small>(plurianualidad)</small></label>
                                                    <select id="plurianualidad" name="plurianualidad" class="form-control">
                                                        <option value="">Seleccionar</option>
                                                    @foreach (array(0 => 'NO', 1 => 'SI') as $index => $value)
                                                        <option value="{{ $index }}" @if ($index == old('plurianualidad', $order->plurianualidad)) selected @endif>{{ $value }}</option>
                                                    @endforeach
                                                    </select>
                                                    @error('plurianualidad')
                                                        <div class="col-form-label">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-sm-2">
                                                <div class="form-group @error('plurianualidad') has-danger @enderror">
                                                    <label style="color:red;font-weight: bold" class="col-form-label">Plurianualidad <br><small>(plurianualidad)</small></label>
                                                    <select id="plurianualidad" name="plurianualidad" class="form-control">
                                                        {{-- <option value="">Seleccionar</option> --}}
                                                    @foreach (array(0 => 'NO', 1 => 'SI') as $index => $value)
                                                        <option disabled="disabled" value="{{ $index }}" @if ($index == old('plurianualidad', $order->plurianualidad)) selected @endif>{{ $value }}</option>
                                                    @endforeach
                                                    </select>
                                                    @error('plurianualidad')
                                                        <div class="col-form-label">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endif

                                        <div id="multi_year_template" class="col-sm-12 d-none">
                                            <div class="row">
                                                <div class="col-sm-4 offset-sm-3">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Año</label>
                                                        <select id="multi_year_year" class="form-control">
                                                            @for ($i = date('Y'); $i < date('Y') + 10; $i++)
                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-offset-4">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Monto</label>
                                                        <input type="number" id="multi_year_amount" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-sm-1">
                                                    <button type="button" title="Borrar Fila" onclick="delRow(this);" class="btn btn-sm btn-danger m-t-30">
                                                        <span class="f-20">-</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        @if (old('plurianualidad', $order->plurianualidad) == true)
                                            <div id="multi_years" class="w-100 @error('multi_year') has-danger @enderror">
                                            @php
                                                $years = old('multi_year_year', $order->orderMultiYears->pluck('year'));
                                                $amounts = old('multi_year_amount', $order->orderMultiYears->pluck('amount'));
                                            @endphp
                                            @for ($x = 0; $x < count($years); $x++)
                                                <div id="multi_year_template" class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-4 offset-sm-3">
                                                            <div class="form-group">
                                                                <label class="col-form-label">Año</label>
                                                                <select id="multi_year_year" name="multi_year_year[]" class="form-control">
                                                                    @for ($i = date('Y'); $i < date('Y') + 10; $i++)
                                                                        <option value="{{ $i }}" @if ($i == $years[$x]) selected @endif>{{ $i }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 col-offset-4">
                                                            <div class="form-group">
                                                                <label class="col-form-label">Monto</label>
                                                                <input type="number" id="multi_year_amount" value="{{ $amounts[$x] }}" name="multi_year_amount[]" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-1">
                                                            @if ($years[$x] == $years[0])
                                                                <button type="button" id="addRow" title="Agregar Nueva Fila" class="btn btn-sm btn-success m-t-30">
                                                                    <span class="f-18">+</span>
                                                                </button>
                                                            @else
                                                                <button type="button" title="Borrar Fila" onclick="delRow(this);" class="btn btn-sm btn-danger m-t-30">
                                                                    <span class="f-20">-</span>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                            @error('multi_year')
                                                <div class="col-sm-8 offset-sm-4">
                                                    <div class="col-form-label">{{ $message }}</div>
                                                </div>
                                            @enderror
                                            </div>
                                        @else
                                            <div id="multi_years" class="w-100 d-none">
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-4 offset-sm-3">
                                                            <div class="form-group">
                                                                <label class="col-form-label">Año</label>
                                                                <select id="multi_year_year" name="multi_year_year[]" class="form-control">
                                                                    @for ($i = date('Y'); $i < date('Y') + 10; $i++)
                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 col-offset-4">
                                                            <div class="form-group">
                                                                <label class="col-form-label">Monto</label>
                                                                <input type="number" id="multi_year_amount" name="multi_year_amount[]" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <button type="button" id="addRow" title="Agregar Nueva Fila" class="btn btn-sm btn-success m-t-30">
                                                                <span class="f-18">+</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-sm-2">
                                            <div class="form-group @error('expenditure_object_id') has-danger @enderror">
                                                <label class="col-form-label">Objeto de Gasto 1<br></label>
                                                <select id="expenditure_object_id" name="expenditure_object_id" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach ($expenditure_objects as $expenditure_object)
                                                    <option value="{{ $expenditure_object->id }}" @if ($expenditure_object->id == old('expenditure_object_id', $order->expenditure_object_id)) selected @endif>{{ $expenditure_object->code }}</option>
                                                @endforeach
                                                </select>
                                                @error('expenditure_object_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group @error('expenditure_object2_id') has-danger @enderror">
                                                <label class="col-form-label">Objeto de Gasto 2<br></label>
                                                <select id="expenditure_object2_id" name="expenditure_object2_id" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach ($expenditure_objects as $expenditure_object)
                                                    <option value="{{ $expenditure_object->id }}" @if ($expenditure_object->id == old('expenditure_object2_id', $order->expenditure_object2_id)) selected @endif>{{ $expenditure_object->code }}</option>
                                                @endforeach
                                                </select>
                                                @error('expenditure_object2_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group @error('expenditure_object3_id') has-danger @enderror">
                                                <label class="col-form-label">Objeto de Gasto 3<br></label>
                                                <select id="expenditure_object3_id" name="expenditure_object3_id" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach ($expenditure_objects as $expenditure_object)
                                                    <option value="{{ $expenditure_object->id }}" @if ($expenditure_object->id == old('expenditure_object3_id', $order->expenditure_object3_id)) selected @endif>{{ $expenditure_object->code }}</option>
                                                @endforeach
                                                </select>
                                                @error('expenditure_object3_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group @error('expenditure_object4_id') has-danger @enderror">
                                                <label class="col-form-label">Objeto de Gasto 4<br></label>
                                                <select id="expenditure_object4_id" name="expenditure_object4_id" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach ($expenditure_objects as $expenditure_object)
                                                    <option value="{{ $expenditure_object->id }}" @if ($expenditure_object->id == old('expenditure_object4_id', $order->expenditure_object4_id)) selected @endif>{{ $expenditure_object->code }}</option>
                                                @endforeach
                                                </select>
                                                @error('expenditure_object4_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group @error('expenditure_object5_id') has-danger @enderror">
                                                <label class="col-form-label">Objeto de Gasto 5<br></label>
                                                <select id="expenditure_object5_id" name="expenditure_object5_id" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach ($expenditure_objects as $expenditure_object)
                                                    <option value="{{ $expenditure_object->id }}" @if ($expenditure_object->id == old('expenditure_object5_id', $order->expenditure_object5_id)) selected @endif>{{ $expenditure_object->code }}</option>
                                                @endforeach
                                                </select>
                                                @error('expenditure_object5_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group @error('expenditure_object6_id') has-danger @enderror">
                                                <label class="col-form-label">Objeto de Gasto 6<br></label>
                                                <select id="expenditure_object6_id" name="expenditure_object6_id" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach ($expenditure_objects as $expenditure_object)
                                                    <option value="{{ $expenditure_object->id }}" @if ($expenditure_object->id == old('expenditure_object6_id', $order->expenditure_object6_id)) selected @endif>{{ $expenditure_object->code }}</option>
                                                @endforeach
                                                </select>
                                                @error('expenditure_object6_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group @error('amount1') has-danger @enderror">
                                            <label class="col-form-label">Monto OG 1 <br></label>
                                            <input type="text" id="amount1" name="amount1" value="{{ old('amount1', $order->amount1) }}" class="form-control amount1 autonumber" data-a-sep="." data-a-dec=",">
                                            @error('amount1')
                                                <div class="col-form-label">{{ $message }}</div>
                                            @enderror
                                            </div>
                                        </div>


                                        <div class="col-sm-2">
                                            <div class="form-group @error('amount2') has-danger @enderror">
                                            <label class="col-form-label">Monto OG 2 <br></label>
                                            <input type="text" id="amount2" name="amount2" value="{{ old('amount2', $order->amount2) }}" class="form-control amount2 autonumber" data-a-sep="." data-a-dec=",">
                                            @error('amount2')
                                                <div class="col-form-label">{{ $message }}</div>
                                            @enderror
                                            </div>
                                        </div>


                                        <div class="col-sm-2">
                                            <div class="form-group @error('amount3') has-danger @enderror">
                                            <label class="col-form-label">Monto OG 3 <br></label>
                                            <input type="text" id="amount3" name="amount3" value="{{ old('amount3', $order->amount3) }}" class="form-control amount3 autonumber" data-a-sep="." data-a-dec=",">
                                            @error('amount3')
                                                <div class="col-form-label">{{ $message }}</div>
                                            @enderror
                                            </div>
                                        </div>


                                        <div class="col-sm-2">
                                            <div class="form-group @error('amount4') has-danger @enderror">
                                            <label class="col-form-label">Monto OG 4 <br></label>
                                            <input type="text" id="amount4" name="amount4" value="{{ old('amount4', $order->amount4) }}" class="form-control amount4 autonumber" data-a-sep="." data-a-dec=",">
                                            @error('amount4')
                                                <div class="col-form-label">{{ $message }}</div>
                                            @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group @error('amount5') has-danger @enderror">
                                            <label class="col-form-label">Monto OG 5 <br></label>
                                            <input type="text" id="amount6" name="amount5" value="{{ old('amount5', $order->amount5) }}" class="form-control amount5 autonumber" data-a-sep="." data-a-dec=",">
                                            @error('amount5')
                                                <div class="col-form-label">{{ $message }}</div>
                                            @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group @error('amount6') has-danger @enderror">
                                            <label class="col-form-label">Monto OG 6 <br></label>
                                            <input type="text" id="amount6" name="amount6" value="{{ old('amount6', $order->amount6) }}" class="form-control amount6 autonumber" data-a-sep="." data-a-dec=",">
                                            @error('amount6')
                                                <div class="col-form-label">{{ $message }}</div>
                                            @enderror
                                            </div>
                                        </div>



                                        <div class="col-sm-4">
                                            <div class="form-group @error('fonacide') has-danger @enderror">
                                                <label class="col-form-label">FONACIDE <br><small>(FONACIDE)</small></label>
                                                <select id="fonacide" name="fonacide" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach (array(0 => 'NO', 1 => 'SI') as $index => $value)
                                                    <option value="{{ $index }}" @if ($index == old('fonacide', $order->fonacide)) selected @endif>{{ $value }}</option>
                                                @endforeach
                                                </select>
                                                @error('fonacide')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('catalogs_technical_annexes') has-danger @enderror">
                                                <label class="col-form-label">La convocante aceptará catálogos, anexos técnicos, folletos y otros textos</label>
                                                <select id="catalogs_technical_annexes" name="catalogs_technical_annexes" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach (array(0 => 'NO', 1 => 'SI') as $index => $value)
                                                    <option value="{{ $index }}" @if ($index == old('catalogs_technical_annexes', $order->catalogs_technical_annexes)) selected @endif>{{ $value }}</option>
                                                @endforeach
                                                </select>
                                                @error('catalogs_technical_annexes')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('alternative_offers') has-danger @enderror">
                                                <label class="col-form-label">Se considerarán ofertas alternativas</label>
                                                <select id="alternative_offers" name="alternative_offers" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach (array(0 => 'NO', 1 => 'SI') as $index => $value)
                                                    <option value="{{ $index }}" @if ($index == old('alternative_offers', $order->alternative_offers)) selected @endif>{{ $value }}</option>
                                                @endforeach
                                                </select>
                                                @error('alternative_offers')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('open_contract') has-danger @enderror">
                                                <label class="col-form-label">Modalidad de Contrato <br><small>(Modalidad de Contrato a utilizar)</small></label>
                                                <select id="open_contract" name="open_contract" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach (array(1 => 'CONTRATO ABIERTO', 2 => 'CONTRATO CERRADO', 3 => 'CONTRATO ABIERTO CON MMIN Y MMAX') as $index => $value)
                                                    <option value="{{ $index }}" @if ($index == old('open_contract', $order->open_contract)) selected @endif>{{ $value }}</option>
                                                @endforeach
                                                </select>
                                                @error('open_contract')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('period_time') has-danger @enderror">
                                                <label class="col-form-label">El período de tiempo estimado de funcionamiento de los bienes</label>
                                                <input type="text" id="period_time" name="period_time" value="{{ old('period_time', $order->period_time) }}" class="form-control">
                                                @error('period_time')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('manufacturer_authorization') has-danger @enderror">
                                                <label class="col-form-label">Autorización del Fabricante</label>
                                                <select id="manufacturer_authorization" name="manufacturer_authorization" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach (array(0 => 'NO', 1 => 'SI') as $index => $value)
                                                    <option value="{{ $index }}" @if ($index == old('manufacturer_authorization', $order->manufacturer_authorization)) selected @endif>{{ $value }}</option>
                                                @endforeach
                                                </select>
                                                @error('manufacturer_authorization')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('financial_advance_percentage_amount') has-danger @enderror">
                                                <label class="col-form-label">Anticipo financiero, porcentaje, monto</label>
                                                <select id="financial_advance_percentage_amount" name="financial_advance_percentage_amount" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach (array(0 => 'NO', 1 => 'SI') as $index => $value)
                                                    <option value="{{ $index }}" @if ($index == old('financial_advance_percentage_amount', $order->financial_advance_percentage_amount)) selected @endif>{{ $value }}</option>
                                                @endforeach
                                                </select>
                                                @error('financial_advance_percentage_amount')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('technical_specifications') has-danger @enderror">
                                                <label class="col-form-label">EETT <br><small>(Especificaciones Técnicas detalladas del bien o servicio a ser adquirido,
                                                    en caso de obras anexar el programa de entrega, en caso de combustibles describir el valor en cupos y tarjetas)</small></label>
                                                <textarea rows="2" id="technical_specifications" name="technical_specifications" class="form-control">{{ old('technical_specifications', $order->technical_specifications) }}</textarea>
                                                @error('technical_specifications')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('samples') has-danger @enderror">
                                                <label class="col-form-label">Solicitud de muestras</label>
                                                <select id="samples" name="samples" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach (array(0 => 'NO', 1 => 'SI') as $index => $value)
                                                    <option value="{{ $index }}" @if ($index == old('samples', $order->samples)) selected @endif>{{ $value }}</option>
                                                @endforeach
                                                </select>
                                                @error('samples')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('delivery_plan') has-danger @enderror">
                                                <label class="col-form-label">Plan de entregas</label>
                                                <input type="text" id="delivery_plan" name="delivery_plan" value="{{ old('delivery_plan', $order->delivery_plan) }}" class="form-control">
                                                @error('delivery_plan')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('evaluation_committee_proposal') has-danger @enderror">
                                                <label class="col-form-label">Propuesta de representantes de miembros del Comité de Evaluación</label>
                                                <textarea rows="2" id="evaluation_committee_proposal" name="evaluation_committee_proposal" class="form-control">{{ old('evaluation_committee_proposal', $order->evaluation_committee_proposal) }}</textarea>
                                                @error('evaluation_committee_proposal')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('payment_conditions') has-danger @enderror">
                                                <label class="col-form-label">Condiciones de Pago</label>
                                                <textarea rows="2" id="payment_conditions" name="payment_conditions" class="form-control">{{ old('payment_conditions', $order->payment_conditions) }}</textarea>
                                                @error('payment_conditions')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('contract_guarantee') has-danger @enderror">
                                                <label class="col-form-label">Garantía del Llamado</label>
                                                <textarea rows="2" id="contract_guarantee" name="contract_guarantee" class="form-control">{{ old('contract_guarantee', $order->contract_guarantee) }}</textarea>
                                                @error('contract_guarantee')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('product_guarantee') has-danger @enderror">
                                                <label class="col-form-label">Garantía del Bien o Servicio</label>
                                                <textarea rows="2" id="product_guarantee" name="product_guarantee" class="form-control">{{ old('product_guarantee', $order->product_guarantee) }}</textarea>
                                                @error('product_guarantee')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('contract_administrator') has-danger @enderror">
                                                <label class="col-form-label">Administrador del Contrato</label>
                                                <input type="text" id="contract_administrator" name="contract_administrator" class="form-control" value="{{ old('contract_administrator', $order->contract_administrator) }}">
                                                @error('contract_administrator')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('contract_validity') has-danger @enderror">
                                                <label class="col-form-label">Vigencia del Contrato</label>
                                                <textarea rows="2" id="contract_validity" name="contract_validity" class="form-control">{{ old('contract_validity', $order->contract_validity) }}</textarea>
                                                @error('contract_validity')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('additional_technical_documents') has-danger @enderror">
                                                <label class="col-form-label">Documentos adicionales que deberá presentar el oferente que demuestran
                                                    que los bienes ofertados cumplen con las especificaciones técnicas.
                                                </label>
                                                <textarea rows="2" id="additional_technical_documents" name="additional_technical_documents" class="form-control">{{ old('additional_technical_documents', $order->additional_technical_documents) }}</textarea>
                                                @error('additional_technical_documents')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('additional_qualified_documents') has-danger @enderror">
                                                <label class="col-form-label">Documentos adicionales que deberá presentar el oferente que demuestran
                                                    que el oferente se halla calificado para ejecutar el contrato.</label>
                                                <textarea rows="2" id="additional_qualified_documents" name="additional_qualified_documents" class="form-control">{{ old('additional_qualified_documents', $order->additional_qualified_documents) }}</textarea>
                                                @error('additional_qualified_documents')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('price_sheet') has-danger @enderror">
                                                <label class="col-form-label">Planilla de Precios (Anexo 1)</label>
                                                <textarea rows="2" id="price_sheet" name="price_sheet" class="form-control">{{ old('price_sheet', $order->price_sheet) }}</textarea>
                                                @error('price_sheet')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('property_title') has-danger @enderror">
                                                <label class="col-form-label">Título de propiedad, planos aprobados por la municipalidad,
                                                    licencia ambiental.
                                                </label>
                                                <textarea rows="2" id="property_title" name="property_title" class="form-control">{{ old('property_title', $order->property_title) }}</textarea>
                                                @error('property_title')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('magnetic_medium') has-danger @enderror">
                                                <label class="col-form-label">Medio Magnético</label>
                                                <input type="text" id="magnetic_medium" name="magnetic_medium" value="{{ old('magnetic_medium', $order->magnetic_medium) }}" class="form-control">
                                                @error('magnetic_medium')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('referring_person_data') has-danger @enderror">
                                                <label class="col-form-label">Datos de la persona referente</label>
                                                <input type="text" id="referring_person_data" name="referring_person_data" value="{{ old('referring_person_data', $order->referring_person_data) }}" class="form-control">
                                                @error('referring_person_data')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('form4_city') has-danger @enderror">
                                                <label class="col-form-label">Localidad <br><small>(Utilizado en formulario 4)</small></label>
                                                <input type="text" id="form4_city" name="form4_city" class="form-control" value="{{ old('form4_city', $order->form4_city) }}">
                                                @error('form4_city')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <label class="col-form-label @error('form4_date') has-danger @enderror">Fecha <br><small>(Utilizado en formulario 4)</small></label>
                                            <div class="input-group @error('form4_date') has-danger @enderror">
                                                @if (is_null($order->dncp_resolution_date))
                                                    <input type="text" id="form4_date" name="form4_date" value="" class="form-control" autocomplete="off">
                                                @else
                                                    <input type="text" id="form4_date" name="form4_date" value="{{ old('form4_date', date('d/m/Y', strtotime($order->form4_date))) }}" class="form-control" autocomplete="off">
                                                @endif

                                                <span class="input-group-append" id="basic-addon">
                                                    <label class="input-group-text" onclick="show('form4_date');"><i class="fa fa-calendar"></i></label>
                                                </span>
                                            </div>
                                            @error('form4_date')
                                            <div class="has-danger">
                                                <div class="col-form-label">{{ $message }}</div>
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group @error('dncp_resolution_number') has-danger @enderror">
                                                <label class="col-form-label">Resolución DNCP Nº</label>
                                                <input type="text" id="dncp_resolution_number" name="dncp_resolution_number" value="{{ old('dncp_resolution_number', $order->dncp_resolution_number) }}" class="form-control">
                                                @error('dncp_resolution_number')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <label class="col-form-label @error('dncp_resolution_date') has-danger @enderror">Fecha de la Resolución DNCP</label>
                                            <div class="input-group @error('dncp_resolution_date') has-danger @enderror">

                                                @if (is_null($order->dncp_resolution_date))
                                                    <input type="text" id="dncp_resolution_date" name="dncp_resolution_date" value="" class="form-control" autocomplete="off">
                                                @else
                                                    <input type="text" id="dncp_resolution_date" name="dncp_resolution_date" value="{{ old('dncp_resolution_date', date('d/m/Y', strtotime($order->dncp_resolution_date))) }}" class="form-control" autocomplete="off">
                                                @endif

                                                <span class="input-group-append" id="basic-addon">
                                                    <label class="input-group-text" onclick="show('dncp_resolution_date');"><i class="fa fa-calendar"></i></label>
                                                </span>
                                                @error('dncp_resolution_date')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group text-center">
                                                <button type="submit" class="btn btn-warning m-b-0 f-12">Modificar</button>
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

    $('#dependency').select2();
    $('#modality').select2();
    $('#sub_program').select2();
    $('#funding_source').select2();
    $('#financial_organism').select2();
    $('#expenditure_object_id').select2();
    $('#expenditure_object2_id').select2();
    $('#expenditure_object3_id').select2();
    $('#expenditure_object4_id').select2();
    $('#expenditure_object5_id').select2();
    $('#expenditure_object6_id').select2();


    $('#begin_date').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy'
    });
    $('#form4_date').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy'
    });
    $('#dncp_resolution_date').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy'
    });

    show = function(id){
        $('#'+id).datepicker('show');
    }

    $('#plurianualidad').change(function(){
        if($('#plurianualidad').val() == 1){
            $('#multi_years').removeClass('d-none');
        }else{
            $('#multi_years').addClass('d-none');
        }
    });

    $('#addRow').click(function(){
        new_row = $('#multi_year_template').clone();
        new_row.removeClass('d-none');
        new_row.find('#multi_year_year').attr('name', 'multi_year_year[]');
        new_row.find('#multi_year_amount').attr('name', 'multi_year_amount[]');
        $('#multi_years').append(new_row);
    });
    delRow = function(element){
        element.closest('#multi_year_template').remove();
    }

});
</script>
@endpush
