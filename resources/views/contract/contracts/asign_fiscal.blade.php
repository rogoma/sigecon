@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="fa fa-file-text bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Editar Contrato</h5>
                        <span>Asignar Fiscal a Contrato N° {{$contract->number_year }}</span>
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
                            {{-- <a href="{{ route('contracts.index') }}">Llamados</a> --}}
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
                                    {{-- <h5>Editar Llamado</h5> --}}
                                    {{-- <br> --}}
                                    <label id="fecha_actual" name="fecha_actual"  style="font-size: 20px;color: #FF0000;float: left;" for="fecha_actual">{{ Carbon\Carbon::now()->format('d/m/Y') }}</label>
                                    <h3 class="text-center">Datos del Contrato</h3>
                                    {{-- <label style="font-size: 20px;color: #FF0000;float: left;">FECHA: </label> --}}
                                </div>
                                <div class="card-block">
                                    {{-- <h3 class="text-center">Asignar Fiscal al Contrato</h3>
                                    <br> --}}
                                    <form class="row" method="POST" action="{{ route('contracts.update.fiscal', $contract->id) }}">
                                        @csrf
                                        @method('PUT')
                                        {{-- PARA MOSTRAR ERRORES DE VALIDACION --}}
                                        {{-- <div class="col-sm-12">
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div> --}}

                                        <div class="col-sm-4">
                                            <div class="form-group @error('description') has-danger @enderror">
                                                <label class="col-form-label">Descripción <br></label>
                                                <br>
                                                <label for="comments">{{ old('comments',$contract->description) }}</label>
                                                {{-- <textarea rows="2" id="description" name="description" class="form-control">{{ old('description', $contract->description) }}</textarea> --}}
                                                @error('description')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group @error('iddncp') has-danger @enderror">
                                                <label class="col-form-label">ID DNCP</label>
                                                <br>
                                                <label for="comments">{{ old('iddncp', number_format($contract->iddncp, 0, ',', '.')) }}</label>
                                                {{-- <input type="text" id="iddncp" name="iddncp" value="{{ old('iddncp', number_format($contract->iddncp, 0, ',', '.')) }}" class="form-control iddncp autonumber" data-a-sep="."> --}}

                                                @error('iddncp')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group @error('number_year') has-danger @enderror">
                                                <label class="col-form-label">N° Contrato/Año</label>
                                                <br>
                                                <label for="comments">{{ old('comments',$contract->number_year) }}</label>
                                                {{-- <input type="text" id="number_year" name="number_year" maxlength="9" value= "{{ old('number_year', $contract->number_year) }}" class="form-control"> --}}
                                                @error('number_year')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group @error('provider_id') has-danger @enderror">
                                                <label class="col-form-label">Contratista</label>
                                                <select id="provider_id" name="provider_id" class="form-control">
                                                    <option value="">Seleccionar Contratista</option>
                                                @foreach ($providers as $provider)
                                                    <option disabled="true" {{ $provider->id }}" @if ($provider->id == old('provider_id', $contract->provider_id)) selected @endif>{{ $provider->description }}</option>
                                                    {{-- <option value="{{ $modality->id }}" @if ($modality->id == old('modality', $contract->modality_id)) selected @endif>{{ $modality->description }}</option> --}}
                                                @endforeach
                                                </select>
                                                @error('provider_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- #8 --}}
                                        <div class="col-sm-2">
                                            <div class="form-group @error('contract_state_id') has-danger @enderror">
                                                <label class="col-form-label">Estado<br></label>
                                                <select id="contract_state_id" name="contract_state_id" class="form-control">
                                                    <option value="">Seleccionar Estado</option>
                                                @foreach ($contr_states as $state)
                                                    <option disabled="true" value="{{ $state->id }}" @if ($state->id == old('contract_state_id', $contract->contract_state_id)) selected @endif>{{ $state->description }}</option>
                                                @endforeach
                                                </select>
                                                @error('contract_state_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group @error('modality_id') has-danger @enderror">
                                                <label class="col-form-label">Modalidad<br></small></label>
                                                <select id="modality_id" name="modality_id" class="form-control"">
                                                    <option value="">Seleccionar Modalidad</option>
                                                @foreach ($modalities as $modality)
                                                    <option disabled="true" value="{{ $modality->id }}" @if ($modality->id == old('modality_id', $contract->modality_id)) selected @endif>{{ $modality->description }}</option>
                                                @endforeach
                                                </select>
                                                @error('modality_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group @error('financial_organism_id') has-danger @enderror">
                                                <label class="col-form-label">Organismo Financiador<br></label>
                                                <select id="financial_organism_id" name="financial_organism_id" class="form-control">
                                                    <option value="">Seleccionar Organismo Financiador</option>
                                                @foreach ($financial_organisms as $financial_organism)
                                                    <option disabled="true" value="{{ $financial_organism->id }}" @if ($financial_organism->id == old('financial_organism_id', $contract->financial_organism_id)) selected @endif>{{ $financial_organism->code.' - '.$financial_organism->description }}</option>
                                                @endforeach
                                                </select>
                                                @error('financial_organism_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group @error('contract_type_id') has-danger @enderror">
                                                <label class="col-form-label">Tipo de Contrato</label>
                                                <select id="contract_type_id" name="contract_type_id" class="form-control">
                                                    <option value="">Seleccionar Tipo de Contrato</option>
                                                @foreach ($contract_types as $contract_type)
                                                    <option disabled="true" value="{{ $contract_type->id }}" @if ($contract_type->id == old('contract_type_id', $contract->contract_type_id)) selected @endif>{{$contract_type->description }}</option>
                                                @endforeach
                                                </select>
                                                @error('contract_type_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-2">
                                            <div class="form-group @error('minim_amount') has-danger @enderror">
                                                <label class="col-form-label">Monto Mínimo <br></label>
                                                <br>
                                                <label for="comments">{{ old('minim_amount', number_format($contract->minim_amount, 0, ',', '.')) }}</label>                                                
                                                @error('minim_amount')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group @error('total_amount') has-danger @enderror">
                                                <label class="col-form-label">Monto Máximo <br></label>
                                                <br>
                                                <label for="comments">{{ old('total_amount', number_format($contract->total_amount, 0, ',', '.')) }}</label>
                                                {{-- <input disabled="true" type="text" id="total_amount" name="total_amount" value="{{ old('total_amount', number_format($contract->total_amount, 0, ',', '.')) }}" class="form-control total_amount autonumber" data-a-sep="." data-a-dec=","> --}}
                                                @error('total_amount')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group @error('open_contract') has-danger @enderror">
                                                <label class="col-form-label">Contrato (ABIERTO / CERRADO) <br></label>
                                                <select id="open_contract" name="open_contract" class="form-control">
                                                    <option value="">Seleccionar</option>
                                                @foreach (array(1 => 'CONTRATO ABIERTO', 2 => 'CONTRATO CERRADO') as $index => $value)
                                                    <option disabled="true" value="{{ $index }}" @if ($index == old('open_contract', $contract->open_contract)) selected @endif>{{ $value }}</option>
                                                @endforeach
                                                </select>
                                                @error('open_contract')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group @error('dependency_id') has-danger @enderror">
                                                <label class="col-form-label">Depedendencia Responsable </label>
                                                <select id="dependency_id" name="dependency_id" class="form-control">
                                                    <option value="">Seleccionar Depedendencia</option>
                                                @foreach ($dependencies as $dependency)
                                                    <option disabled="true" value="{{ $dependency->id    }}" @if ($dependency->id    == old('dependency_id', $contract->dependency_id      )) selected @endif>{{$dependency->description }}</option>
                                                    {{-- <option value="{{ $contract_type->id }}" @if ($contract_type->id == old('contract_type_id', $contract->contract_type_id)) selected @endif>{{$contract_type->description }}</option> --}}
                                                @endforeach
                                                </select>
                                                @error('dependency_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group @error('contract_admin_id') has-danger @enderror">
                                                <label class="col-form-label">Administrador del Contrato</label>
                                                <select id="contract_admin_id" name="contract_admin_id" class="form-control">
                                                    <option value="">Seleccionar Administrador del Contrato</option>
                                                @foreach ($dependencies as $dependency)
                                                    <option disabled="true" value="{{ $dependency->id    }}" @if ($dependency->id    == old('contract_admin_id', $contract->dependency_id      )) selected @endif>{{$dependency->description }}</option>
                                                    {{-- <option value="{{ $contract_type->id }}" @if ($contract_type->id == old('contract_type_id', $contract->contract_type_id)) selected @endif>{{$contract_type->description }}</option> --}}
                                                @endforeach
                                                </select>
                                                @error('contract_admin_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="form-group @error('comments') has-danger @enderror">
                                                <label class="col-form-label">Comentarios: {{ old('comments',$contract->comments) }} </label>
                                                <br>
                                                {{-- <label>Comentarios for="comments">{{ old('comments',$contract->comments) }}</label>                                                 --}}
                                                @error('comments')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <br><br><br>
                                            <h3 class="text-center">Asignar Fiscal al Contrato</h3>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group @error('fiscal1_id') has-danger @enderror">
                                                <label class="col-form-label">Fiscal N° 1<br></small></label>
                                                <select id="fiscal1_id" name="fiscal1_id" class="form-control">
                                                    <option value="">Seleccionar Usuario Fiscal</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}" @if ($user->id == old('fiscal1_id', $contract->fiscal1_id)) selected @endif>{{ $user->name }} {{ $user->lastname }} - {{ $user->position->description }}</option>
                                                @endforeach
                                                </select>
                                                @error('fiscal1_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group @error('fiscal2_id') has-danger @enderror">
                                                <label class="col-form-label">Fiscal N° 2<br></small></label>
                                                <select id="fiscal2_id" name="fiscal2_id" class="form-control">
                                                    <option value="">Seleccionar Usuario Fiscal</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}" @if ($user->id == old('fiscal2_id', $contract->fiscal2_id)) selected @endif>{{ $user->name }} {{ $user->lastname }} - {{ $user->position->description }}</option>
                                                @endforeach
                                                </select>
                                                @error('fiscal2_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group @error('fiscal3_id') has-danger @enderror">
                                                <label class="col-form-label">Fiscal N° 3<br></small></label>
                                                <select id="fiscal3_id" name="fiscal3_id" class="form-control">
                                                    <option value="">Seleccionar Usuario Fiscal</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}" @if ($user->id == old('fiscal3_id', $contract->fiscal3_id)) selected @endif>{{ $user->name }} {{ $user->lastname }} - {{ $user->position->description }}</option>
                                                @endforeach
                                                </select>
                                                @error('fiscal3_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group @error('fiscal4_id') has-danger @enderror">
                                                <label class="col-form-label">Fiscal N° 4<br></small></label>
                                                <select id="fiscal4_id" name="fiscal4_id" class="form-control">
                                                    <option value="">Seleccionar Usuario Fiscal</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}" @if ($user->id == old('fiscal4_id', $contract->fiscal4_id)) selected @endif>{{ $user->name }} {{ $user->lastname }} - {{ $user->position->description }}</option>
                                                @endforeach
                                                </select>
                                                @error('fiscal4_id')
                                                    <div class="col-form-label">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="container">
                                        <br><br>
                                        <div class="col-sm-12">
                                            <div class="form-group text-center">
                                                <button type="submit" class="btn btn-danger m-b-0 f-12">Asignar Fiscal</button>
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
    $('#provider_id').select2();
    $('#modality_id').select2();
    $('#contract_state_id').select2();
    $('#contract_type_id').select2();
    $('#funding_source_id').select2();
    $('#financial_organism_id').select2();
    $('#dependency_id').select2();
    $('#contract_admin_id').select2();
    $('#open_contract').select2();
    $('#fiscal1_id').select2();
    $('#fiscal2_id').select2();
    $('#fiscal3_id').select2();
    $('#fiscal4_id').select2();



});
</script>
@endpush



